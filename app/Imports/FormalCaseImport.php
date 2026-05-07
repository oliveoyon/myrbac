<?php

namespace App\Imports;

use App\Models\FormalCase;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Auth;

class FormalCaseImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {
        return new FormalCase([
            'institute' => $row['institute'] ?? null,
            'central_id' => $row['central_id'] ?? null,
            'user_id' => auth()->id(),
            'district_id' => Auth::user()->district_id,
            'pngo_id' => Auth::user()->pngo_id,
            'status' => $row['status'] ?? null,
            'profile_no' => $row['profile_no'] ?? null,
            'full_name' => $row['full_name'] ?? null,
            'nick_name' => $row['nick_name'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'sex' => $row['sex'] ?? null,
            'age' => $row['age'] ?? null,
            'disability' => $row['disability'] ?? 'no',
            'nationality' => $row['nationality'] ?? null,
            'nid_passport' => $row['nid_passport'] ?? null,
            'phone_number' => $row['phone_number'] ?? null,
            'address' => $row['address'] ?? null,
            'interview_date' => $row['interview_date'] ?? null,
            'interview_time' => $row['interview_time'] ?? null,
            'interview_place' => $row['interview_place'] ?? null,
            'marital_status' => $row['marital_status'] ?? null,
            'spouse_name' => $row['spouse_name'] ?? null,
            'education_level' => $row['education_level'] ?? null,
            'occupation' => $row['occupation'] ?? null,
            'monthly_income' => $row['monthly_income'] ?? null,
            'family_informed' => $row['family_informed'] ?? null,
            'children_with_prisoner' => $row['children_with_prisoner'] ?? null,
            'child_sex' => $row['child_sex'] ?? null,
            'child_age' => $row['child_age'] ?? null,
            'child_2_sex' => $row['child_2_sex'] ?? null,
            'child_2_age' => $row['child_2_age'] ?? null,
            'has_guardian' => $row['has_guardian'] ?? null,
            'guardian_name' => $row['guardian_name'] ?? null,
            'guardian_phone' => $row['guardian_phone'] ?? null,
            'guardian_address' => $row['guardian_address'] ?? null,
            'guardian_relation' => $row['guardian_relation'] ?? null,
            'guardian_relation_details' => $row['guardian_relation_details'] ?? null,
            'guardian_surety' => $row['guardian_surety'] ?? null,
            'has_lawyer' => $row['has_lawyer'] ?? null,
            'lawyer_type' => $row['lawyer_type'] ?? null,
            'lawyer_type_details' => $row['lawyer_type_details'] ?? null,
            'lawyer_name' => $row['lawyer_name'] ?? null,
            'lawyer_membership' => $row['lawyer_membership'] ?? null,
            'lawyer_phone' => $row['lawyer_phone'] ?? null,
            'incident_details' => $row['incident_details'] ?? null,
            'custody_status' => $row['custody_status'] ?? null,
            'charges_details' => $row['charges_details'] ?? null,
            'arrest_date' => $row['arrest_date'] ?? null,
            'case_no' => $row['case_no'] ?? null,
            'family_communication_date' => $row['family_communication_date'] ?? null,
            'legal_representation' => $row['legal_representation'] ?? null,
            'legal_representation_details' => $row['legal_representation_details'] ?? null,
            'legal_representation_date' => $row['legal_representation_date'] ?? null,
            'collected_vokalatnama_date' => $row['collected_vokalatnama_date'] ?? null,
            'collected_case_doc' => $row['collected_case_doc'] ?? null,
            'identify_sureties' => $row['identify_sureties'] ?? null,
            'identify_sureties_date' => $row['identify_sureties_date'] ?? null,
            'witness_communication_date' => $row['witness_communication_date'] ?? null,
            'medical_report_date' => $row['medical_report_date'] ?? null,
            'legal_assistance_date' => $row['legal_assistance_date'] ?? null,
            'assistance_under_custody_date' => $row['assistance_under_custody_date'] ?? null,
            'referral_service' => $row['referral_service'] ?? null,
            'referral_service_details' => $row['referral_service_details'] ?? null,
            'referral_service_date' => $row['referral_service_date'] ?? null,
            'case_resolved_date' => $row['case_resolved_date'] ?? null,
            'resolved_dispute_date' => $row['resolved_dispute_date'] ?? null,
            'appoint_lawyer_date' => $row['appoint_lawyer_date'] ?? null,
            'release_status' => $row['release_status'] ?? null,
            'fine_amount' => $row['fine_amount'] ?? null,
            'release_status_date' => $row['release_status_date'] ?? null,
            'other_result_details' => $row['other_result_details'] ?? null,
            'other_result_date' => $row['other_result_date'] ?? null,
            'application_mode' => $row['application_mode'] ?? null,
            'application_mode_date' => $row['application_mode_date'] ?? null,
            'received_application' => $row['received_application'] ?? null,
            'reference_no' => $row['reference_no'] ?? null,
            'type_of_service' => $this->prepareMultiSelectValue($row['type_of_service'] ?? null),
            'type_of_service_date' => $row['type_of_service_date'] ?? null,
            'source_of_interview' => $row['source_of_interview'] ?? null,
            'source_of_interview_details' => $row['source_of_interview_details'] ?? null,
            'prison_reg_no' => $row['prison_reg_no'] ?? null,
            'entry_date' => $row['entry_date'] ?? null,
            'case_transferred' => $row['case_transferred'] ?? null,
            'current_court' => $row['current_court'] ?? null,
            'case_status' => $row['case_status'] ?? null,
            'next_court_date' => $row['next_court_date'] ?? null,
            'facts_of_case' => $row['facts_of_case'] ?? null,
            'imprisonment_condition' => $row['imprisonment_condition'] ?? null,
            'special_condition' => $row['special_condition'] ?? null,
            'special_condition_details' => $row['special_condition_details'] ?? null,
            'surrender_date' => $row['surrender_date'] ?? null,
            'prison_legal_representation_details' => $row['prison_legal_representation_details'] ?? null,
            'released_on' => $row['released_on'] ?? null,
            'identify_sureties_prison_date' => $row['identify_sureties_prison_date'] ?? null,
            'ministerial_communication_details' => $row['ministerial_communication_details'] ?? null,
            'other_legal_assistance_details' => $row['other_legal_assistance_details'] ?? null,
            'result_of_appeal' => $row['result_of_appeal'] ?? null,
            'convicted_length_details' => $row['convicted_length_details'] ?? null,
            'convicted_sentence_expire_details' => $row['convicted_sentence_expire_details'] ?? null,
            'result_of_appeal_date' => $row['result_of_appeal_date'] ?? null,
            'prison_case_resolved_date' => $row['prison_case_resolved_date'] ?? null,
            'send_to_details' => $row['send_to_details'] ?? null,
            'date_of_reliefe' => $row['date_of_reliefe'] ?? null,
            'file_closure_date' => $row['file_closure_date'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'age' => 'nullable|integer',
            'child_age' => 'nullable|integer',
            'child_2_age' => 'nullable|integer',
            'monthly_income' => 'nullable|numeric',
            'fine_amount' => 'nullable|numeric',
            'case_no' => 'nullable|string|max:255',
        ];
    }

    private function prepareMultiSelectValue($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                $value = $decoded;
            } else {
                $value = array_map('trim', explode(',', $value));
            }
        }

        $values = array_values(array_filter((array) $value, fn ($item) => filled($item)));

        return empty($values) ? null : json_encode($values);
    }
}
