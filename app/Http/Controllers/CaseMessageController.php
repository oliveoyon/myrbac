<?php

namespace App\Http\Controllers;

use App\Models\CaseMessage;
use App\Models\CaseMessageThread;
use App\Models\FormalCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaseMessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $threads = CaseMessageThread::with([
                'formalCase:id,central_id,full_name,district_id,pngo_id',
                'formalCase.district:id,name',
                'formalCase.pngo:id,name',
                'latestMessage.sender:id,name,full_name',
                'latestMessage.receiver:id,name,full_name',
            ])
            ->when(! $user->can('View All Case Messages'), function ($query) use ($user) {
                $query->whereHas('messages', function ($messageQuery) use ($user) {
                    $messageQuery
                        ->where('sender_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                });
            })
            ->whereHas('formalCase', function ($caseQuery) use ($user) {
                $user->applyDistrictPngoScope($caseQuery);
            })
            ->latest()
            ->paginate(25);

        return view('dashboard.report.case-messages', compact('threads'));
    }

    public function show(FormalCase $formalCase)
    {
        $this->authorizeCaseAccess($formalCase);

        $thread = CaseMessageThread::where('formal_case_id', $formalCase->id)->first();

        if ($thread) {
            CaseMessage::where('case_message_thread_id', $thread->id)
                ->where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $thread->load([
                'messages.sender:id,name,full_name',
                'messages.receiver:id,name,full_name',
            ]);
        }

        return response()->json([
            'case' => [
                'id' => $formalCase->id,
                'central_id' => $formalCase->central_id,
                'full_name' => $formalCase->full_name,
            ],
            'thread' => [
                'id' => $thread?->id,
                'status' => $thread?->status ?: 'open',
            ],
            'receivers' => $this->receiverOptions($formalCase),
            'messages' => collect($thread?->messages ?: [])->map(function ($message) {
                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender' => $message->sender?->full_name ?: $message->sender?->name ?: 'Unknown',
                    'receiver' => $message->receiver?->full_name ?: $message->receiver?->name ?: 'Unknown',
                    'is_mine' => (int) $message->sender_id === (int) Auth::id(),
                    'read_at' => optional($message->read_at)->format('j M, Y g:i A'),
                    'created_at' => optional($message->created_at)->format('j M, Y g:i A'),
                ];
            }),
        ]);
    }

    public function store(Request $request, FormalCase $formalCase)
    {
        $this->authorizeCaseAccess($formalCase);
        abort_if(! Auth::user()->can('Send Case Message') && ! Auth::user()->can('Reply Case Message'), 403);

        $validated = $request->validate([
            'receiver_id' => ['required', 'exists:users,id'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $allowedReceiverIds = collect($this->receiverOptions($formalCase))->pluck('id')->map(fn ($id) => (int) $id);
        abort_if(! $allowedReceiverIds->contains((int) $validated['receiver_id']), 403);

        $thread = CaseMessageThread::firstOrCreate(
            ['formal_case_id' => $formalCase->id],
            ['status' => 'open', 'created_by' => Auth::id()]
        );

        if ($thread->status === 'resolved') {
            $thread->update([
                'status' => 'open',
                'resolved_by' => null,
                'resolved_at' => null,
            ]);
        }

        $receiver = User::with('roles:id,name')->findOrFail($validated['receiver_id']);

        CaseMessage::create([
            'case_message_thread_id' => $thread->id,
            'formal_case_id' => $formalCase->id,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'receiver_role' => $receiver->roles->pluck('name')->first(),
            'message' => $validated['message'],
        ]);

        return response()->json(['success' => true]);
    }

    public function resolve(CaseMessageThread $thread)
    {
        $thread->load('formalCase');
        $this->authorizeCaseAccess($thread->formalCase);
        abort_if(! Auth::user()->can('Resolve Case Message'), 403);

        $thread->update([
            'status' => 'resolved',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    private function authorizeCaseAccess(FormalCase $formalCase): void
    {
        abort_if(! Auth::user()->canAccessDistrictPngo($formalCase->district_id, $formalCase->pngo_id), 403);
    }

    private function receiverOptions(FormalCase $formalCase): array
    {
        $user = Auth::user();
        $query = User::with('roles:id,name')
            ->where('id', '!=', $user->id)
            ->whereIn('status', [1, 2]);

        if ($user->can('View All Case Messages')) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->whereIn('name', ['Paralegal', 'DPO', 'M&EO', 'PNGO Focal', 'Admin', 'Super Admin']);
            });
        } elseif ($user->hasRole('DPO')) {
            $query->whereKey($formalCase->user_id);
        } elseif ($user->hasAnyRole(['M&EO', 'PNGO Focal'])) {
            $query->whereHas('roles', fn ($roleQuery) => $roleQuery->where('name', 'DPO'));
        } elseif ($user->hasRole('Paralegal')) {
            $query->whereHas('roles', fn ($roleQuery) => $roleQuery->where('name', 'DPO'));
        } else {
            $query->whereRaw('1 = 0');
        }

        return $query
            ->orderBy('full_name')
            ->orderBy('name')
            ->get()
            ->filter(function (User $receiver) use ($formalCase, $user) {
                if ((int) $receiver->id === (int) $formalCase->user_id && $user->hasRole('DPO')) {
                    return true;
                }

                if ($user->can('View All Case Messages') && $receiver->hasAnyRole(['Admin', 'Super Admin'])) {
                    return true;
                }

                return $receiver->canAccessDistrictPngo($formalCase->district_id, $formalCase->pngo_id);
            })
            ->map(function (User $receiver) {
                return [
                    'id' => $receiver->id,
                    'name' => $receiver->full_name ?: $receiver->name,
                    'role' => $receiver->roles->pluck('name')->implode(', '),
                ];
            })
            ->values()
            ->all();
    }
}
