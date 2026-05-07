<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormalCase extends Model
{
    public const STATUS_SUBMITTED = 1;
    public const STATUS_DPO_VERIFIED = 2;
    public const STATUS_MNEO_VERIFIED = 3;

    protected $fillable = [
        'institute','central_id', 'user_id', 'district_id', 'pngo_id', 'status', 'profile_no',
        'full_name', 'nick_name', 'father_name', 'mother_name', 'sex', 'age', 
        'disability', 'nationality', 'nid_passport', 'phone_number', 'address',
        'interview_date', 'interview_time', 'interview_place', 'marital_status', 
        'spouse_name', 'education_level', 'occupation', 'monthly_income', 'family_informed',
        'children_with_prisoner', 'child_sex', 'child_age', 'child_2_sex', 'child_2_age', 'has_guardian', 'guardian_name', 
        'guardian_phone', 'guardian_address', 'guardian_relation', 'guardian_relation_details', 'guardian_surety', 
        'has_lawyer', 'lawyer_type', 'lawyer_type_details', 'lawyer_name', 'lawyer_membership', 'lawyer_phone', 
        'incident_details', 'custody_status', 'charges_details', 'arrest_date', 'case_no', 
        'family_communication_date', 'legal_representation', 'legal_representation_details', 'legal_representation_date', 
        'collected_vokalatnama_date', 'collected_case_doc', 'identify_sureties', 'identify_sureties_date', 
        'witness_communication_date', 'medical_report_date', 'legal_assistance_date', 
        'assistance_under_custody_date', 'referral_service', 'referral_service_details', 'referral_service_date', 
        'case_resolved_date', 'resolved_dispute_date', 'appoint_lawyer_date', 'release_status', 'fine_amount', 
        'release_status_date', 'other_result_details', 'other_result_date', 'application_mode', 'application_mode_date', 'received_application', 
        'reference_no', 'type_of_service', 'type_of_service_date', 'service_description', 
        'source_of_interview', 'source_of_interview_details', 'prison_reg_no', 'section_no', 'present_court', 'lockup_no', 
        'entry_date', 'case_transferred', 'current_court', 'case_status', 'co_offenders', 
        'next_court_date', 'facts_of_case', 'imprisonment_condition', 'imprisonment_status', 
        'special_condition', 'special_condition_details', 'surrender_date', 'prison_family_communication', 
        'prison_legal_representation', 'prison_legal_representation_details', 'prison_legal_representation_date', 
        'next_court_collection_date', 'collected_case_doc_prison', 
        'identify_sureties_prison_nid', 'identify_sureties_prison_phone', 'identify_sureties_prison_date',
        'witness_communication_prison', 'bail_bond_submission', 'court_order_communication', 
        'application_certified_copies', 'appeal_assistance', 'ministerial_communication', 'ministerial_communication_details',
        'other_legal_assistance', 'other_legal_assistance_details', 'other_legal_assistance_date', 'released_on', 
        'released_on_date', 'send_to', 'send_to_details', 'send_to_date', 'convicted_length', 'convicted_length_details',
        'convicted_sentence_expire', 'convicted_sentence_expire_details', 'result_of_appeal',
        'result_of_appeal_date', 'prison_case_resolved_date', 'date_of_reliefe', 'file_closure_date'
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function pngo()
    {
        return $this->belongsTo(Pngo::class, 'pngo_id');
    }

    public function fileUploads()
    {
        return $this->hasMany(FileUpload::class, 'case_id');
    }

    public function getTypeOfServiceListAttribute(): array
    {
        if (blank($this->type_of_service)) {
            return [];
        }

        $decoded = json_decode($this->type_of_service, true);

        if (is_array($decoded)) {
            return array_values(array_filter($decoded, fn ($value) => filled($value)));
        }

        return [$this->type_of_service];
    }

}
