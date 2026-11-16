<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreCaseSummaryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $yesNo = ['nullable', Rule::in(['Yes', 'No', 1, 0, true, false])];

        return [
            'summary_type' => ['required', Rule::in(['Paralegal Intervention', 'Judicial Intervention'])],
            'district_id' => ['required', 'integer', 'exists:districts,id'],
            'pngo_id' => ['required', 'integer', 'exists:pngos,id'],
            'summary_date' => ['required', 'date'],
            'court_id' => [
                'required',
                'integer',
                Rule::exists('courts', 'id')->where(fn ($query) => $query->where('district_id', $this->integer('district_id'))),
            ],
            'case_nature' => ['required', Rule::in(['Civil Case', 'Criminal Case'])],
            'case_number' => ['required', 'string', 'max:125'],
            'compoundable_sections' => ['nullable', 'array'],
            'compoundable_sections.*' => ['string', 'max:20', 'distinct'],
            'non_compoundable_section' => ['nullable', 'string', 'max:255'],
            'case_section_comments' => ['nullable', 'string'],
            'act_ids' => ['required', 'array', 'min:1'],
            'act_ids.*' => ['integer', 'distinct', 'exists:acts,id'],
            'case_filing_date' => ['required', 'date'],

            'offence_subject' => ['nullable', 'string'],
            'current_case_status' => ['required', 'string', 'max:125'],
            'current_case_status_other' => ['required_if:current_case_status,Other', 'nullable', 'string', 'max:255'],
            'hearing_count' => ['required', 'integer', 'min:0'],
            'trial_hearing_count' => ['required', 'integer', 'min:0'],
            'last_hearing_date' => ['required', 'date', 'after_or_equal:case_filing_date'],
            'next_hearing_date' => ['required', 'date', 'after_or_equal:last_hearing_date'],
            'accused_status' => ['nullable', 'string', 'max:125'],
            'accused_status_other' => ['required_if:accused_status,Other', 'nullable', 'string', 'max:255'],
            'number_of_witnesses' => ['nullable', 'integer', 'min:0'],
            'witnesses_examined' => ['nullable', 'integer', 'min:0'],

            'complainant' => ['required_if:summary_type,Paralegal Intervention', 'nullable', 'array', 'min:1'],
            'complainant.*.name' => ['required_with:complainant', 'string', 'max:255'],
            'complainant.*.sex' => ['required_with:complainant', Rule::in(['Male', 'Female', 'Transgender Person'])],
            'complainant.*.age' => ['nullable', 'integer', 'between:0,150'],
            'complainant.*.disability' => ['nullable', Rule::in(['Yes', 'No'])],
            'complainant.*.income' => ['nullable', 'string', 'max:50'],
            'complainant.*.phone' => ['nullable', 'string', 'max:30'],
            'complainant.*.representation' => ['nullable', 'string', 'max:125'],
            'complainant.*.representation_other' => ['nullable', 'required_if:complainant.*.representation,Other', 'string', 'max:255'],
            'complainant.*.lawyer_name' => ['nullable', 'string', 'max:255'],
            'complainant.*.lawyer_phone' => ['nullable', 'string', 'max:30'],
            'defendant' => ['required_if:summary_type,Paralegal Intervention', 'nullable', 'array', 'min:1'],
            'defendant.*.name' => ['required_with:defendant', 'string', 'max:255'],
            'defendant.*.sex' => ['required_with:defendant', Rule::in(['Male', 'Female', 'Transgender Person'])],
            'defendant.*.age' => ['nullable', 'integer', 'between:0,150'],
            'defendant.*.disability' => ['nullable', Rule::in(['Yes', 'No'])],
            'defendant.*.income' => ['nullable', 'string', 'max:50'],
            'defendant.*.phone' => ['nullable', 'string', 'max:30'],
            'defendant.*.representation' => ['nullable', 'string', 'max:125'],
            'defendant.*.representation_other' => ['nullable', 'required_if:defendant.*.representation,Other', 'string', 'max:255'],
            'defendant.*.lawyer_name' => ['nullable', 'string', 'max:255'],
            'defendant.*.lawyer_phone' => ['nullable', 'string', 'max:30'],

            'missing_documents' => $yesNo,
            'missing_documents_type' => ['nullable', 'array'],
            'missing_documents_type.*' => ['string', 'max:125'],
            'missing_documents_other' => ['nullable', 'string'],
            'missing_documents_remarks' => ['nullable', 'string'],
            'absent_witnesses' => $yesNo,
            'absent_witnesses_type' => ['nullable', 'array'],
            'absent_witnesses_type.*' => ['string', 'max:125'],
            'government_witness_type' => ['nullable', 'string', 'max:125'],
            'government_witness_other' => ['nullable', 'string', 'max:255'],
            'absent_witness_information' => ['nullable', 'string'],
            'absent_witnesses_remarks' => ['nullable', 'string'],
            'procedural_delays' => $yesNo,
            'procedural_delays_type' => ['nullable', 'string', 'max:125'],
            'procedural_delays_other' => ['nullable', 'string'],
            'procedural_delays_remarks' => ['nullable', 'string'],
            'adjournment_by_parties' => $yesNo,
            'adjournment_by_parties_remarks' => ['nullable', 'string'],
            'adjournment_by_court' => $yesNo,
            'adjournment_by_court_remarks' => ['nullable', 'string'],
            'lack_of_lawyer' => $yesNo,
            'lack_of_lawyer_remarks' => ['nullable', 'string'],
            'lack_of_prosecutor' => $yesNo,
            'lack_of_prosecutor_remarks' => ['nullable', 'string'],
            'settled_outside_court' => $yesNo,
            'settled_outside_court_remarks' => ['nullable', 'string'],
            'death_of_accused' => $yesNo,
            'death_of_accused_remarks' => ['nullable', 'string'],
            'death_of_witness' => $yesNo,
            'death_of_witness_remarks' => ['nullable', 'string'],
            'pending_for_argument' => $yesNo,
            'pending_for_argument_remarks' => ['nullable', 'string'],
            'pending_for_judgment' => $yesNo,
            'pending_for_judgment_remarks' => ['nullable', 'string'],
            'dismissed_but_pending' => $yesNo,
            'dismissed_but_pending_remarks' => ['nullable', 'string'],
            'administrative_issues' => $yesNo,
            'administrative_issues_specify' => ['nullable', 'string'],
            'administrative_issues_remarks' => ['nullable', 'string'],

            'priority_hearing' => $yesNo,
            'mediation_settlement' => $yesNo,
            'diversion_to_probation' => $yesNo,
            'withdrawal' => $yesNo,
            'administrative_followup' => $yesNo,
            'case_scheduling' => $yesNo,
            'other_solution' => $yesNo,
            'other_solution_specify' => ['required_if:other_solution,Yes', 'nullable', 'string'],
            'solution_remarks' => ['nullable', 'string'],

            'collect_case_documents_date' => ['nullable', 'date'],
            'communicate_witness_date' => ['nullable', 'date'],
            'communicate_parties_date' => ['nullable', 'date'],
            'refer_mediation_date' => ['nullable', 'date'],
            'refer_mediation_destination' => ['required_with:refer_mediation_date', 'nullable', 'string', 'max:125'],
            'refer_mediation_destination_details' => ['nullable', 'string'],
            'present_judge_date' => ['nullable', 'date'],
            'present_judicial_conference_date' => ['nullable', 'date'],
            'other_paralegal_intervention_details' => ['nullable', 'string'],
            'other_paralegal_intervention_date' => ['nullable', 'date'],

            'judicial_mediation_destination' => ['nullable', 'string', 'max:125'],
            'judicial_mediation_details' => ['nullable', 'string'],
            'judicial_mediation_date' => ['required_with:judicial_mediation_destination', 'nullable', 'date'],
            'judiciary_priority_hearing_date' => ['nullable', 'date'],
            'judiciary_diversion_to_probation_date' => ['nullable', 'date'],
            'judiciary_withdrawal_date' => ['nullable', 'date'],
            'judiciary_administrative_followup_date' => ['nullable', 'date'],
            'judiciary_case_scheduling_date' => ['nullable', 'date'],
            'judiciary_other_action_details' => ['nullable', 'string'],
            'judiciary_other_action_date' => ['nullable', 'date'],

            'disposed_through_mediation' => $yesNo,
            'mediation_disposal_date' => ['required_if:disposed_through_mediation,Yes', 'nullable', 'date'],
            'disposed_by_judgment' => $yesNo,
            'judgment_details' => ['required_if:disposed_by_judgment,Yes', 'nullable', 'string'],
            'judgment_written_delivered' => $yesNo,
            'decree_pronounced' => $yesNo,
            'judgment_disposal_date' => ['required_if:disposed_by_judgment,Yes', 'nullable', 'date'],
            'case_dismissed' => $yesNo,
            'case_dismissed_date' => ['required_if:case_dismissed,Yes', 'nullable', 'date'],
            'acquitted' => $yesNo,
            'acquittal_reason' => ['required_if:acquitted,Yes', 'nullable', 'string', 'max:125'],
            'acquittal_date' => ['required_if:acquitted,Yes', 'nullable', 'date'],

            'follow_up_date' => ['nullable', 'date'],
            'follow_up_action' => ['nullable', 'required_with:follow_up_date', 'string'],
            'intervention_to_be_taken_date' => ['nullable', 'date'],
            'interventions_to_be_taken' => ['nullable', 'required_with:intervention_to_be_taken_date', 'string'],
            'summary_attachments' => ['nullable', 'array', 'max:10'],
            'summary_attachments.*' => ['file', 'max:2048', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if (blank($this->input('non_compoundable_section')) && empty($this->input('compoundable_sections', []))) {
                $validator->errors()->add('compoundable_sections', 'Please provide at least one case section.');
            }

            $outcomes = collect(['disposed_through_mediation', 'disposed_by_judgment', 'case_dismissed', 'acquitted'])
                ->filter(fn (string $field) => $this->input($field) === 'Yes');

            if ($outcomes->count() > 1) {
                $validator->errors()->add('disposed_through_mediation', 'Only one case disposal outcome may be selected.');
            }
        }];
    }

    public function messages(): array
    {
        return [
            'pngo_id.required' => 'Please select the PNGO responsible for this case summary.',
            'act_ids.required' => 'Please select at least one Act.',
            'act_ids.min' => 'Please select at least one Act.',
            'summary_attachments.max' => 'You may upload a maximum of 10 attachments.',
            'summary_attachments.*.max' => 'Each attachment must not exceed 2 MB.',
            'summary_attachments.*.mimes' => 'Attachments must be PDF, JPG, JPEG, PNG, DOC, or DOCX files.',
        ];
    }
}
