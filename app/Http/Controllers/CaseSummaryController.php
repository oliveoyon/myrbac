<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCaseSummaryRequest;
use App\Http\Requests\UpdateCaseSummaryRequest;
use App\Models\Act;
use App\Models\CaseSummary;
use App\Models\CaseSummaryAttachment;
use App\Models\Court;
use App\Models\District;
use App\Models\Pngo;
use App\Services\CaseSummaryCentralIdService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CaseSummaryController extends Controller
{
    private const BOOLEAN_FIELDS = [
        'missing_documents', 'absent_witnesses', 'procedural_delays', 'adjournment_by_parties',
        'adjournment_by_court', 'lack_of_lawyer', 'lack_of_prosecutor', 'settled_outside_court',
        'death_of_accused', 'death_of_witness', 'pending_for_argument', 'pending_for_judgment',
        'dismissed_but_pending', 'administrative_issues', 'priority_hearing', 'mediation_settlement',
        'diversion_to_probation', 'withdrawal', 'administrative_followup', 'case_scheduling',
        'other_solution', 'disposed_through_mediation', 'disposed_by_judgment',
        'judgment_written_delivered', 'decree_pronounced', 'case_dismissed', 'acquitted',
    ];

    private const MAIN_FIELDS = [
        'summary_type', 'summary_date', 'court_id', 'case_nature', 'case_number',
        'compoundable_sections', 'non_compoundable_section', 'case_section_comments', 'case_filing_date',
        'offence_subject', 'current_case_status', 'current_case_status_other', 'hearing_count',
        'trial_hearing_count', 'last_hearing_date', 'next_hearing_date', 'accused_status',
        'accused_status_other', 'number_of_witnesses', 'witnesses_examined',
        'missing_documents_other', 'missing_documents_remarks', 'absent_witnesses_type',
        'government_witness_type', 'government_witness_other', 'absent_witness_information',
        'absent_witnesses_remarks', 'procedural_delays_type', 'procedural_delays_other',
        'procedural_delays_remarks', 'adjournment_by_parties_remarks', 'adjournment_by_court_remarks',
        'lack_of_lawyer_remarks', 'lack_of_prosecutor_remarks', 'settled_outside_court_remarks',
        'death_of_accused_remarks', 'death_of_witness_remarks', 'pending_for_argument_remarks',
        'pending_for_judgment_remarks', 'dismissed_but_pending_remarks', 'administrative_issues_specify',
        'administrative_issues_remarks', 'other_solution_specify', 'solution_remarks',
        'collect_case_documents_date', 'communicate_witness_date', 'communicate_parties_date',
        'refer_mediation_date', 'refer_mediation_destination', 'refer_mediation_destination_details',
        'present_judge_date', 'present_judicial_conference_date', 'other_paralegal_intervention_details',
        'other_paralegal_intervention_date', 'judicial_mediation_destination', 'judicial_mediation_details',
        'judicial_mediation_date', 'judiciary_priority_hearing_date', 'judiciary_diversion_to_probation_date',
        'judiciary_withdrawal_date', 'judiciary_administrative_followup_date',
        'judiciary_case_scheduling_date', 'judiciary_other_action_details', 'judiciary_other_action_date',
        'mediation_disposal_date', 'judgment_details', 'judgment_disposal_date', 'case_dismissed_date',
        'acquittal_reason', 'acquittal_date',
    ];

    public function create(): View
    {
        return $this->formView();
    }

    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:125'],
            'district_id' => ['nullable', 'integer', 'exists:districts,id'],
            'pngo_id' => ['nullable', 'integer', 'exists:pngos,id'],
            'status' => ['nullable', Rule::in([CaseSummary::STATUS_SUBMITTED, CaseSummary::STATUS_DPO_VERIFIED])],
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
        ]);
        $user = $request->user();
        $query = $user->applyDistrictPngoScope(
            CaseSummary::query(),
            'case_summaries.district_id',
            'case_summaries.pngo_id'
        );

        $query->with(['district:id,name', 'pngo:id,name', 'court:id,name', 'creator:id,name,full_name'])
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($nested) use ($search) {
                    $nested->where('central_id', 'like', "%{$search}%")
                        ->orWhere('case_number', 'like', "%{$search}%");
                });
            })
            ->when($validated['district_id'] ?? null, fn ($query, $districtId) => $query->where('district_id', $districtId))
            ->when($validated['pngo_id'] ?? null, fn ($query, $pngoId) => $query->where('pngo_id', $pngoId))
            ->when(isset($validated['status']), fn ($query) => $query->where('status', $validated['status']))
            ->when($validated['from_date'] ?? null, fn ($query, $date) => $query->whereDate('summary_date', '>=', $date))
            ->when($validated['to_date'] ?? null, fn ($query, $date) => $query->whereDate('summary_date', '<=', $date));

        $caseSummaries = $query->latest('summary_date')->latest('id')->paginate(20)->withQueryString();
        $scope = $this->entryScopeOptions();

        return view('dashboard.admin.case-summary-management', [
            'caseSummaries' => $caseSummaries,
            'districts' => $scope['districts'],
            'pngos' => $scope['pngos'],
        ]);
    }

    public function store(StoreCaseSummaryRequest $request, CaseSummaryCentralIdService $centralIds): RedirectResponse
    {
        $validated = $request->validated();
        $this->authorizeEntryScope((int) $validated['district_id'], (int) $validated['pngo_id']);

        $storedPaths = [];

        try {
            $caseSummary = DB::transaction(function () use ($request, $validated, $centralIds, &$storedPaths) {
                $district = District::findOrFail($validated['district_id']);
                $caseSummary = CaseSummary::create(array_merge(
                    $this->mainPayload($validated),
                    [
                        'central_id' => $centralIds->nextForDistrict($district),
                        'district_id' => $district->id,
                        'pngo_id' => $validated['pngo_id'],
                        'status' => CaseSummary::STATUS_SUBMITTED,
                        'created_by' => $request->user()->id,
                        'submitted_at' => now(),
                    ]
                ));

                $this->replaceParties($caseSummary, $validated);
                $caseSummary->acts()->sync($validated['act_ids']);
                $this->appendFollowUp($caseSummary, $validated, $request->user()->id);
                $this->storeAttachments($request, $caseSummary, $storedPaths);

                return $caseSummary;
            }, 3);
        } catch (\Throwable $exception) {
            $this->deleteStoredFiles($storedPaths);
            report($exception);

            return back()->withInput()->with('error', 'The case summary could not be saved. Please try again.');
        }

        return redirect()
            ->route('case-summaries.create')
            ->with('success', "Case summary saved successfully. Central ID: {$caseSummary->central_id}");
    }

    public function edit(CaseSummary $caseSummary): View
    {
        $this->authorizeCaseScope($caseSummary);
        $this->authorizeVerifiedEdit($caseSummary);
        $caseSummary->load(['parties', 'acts:id', 'attachments']);

        return $this->formView($caseSummary);
    }

    public function update(
        UpdateCaseSummaryRequest $request,
        CaseSummary $caseSummary
    ): RedirectResponse {
        $this->authorizeCaseScope($caseSummary);
        $this->authorizeVerifiedEdit($caseSummary);
        $validated = $request->validated();
        $this->authorizeEntryScope((int) $validated['district_id'], (int) $validated['pngo_id']);
        $storedPaths = [];

        try {
            DB::transaction(function () use ($request, $caseSummary, $validated, &$storedPaths) {
                $caseSummary->update(array_merge($this->mainPayload($validated), [
                    'district_id' => $validated['district_id'],
                    'pngo_id' => $validated['pngo_id'],
                    'updated_by' => $request->user()->id,
                ]));

                $this->replaceParties($caseSummary, $validated);
                $caseSummary->acts()->sync($validated['act_ids']);
                $this->appendFollowUp($caseSummary, $validated, $request->user()->id);
                $this->storeAttachments($request, $caseSummary, $storedPaths);
            }, 3);
        } catch (\Throwable $exception) {
            $this->deleteStoredFiles($storedPaths);
            report($exception);

            return back()->withInput()->with('error', 'The case summary could not be updated. Please try again.');
        }

        return redirect()
            ->route('case-summaries.edit', $caseSummary)
            ->with('success', "Case summary {$caseSummary->central_id} updated successfully.");
    }

    public function verifyByDpo(Request $request, CaseSummary $caseSummary): RedirectResponse
    {
        $this->authorizeCaseScope($caseSummary);

        if ((int) $caseSummary->status !== CaseSummary::STATUS_SUBMITTED) {
            return back()->with('error', 'Only submitted case summaries can be verified by the DPO.');
        }

        $validated = $request->validate([
            'dpo_verification_remarks' => ['nullable', 'string', 'max:2000'],
        ]);

        $caseSummary->update([
            'status' => CaseSummary::STATUS_DPO_VERIFIED,
            'dpo_verified_by' => $request->user()->id,
            'dpo_verified_at' => now(),
            'dpo_verification_remarks' => $validated['dpo_verification_remarks'] ?? null,
            'updated_by' => $request->user()->id,
        ]);

        return back()->with('success', "Case summary {$caseSummary->central_id} verified successfully.");
    }

    public function show(CaseSummary $caseSummary): View
    {
        $this->authorizeCaseScope($caseSummary);
        $caseSummary->load([
            'district:id,name', 'pngo:id,name', 'court:id,name',
            'creator:id,name,full_name', 'updater:id,name,full_name',
            'dpoVerifier:id,name,full_name', 'acts:id,title,act_number,year',
            'parties', 'followUps.creator:id,name,full_name', 'attachments.uploader:id,name,full_name',
        ]);

        return view('dashboard.admin.case-summary-view', compact('caseSummary'));
    }

    public function addFollowUp(Request $request, CaseSummary $caseSummary): RedirectResponse
    {
        $this->authorizeCaseScope($caseSummary);
        $validated = $request->validate([
            'follow_up_date' => ['nullable', 'date'],
            'follow_up_action' => ['nullable', 'required_with:follow_up_date', 'string'],
            'intervention_to_be_taken_date' => ['nullable', 'date'],
            'interventions_to_be_taken' => ['nullable', 'required_with:intervention_to_be_taken_date', 'string'],
        ]);

        if (! collect($validated)->contains(fn ($value) => filled($value))) {
            return back()->withErrors(['follow_up_date' => 'Please enter progress or an intervention to be taken.']);
        }

        $caseSummary->followUps()->create(array_merge($validated, ['created_by' => $request->user()->id]));

        return back()->with('success', 'Follow-up added successfully without changing earlier records.');
    }

    public function viewAttachment(CaseSummaryAttachment $attachment)
    {
        $attachment->loadMissing('caseSummary');
        $this->authorizeCaseScope($attachment->caseSummary);
        abort_unless(Storage::disk('public')->exists($attachment->file_path), 404);

        return Storage::disk('public')->response(
            $attachment->file_path,
            $attachment->original_file_name,
            ['Content-Type' => $attachment->mime_type]
        );
    }

    public function downloadAttachment(CaseSummaryAttachment $attachment)
    {
        $attachment->loadMissing('caseSummary');
        $this->authorizeCaseScope($attachment->caseSummary);
        abort_unless(Storage::disk('public')->exists($attachment->file_path), 404);

        return Storage::disk('public')->download($attachment->file_path, $attachment->original_file_name);
    }

    public function deleteAttachment(CaseSummaryAttachment $attachment): RedirectResponse
    {
        $attachment->loadMissing('caseSummary');
        $this->authorizeCaseScope($attachment->caseSummary);
        $path = $attachment->file_path;
        $attachment->delete();
        Storage::disk('public')->delete($path);

        return back()->with('success', 'Attachment deleted successfully.');
    }

    private function formView(?CaseSummary $caseSummary = null): View
    {
        $scope = $this->entryScopeOptions();
        $districts = $scope['districts'];
        $pngos = $scope['pngos'];
        $courts = Court::with('district:id,name')
            ->whereIn('district_id', $districts->pluck('id'))
            ->orderBy('district_id')
            ->orderBy('name')
            ->get();
        $acts = Act::orderBy('title')->get(['id', 'title', 'act_number', 'year']);
        $initialFormData = $caseSummary ? $this->initialFormData($caseSummary) : [];

        return view('dashboard.admin.case-summary-demo', compact(
            'districts', 'pngos', 'courts', 'acts', 'caseSummary', 'initialFormData'
        ));
    }

    private function entryScopeOptions(): array
    {
        $user = auth()->user();

        if ($user->district_id && $user->pngo_id) {
            $pngos = Pngo::with('district')->whereKey($user->pngo_id)->get();
        } elseif ($user->hasPngoScopes()) {
            $scopePairs = $user->pngoScopes()->get(['district_id', 'pngo_id']);
            $pngos = Pngo::with('district')
                ->whereIn('id', $scopePairs->pluck('pngo_id'))
                ->get()
                ->filter(fn (Pngo $pngo) => $scopePairs->contains(
                    fn ($scope) => (int) $scope->district_id === (int) $pngo->district_id
                        && (int) $scope->pngo_id === (int) $pngo->id
                ))
                ->values();
        } elseif ($user->hasAnyRole(['Admin', 'Super Admin'])) {
            $pngos = Pngo::with('district')->orderBy('name')->get();
        } else {
            abort(403, 'Your account has no district and PNGO scope for case summary entry.');
        }

        abort_if($pngos->isEmpty(), 403, 'No district and PNGO scope is available for your account.');

        return [
            'pngos' => $pngos,
            'districts' => District::whereIn('id', $pngos->pluck('district_id')->unique())
                ->orderBy('name')
                ->get(),
        ];
    }

    private function authorizeEntryScope(int $districtId, int $pngoId): void
    {
        $user = auth()->user();
        $pngo = Pngo::find($pngoId);

        abort_if(! $pngo || (int) $pngo->district_id !== $districtId, 422, 'The selected PNGO is not mapped to the selected district.');

        $allowed = $user->hasPngoScopes()
            ? $user->pngoScopes()->where('district_id', $districtId)->where('pngo_id', $pngoId)->exists()
            : ($user->hasAnyRole(['Admin', 'Super Admin'])
                || ((int) $user->district_id === $districtId && (int) $user->pngo_id === $pngoId));

        abort_unless($allowed, 403);
    }

    private function authorizeCaseScope(CaseSummary $caseSummary): void
    {
        abort_unless(auth()->user()->canAccessDistrictPngo($caseSummary->district_id, $caseSummary->pngo_id), 403);
    }

    private function authorizeVerifiedEdit(CaseSummary $caseSummary): void
    {
        if ((int) $caseSummary->status === CaseSummary::STATUS_DPO_VERIFIED) {
            abort_unless(auth()->user()->hasAnyRole(['Admin', 'Super Admin']), 403, 'Verified case summaries can only be edited by an administrator.');
        }
    }

    private function mainPayload(array $validated): array
    {
        $payload = Arr::only($validated, array_merge(self::MAIN_FIELDS, self::BOOLEAN_FIELDS));
        $payload['missing_document_types'] = $validated['missing_documents_type'] ?? null;

        foreach (self::BOOLEAN_FIELDS as $field) {
            $payload[$field] = ($validated[$field] ?? 'No') === 'Yes';
        }

        return $payload;
    }

    private function replaceParties(CaseSummary $caseSummary, array $validated): void
    {
        $caseSummary->parties()->delete();

        foreach (['complainant', 'defendant'] as $partyType) {
            foreach (array_values($validated[$partyType] ?? []) as $index => $party) {
                $caseSummary->parties()->create(array_merge($party, [
                    'party_type' => $partyType,
                    'sort_order' => $index + 1,
                ]));
            }
        }
    }

    private function appendFollowUp(CaseSummary $caseSummary, array $validated, int $userId): void
    {
        $followUp = Arr::only($validated, [
            'follow_up_date', 'follow_up_action',
            'intervention_to_be_taken_date', 'interventions_to_be_taken',
        ]);

        if (collect($followUp)->contains(fn ($value) => filled($value))) {
            $caseSummary->followUps()->create(array_merge($followUp, ['created_by' => $userId]));
        }
    }

    private function storeAttachments($request, CaseSummary $caseSummary, array &$storedPaths): void
    {
        foreach ($request->file('summary_attachments', []) as $index => $file) {
            $extension = strtolower($file->getClientOriginalExtension());
            $directory = 'uploads/case_summaries/' . $caseSummary->district_id . '/' . now()->format('Y/m');
            $fileName = sprintf(
                '%s_%s_%02d_%s.%s',
                Str::slug($caseSummary->central_id),
                now()->format('YmdHisv'),
                $index + 1,
                Str::lower(Str::random(6)),
                $extension
            );
            $path = $file->storeAs($directory, $fileName, 'public');
            $storedPaths[] = $path;

            $caseSummary->attachments()->create([
                'original_file_name' => $file->getClientOriginalName(),
                'stored_file_name' => $fileName,
                'file_path' => $path,
                'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                'file_size' => $file->getSize(),
                'uploaded_by' => $request->user()->id,
            ]);
        }
    }

    private function deleteStoredFiles(array $paths): void
    {
        if ($paths !== []) {
            Storage::disk('public')->delete($paths);
        }
    }

    private function initialFormData(CaseSummary $caseSummary): array
    {
        $data = $caseSummary->toArray();

        foreach (self::BOOLEAN_FIELDS as $field) {
            $data[$field] = $caseSummary->{$field} ? 'Yes' : 'No';
        }

        $data['missing_documents_type'] = $caseSummary->missing_document_types ?? [];
        $data['act_ids'] = $caseSummary->acts->pluck('id')->all();
        $data['complainant'] = $caseSummary->parties->where('party_type', 'complainant')->values()->all();
        $data['defendant'] = $caseSummary->parties->where('party_type', 'defendant')->values()->all();

        return $data;
    }
}
