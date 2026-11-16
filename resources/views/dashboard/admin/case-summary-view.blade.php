@extends('dashboard.layouts.admin-layout')

@section('title', 'Case Summary Review')

@push('styles')
<style>
    .summary-review { display: grid; gap: 14px; min-width: 0; color: #344054; }
    .review-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding: 18px 20px; border: 1px solid #dfe5e2; border-radius: 8px; background: #fff; }
    .review-head h1 { margin: 0 0 5px; color: #1d2939; font-size: 22px; font-weight: 800; }
    .review-head p { margin: 0; color: #667085; font-size: 13px; }
    .review-head-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 7px; }
    .review-status { display: inline-flex; margin-top: 8px; padding: 4px 9px; border-radius: 999px; font-size: 11px; font-weight: 800; }
    .review-status.submitted { color: #8a4b08; background: #fff3dc; }
    .review-status.verified { color: #17643a; background: #e9f6ee; }
    .review-meta { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); border: 1px solid #dfe5e2; border-radius: 8px; background: #fff; overflow: hidden; }
    .review-meta-item { padding: 12px 14px; border-right: 1px solid #eaecf0; }
    .review-meta-item:last-child { border-right: 0; }
    .review-meta-item span { display: block; margin-bottom: 3px; color: #667085; font-size: 11px; font-weight: 700; }
    .review-meta-item strong { color: #1d2939; font-size: 13px; overflow-wrap: anywhere; }
    .review-section { border: 1px solid #dfe5e2; border-radius: 8px; background: #fff; overflow: hidden; }
    .review-section-head { padding: 11px 14px; border-bottom: 1px solid #e4e9e6; background: #f7f9f8; }
    .review-section-head h2 { margin: 0; color: #253b33; font-size: 15px; font-weight: 800; }
    .review-section-body { padding: 14px; }
    .review-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    .review-field { min-width: 0; }
    .review-field.wide { grid-column: span 2; }
    .review-field.full { grid-column: 1 / -1; }
    .review-field span { display: block; margin-bottom: 3px; color: #667085; font-size: 11px; font-weight: 700; }
    .review-field strong, .review-field p { margin: 0; color: #1d2939; font-size: 13px; font-weight: 600; overflow-wrap: anywhere; white-space: pre-line; }
    .review-table { margin: 0; font-size: 12px; }
    .review-table th { color: #344054; background: #f7f9f8; white-space: nowrap; }
    .review-table td { vertical-align: middle; }
    .review-tags { display: flex; flex-wrap: wrap; gap: 6px; }
    .review-tag { display: inline-flex; padding: 4px 8px; border: 1px solid #d5e3dc; border-radius: 5px; color: #315c4b; background: #f2f8f5; font-size: 11px; font-weight: 700; }
    .review-empty { padding: 12px; color: #667085; text-align: center; border: 1px dashed #d0d5dd; border-radius: 6px; background: #fcfcfd; }
    .attachment-list { display: grid; gap: 7px; }
    .attachment-row { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 10px; border: 1px solid #e4e7ec; border-radius: 6px; }
    .attachment-name { min-width: 0; overflow-wrap: anywhere; font-size: 12px; font-weight: 700; }
    .attachment-actions { display: flex; flex: 0 0 auto; gap: 5px; }
    .follow-up-entry { padding: 12px 0; border-bottom: 1px solid #eaecf0; }
    .follow-up-entry:first-child { padding-top: 0; }
    .follow-up-entry:last-child { padding-bottom: 0; border-bottom: 0; }
    .verify-panel { padding: 14px; border: 1px solid #b8d6c5; border-radius: 8px; background: #f2f8f5; }
    @media (max-width: 992px) { .review-meta { grid-template-columns: repeat(3, minmax(0, 1fr)); } .review-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 767px) {
        .review-head { flex-direction: column; padding: 15px; }
        .review-head-actions { width: 100%; justify-content: flex-start; }
        .review-meta { grid-template-columns: 1fr 1fr; }
        .review-meta-item { border-bottom: 1px solid #eaecf0; }
        .review-grid { grid-template-columns: 1fr; }
        .review-field.wide { grid-column: auto; }
        .attachment-row { align-items: flex-start; flex-direction: column; }
        .attachment-actions { width: 100%; }
    }
</style>
@endpush

@section('content')
@php
    $date = fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d M, Y') : 'N/A';
    $display = fn ($value) => filled($value) ? $value : 'N/A';
    $barriers = collect([
        'Missing Documents' => $caseSummary->missing_documents,
        'Absence of Witness' => $caseSummary->absent_witnesses,
        'Procedural Delays' => $caseSummary->procedural_delays,
        'Adjournment by Parties' => $caseSummary->adjournment_by_parties,
        'Adjournment by Court' => $caseSummary->adjournment_by_court,
        'Lack of Lawyer' => $caseSummary->lack_of_lawyer,
        'Lack of Prosecutor' => $caseSummary->lack_of_prosecutor,
        'Settled Outside Court' => $caseSummary->settled_outside_court,
        'Death of Accused' => $caseSummary->death_of_accused,
        'Death of Witness' => $caseSummary->death_of_witness,
        'Pending for Argument' => $caseSummary->pending_for_argument,
        'Pending for Judgment' => $caseSummary->pending_for_judgment,
        'Dismissed but Pending' => $caseSummary->dismissed_but_pending,
        'Other Administrative Issues' => $caseSummary->administrative_issues,
    ])->filter();
    $solutions = collect([
        'Priority Hearing' => $caseSummary->priority_hearing,
        'Mediation / Settlement' => $caseSummary->mediation_settlement,
        'Diversion to Probation' => $caseSummary->diversion_to_probation,
        'Withdrawal' => $caseSummary->withdrawal,
        'Administrative Follow-up' => $caseSummary->administrative_followup,
        'Case Scheduling' => $caseSummary->case_scheduling,
        'Other Solution' => $caseSummary->other_solution,
    ])->filter();
    $interventions = collect([
        ['Collect Case Documents', $caseSummary->collect_case_documents_date],
        ['Communicate with Witness', $caseSummary->communicate_witness_date],
        ['Communicate with Parties', $caseSummary->communicate_parties_date],
        ['Support to Refer to Mediation', $caseSummary->refer_mediation_date],
        ['Present to Judge', $caseSummary->present_judge_date],
        ['Present to Judicial Conference', $caseSummary->present_judicial_conference_date],
        ['Other Paralegal Intervention', $caseSummary->other_paralegal_intervention_date],
        ['Judicial Mediation Referral', $caseSummary->judicial_mediation_date],
        ['Judiciary Priority Hearing', $caseSummary->judiciary_priority_hearing_date],
        ['Judiciary Diversion to Probation', $caseSummary->judiciary_diversion_to_probation_date],
        ['Judiciary Withdrawal', $caseSummary->judiciary_withdrawal_date],
        ['Judiciary Administrative Follow-up', $caseSummary->judiciary_administrative_followup_date],
        ['Judiciary Case Scheduling', $caseSummary->judiciary_case_scheduling_date],
        ['Other Judiciary Action', $caseSummary->judiciary_other_action_date],
    ])->filter(fn ($item) => filled($item[1]));
@endphp

<section class="summary-review">
    <div class="review-head">
        <div>
            <h1>{{ $caseSummary->central_id }}</h1>
            <p>{{ $caseSummary->case_number }} · {{ $caseSummary->summary_type }}</p>
            <span class="review-status {{ $caseSummary->status === 2 ? 'verified' : 'submitted' }}">{{ $caseSummary->status === 2 ? 'DPO Verified' : 'Submitted' }}</span>
        </div>
        <div class="review-head-actions">
            <a href="{{ route('case-summaries.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
            @can('Edit Case Summary')
                @if ($caseSummary->status === 1 || auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                    <a href="{{ route('case-summaries.edit', $caseSummary) }}" class="btn btn-outline-success btn-sm"><i class="fas fa-pen"></i> Edit</a>
                @endif
            @endcan
        </div>
    </div>

    @if (session('success'))<div class="alert alert-success mb-0">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger mb-0">{{ session('error') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger mb-0"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

    <div class="review-meta">
        <div class="review-meta-item"><span>District</span><strong>{{ $caseSummary->district?->name ?? 'N/A' }}</strong></div>
        <div class="review-meta-item"><span>PNGO</span><strong>{{ $caseSummary->pngo?->name ?? 'N/A' }}</strong></div>
        <div class="review-meta-item"><span>Court</span><strong>{{ $caseSummary->court?->name ?? 'N/A' }}</strong></div>
        <div class="review-meta-item"><span>Summary Date</span><strong>{{ $date($caseSummary->summary_date) }}</strong></div>
        <div class="review-meta-item"><span>Created By</span><strong>{{ $caseSummary->creator?->full_name ?: ($caseSummary->creator?->name ?? 'N/A') }}</strong></div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Case Identification and Profile</h2></div>
        <div class="review-section-body review-grid">
            <div class="review-field"><span>Nature of Case</span><strong>{{ $display($caseSummary->case_nature) }}</strong></div>
            <div class="review-field"><span>Case Number</span><strong>{{ $display($caseSummary->case_number) }}</strong></div>
            <div class="review-field"><span>Date of Filing</span><strong>{{ $date($caseSummary->case_filing_date) }}</strong></div>
            <div class="review-field"><span>Current Status</span><strong>{{ $display($caseSummary->current_case_status_other ?: $caseSummary->current_case_status) }}</strong></div>
            <div class="review-field wide"><span>Compoundable Sections</span><strong>{{ collect($caseSummary->compoundable_sections)->implode(', ') ?: 'N/A' }}</strong></div>
            <div class="review-field wide"><span>Non-Compoundable Section</span><strong>{{ $display($caseSummary->non_compoundable_section) }}</strong></div>
            <div class="review-field full"><span>Acts</span><div class="review-tags">@foreach($caseSummary->acts as $act)<span class="review-tag">{{ $act->title }}</span>@endforeach</div></div>
            <div class="review-field full"><span>Description of Offence</span><p>{{ $display($caseSummary->offence_subject) }}</p></div>
            <div class="review-field"><span>Pre-trial Hearings</span><strong>{{ $caseSummary->hearing_count }}</strong></div>
            <div class="review-field"><span>Trial Hearings</span><strong>{{ $caseSummary->trial_hearing_count }}</strong></div>
            <div class="review-field"><span>Last Hearing</span><strong>{{ $date($caseSummary->last_hearing_date) }}</strong></div>
            <div class="review-field"><span>Next Hearing</span><strong>{{ $date($caseSummary->next_hearing_date) }}</strong></div>
            <div class="review-field"><span>Accused Status</span><strong>{{ $display($caseSummary->accused_status_other ?: $caseSummary->accused_status) }}</strong></div>
            <div class="review-field"><span>Number of Witnesses</span><strong>{{ $display($caseSummary->number_of_witnesses) }}</strong></div>
            <div class="review-field"><span>Witnesses Examined</span><strong>{{ $display($caseSummary->witnesses_examined) }}</strong></div>
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Parties</h2></div>
        <div class="review-section-body table-responsive">
            @if ($caseSummary->parties->isEmpty())
                <div class="review-empty">No party information is applicable to this summary type.</div>
            @else
                <table class="table table-bordered review-table"><thead><tr><th>Type</th><th>Name</th><th>Sex</th><th>Age</th><th>Disability</th><th>Income</th><th>Phone</th><th>Representation</th></tr></thead><tbody>
                    @foreach ($caseSummary->parties as $party)
                        <tr><td>{{ ucfirst($party->party_type) }}</td><td>{{ $party->name }}</td><td>{{ $party->sex }}</td><td>{{ $display($party->age) }}</td><td>{{ $display($party->disability) }}</td><td>{{ $display($party->income) }}</td><td>{{ $display($party->phone) }}</td><td>{{ $display($party->representation_other ?: $party->representation) }}</td></tr>
                    @endforeach
                </tbody></table>
            @endif
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Barriers and Proposed Solutions</h2></div>
        <div class="review-section-body review-grid">
            <div class="review-field wide"><span>Identified Barriers</span><div class="review-tags">@forelse($barriers->keys() as $label)<span class="review-tag">{{ $label }}</span>@empty<span>N/A</span>@endforelse</div></div>
            <div class="review-field wide"><span>Proposed Solutions</span><div class="review-tags">@forelse($solutions->keys() as $label)<span class="review-tag">{{ $label }}</span>@empty<span>N/A</span>@endforelse</div></div>
            <div class="review-field full"><span>Remarks</span><p>{{ $display($caseSummary->solution_remarks) }}</p></div>
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Interventions and Disposal</h2></div>
        <div class="review-section-body">
            <div class="table-responsive mb-3"><table class="table table-bordered review-table"><thead><tr><th>Intervention</th><th>Date</th></tr></thead><tbody>
                @forelse($interventions as $item)<tr><td>{{ $item[0] }}</td><td>{{ $date($item[1]) }}</td></tr>@empty<tr><td colspan="2" class="text-center text-muted">No intervention dates recorded.</td></tr>@endforelse
            </tbody></table></div>
            <div class="review-grid">
                <div class="review-field"><span>Disposed through Mediation</span><strong>{{ $caseSummary->disposed_through_mediation ? $date($caseSummary->mediation_disposal_date) : 'No' }}</strong></div>
                <div class="review-field"><span>Disposed by Judgment</span><strong>{{ $caseSummary->disposed_by_judgment ? $date($caseSummary->judgment_disposal_date) : 'No' }}</strong></div>
                <div class="review-field"><span>Case Dismissed</span><strong>{{ $caseSummary->case_dismissed ? $date($caseSummary->case_dismissed_date) : 'No' }}</strong></div>
                <div class="review-field"><span>Acquitted</span><strong>{{ $caseSummary->acquitted ? $date($caseSummary->acquittal_date) : 'No' }}</strong></div>
                <div class="review-field full"><span>Judgment Details</span><p>{{ $display($caseSummary->judgment_details) }}</p></div>
            </div>
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Follow-up History</h2></div>
        <div class="review-section-body">
            @forelse ($caseSummary->followUps as $followUp)
                <div class="follow-up-entry review-grid">
                    <div class="review-field"><span>Progress Date</span><strong>{{ $date($followUp->follow_up_date) }}</strong></div>
                    <div class="review-field wide"><span>Progress / Action Taken</span><p>{{ $display($followUp->follow_up_action) }}</p></div>
                    <div class="review-field"><span>Next Action Date</span><strong>{{ $date($followUp->intervention_to_be_taken_date) }}</strong></div>
                    <div class="review-field wide"><span>Intervention to be Taken</span><p>{{ $display($followUp->interventions_to_be_taken) }}</p></div>
                    <div class="review-field"><span>Recorded By</span><strong>{{ $followUp->creator?->full_name ?: ($followUp->creator?->name ?? 'N/A') }}</strong></div>
                </div>
            @empty
                <div class="review-empty">No follow-up records have been added.</div>
            @endforelse

            @can('Add Case Summary Follow-up')
                <form method="POST" action="{{ route('case-summaries.follow-ups.store', $caseSummary) }}" class="mt-3 pt-3 border-top">
                    @csrf
                    <div class="review-grid">
                        <div><label class="form-label" for="follow_up_date">Progress Date</label><input type="date" id="follow_up_date" name="follow_up_date" class="form-control"></div>
                        <div class="review-field wide"><label class="form-label" for="follow_up_action">Progress / Action Taken</label><textarea id="follow_up_action" name="follow_up_action" class="form-control" rows="2"></textarea></div>
                        <div><label class="form-label" for="intervention_to_be_taken_date">Next Action Date</label><input type="date" id="intervention_to_be_taken_date" name="intervention_to_be_taken_date" class="form-control"></div>
                        <div class="review-field wide"><label class="form-label" for="interventions_to_be_taken">Intervention to be Taken</label><textarea id="interventions_to_be_taken" name="interventions_to_be_taken" class="form-control" rows="2"></textarea></div>
                        <div class="review-field full"><button class="btn btn-success btn-sm" type="submit"><i class="fas fa-plus"></i> Add Follow-up</button></div>
                    </div>
                </form>
            @endcan
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Evidence Attachments</h2></div>
        <div class="review-section-body attachment-list">
            @forelse ($caseSummary->attachments as $attachment)
                <div class="attachment-row">
                    <div class="attachment-name"><i class="fas fa-paperclip"></i> {{ $attachment->original_file_name }} <small class="text-muted">({{ number_format($attachment->file_size / 1024, 1) }} KB)</small></div>
                    <div class="attachment-actions">
                        @can('Download Case Summary Attachment')
                            <a target="_blank" href="{{ route('case-summaries.attachments.view', $attachment) }}" class="btn btn-outline-secondary btn-sm" title="View"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('case-summaries.attachments.download', $attachment) }}" class="btn btn-outline-success btn-sm" title="Download"><i class="fas fa-download"></i></a>
                        @endcan
                        @can('Delete Case Summary Attachment')
                            <form method="POST" action="{{ route('case-summaries.attachments.delete', $attachment) }}" class="attachment-delete-form">@csrf @method('DELETE')<button class="btn btn-outline-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button></form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="review-empty">No evidence attachments were uploaded.</div>
            @endforelse
        </div>
    </div>

    <div class="review-section">
        <div class="review-section-head"><h2>Verification History</h2></div>
        <div class="review-section-body review-grid">
            <div class="review-field"><span>Submitted By</span><strong>{{ $caseSummary->creator?->full_name ?: ($caseSummary->creator?->name ?? 'N/A') }}</strong></div>
            <div class="review-field"><span>Submitted At</span><strong>{{ $caseSummary->submitted_at?->format('d M, Y h:i A') ?? 'N/A' }}</strong></div>
            <div class="review-field"><span>DPO Verified By</span><strong>{{ $caseSummary->dpoVerifier?->full_name ?: ($caseSummary->dpoVerifier?->name ?? 'Pending') }}</strong></div>
            <div class="review-field"><span>DPO Verified At</span><strong>{{ $caseSummary->dpo_verified_at?->format('d M, Y h:i A') ?? 'Pending' }}</strong></div>
            <div class="review-field full"><span>Verification Remarks</span><p>{{ $display($caseSummary->dpo_verification_remarks) }}</p></div>
        </div>
    </div>

    @can('Verify Case Summary by DPO')
        @if ($caseSummary->status === 1)
            <form method="POST" action="{{ route('case-summaries.verify', $caseSummary) }}" class="verify-panel" id="dpoVerifyForm">
                @csrf @method('PATCH')
                <label class="form-label fw-bold" for="dpo_verification_remarks">DPO Verification Remarks, if any</label>
                <textarea id="dpo_verification_remarks" name="dpo_verification_remarks" class="form-control mb-3" rows="3" maxlength="2000"></textarea>
                <button class="btn btn-success" type="submit"><i class="fas fa-check"></i> Verify Case Summary</button>
            </form>
        @endif
    @endcan
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.attachment-delete-form').forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!window.Swal) return;
            event.preventDefault();
            Swal.fire({
                icon: 'warning', title: 'Delete attachment?',
                text: 'This evidence file will be permanently removed.',
                showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#b42318'
            }).then(function(result) { if (result.isConfirmed) form.submit(); });
        });
    });
    const verifyForm = document.getElementById('dpoVerifyForm');
    verifyForm?.addEventListener('submit', function(event) {
        if (!window.Swal) return;
        event.preventDefault();
        Swal.fire({
            icon: 'question', title: 'Verify this case summary?',
            text: 'The verification will be recorded with your name and time.',
            showCancelButton: true, confirmButtonText: 'Verify', confirmButtonColor: '#198754'
        }).then(function(result) { if (result.isConfirmed) verifyForm.submit(); });
    });
});
</script>
@endpush
