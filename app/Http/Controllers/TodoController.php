<?php

namespace App\Http\Controllers;

use App\Models\FollowUpIntervention;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TodoController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('task_date', now()->toDateString());

        $personalTodos = Todo::with('user:id,name')
            ->where('user_id', Auth::id())
            ->whereDate('task_date', $selectedDate)
            ->where('status', '!=', Todo::STATUS_DONE)
            ->latest()
            ->get();

        $followUpTasks = $this->scopedFollowUpQuery()
            ->whereDate('follow_up_interventions.to_be_taken_date', $selectedDate)
            ->where('follow_up_interventions.task_status', '!=', Todo::STATUS_DONE)
            ->orderBy('follow_up_interventions.to_be_taken_date')
            ->select([
                'follow_up_interventions.*',
                'formal_cases.central_id as case_central_id',
                'formal_cases.full_name as case_name',
                'formal_cases.district_id as case_district_id',
                'formal_cases.pngo_id as case_pngo_id',
            ])
            ->get();

        $calendarDays = $this->calendarDays();

        return view('dashboard.admin.todos', [
            'selectedDate' => $selectedDate,
            'personalTodos' => $personalTodos,
            'followUpTasks' => $followUpTasks,
            'calendarDays' => $calendarDays,
            'statusOptions' => $this->statusOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'task_date' => ['required', 'date'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = Auth::user();

        Todo::create([
            'user_id' => $user->id,
            'district_id' => $user->district_id,
            'pngo_id' => $user->pngo_id,
            'task_date' => $validated['task_date'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => Todo::STATUS_PENDING,
        ]);

        return redirect()
            ->route('todos.index', ['task_date' => $validated['task_date']])
            ->with('success', 'ToDo task added successfully.');
    }

    public function updateStatus(Request $request, Todo $todo)
    {
        abort_if((int) $todo->user_id !== (int) Auth::id(), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys($this->statusOptions()))],
        ]);

        $todo->status = $validated['status'];
        $todo->completed_at = $validated['status'] === Todo::STATUS_DONE ? now() : null;
        $todo->save();

        return redirect()
            ->route('todos.index', ['task_date' => optional($todo->task_date)->format('Y-m-d')])
            ->with('success', 'ToDo status updated.');
    }

    public function updateFollowUpStatus(Request $request, FollowUpIntervention $followUpIntervention)
    {
        $this->authorizeFollowUpScope($followUpIntervention);

        $validated = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys($this->statusOptions()))],
        ]);

        $followUpIntervention->task_status = $validated['status'];
        $followUpIntervention->task_completed_by = $validated['status'] === Todo::STATUS_DONE ? Auth::id() : null;
        $followUpIntervention->task_completed_at = $validated['status'] === Todo::STATUS_DONE ? now() : null;
        $followUpIntervention->save();

        return redirect()
            ->route('todos.index', ['task_date' => optional($followUpIntervention->to_be_taken_date)->format('Y-m-d')])
            ->with('success', 'Follow-up task status updated.');
    }

    private function scopedFollowUpQuery()
    {
        $query = FollowUpIntervention::query()
            ->leftJoin('formal_cases', function ($join) {
                $join->on('follow_up_interventions.central_id', '=', 'formal_cases.id')
                    ->orOn('follow_up_interventions.central_id', '=', 'formal_cases.central_id');
            })
            ->whereNull('formal_cases.deleted_at')
            ->whereNotNull('follow_up_interventions.to_be_taken_date')
            ->whereNotNull('follow_up_interventions.intervention_to_be_taken');

        $user = Auth::user();

        $user->applyDistrictPngoScope($query, 'formal_cases.district_id', 'formal_cases.pngo_id');

        return $query;
    }

    private function authorizeFollowUpScope(FollowUpIntervention $followUpIntervention): void
    {
        $case = DB::table('formal_cases')
            ->where(function ($query) use ($followUpIntervention) {
                $query
                    ->where('id', $followUpIntervention->central_id)
                    ->orWhere('central_id', $followUpIntervention->central_id);
            })
            ->whereNull('deleted_at')
            ->first();

        abort_if(! $case, 404);

        $user = Auth::user();

        abort_if(! $user->canAccessDistrictPngo($case->district_id, $case->pngo_id), 403);
    }

    private function calendarDays(): array
    {
        $start = now()->startOfDay();

        return collect(range(0, 13))->map(function ($offset) use ($start) {
            $date = $start->copy()->addDays($offset);
            $dateString = $date->toDateString();

            $personalCount = Todo::where('user_id', Auth::id())
                ->whereDate('task_date', $dateString)
                ->where('status', '!=', Todo::STATUS_DONE)
                ->count();

            $followUpCount = (clone $this->scopedFollowUpQuery())
                ->whereDate('follow_up_interventions.to_be_taken_date', $dateString)
                ->where('follow_up_interventions.task_status', '!=', Todo::STATUS_DONE)
                ->count();

            return [
                'date' => $dateString,
                'label' => $date->format('j M'),
                'day' => $date->format('D'),
                'total' => $personalCount + $followUpCount,
            ];
        })->all();
    }

    private function statusOptions(): array
    {
        return [
            Todo::STATUS_PENDING => 'Pending',
            Todo::STATUS_IN_PROGRESS => 'In Progress',
            Todo::STATUS_DONE => 'Solved',
        ];
    }
}
