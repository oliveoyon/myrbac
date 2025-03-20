<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormalCase extends Model
{
    protected $fillable = [
        'institute','central_id', 'user_id', 'district_id', 'pngo_id', 'status', 'profile_no',
        'full_name', 'nick_name', 'father_name', 'mother_name', 'sex', 'age', 
        'disability', 'nationality', 'nid_passport', 'phone_number', 'address',
        'interview_date', 'interview_time', 'interview_place', 'marital_status', 
        'spouse_name', 'education_level', 'occupation', 'monthly_income', 'family_informed',
        'children_with_prisoner', 'child_sex', 'child_age', 'has_guardian', 'guardian_name', 
        'guardian_phone', 'guardian_address', 'guardian_relation', 'guardian_surety', 
        'has_lawyer', 'lawyer_type', 'lawyer_name', 'lawyer_membership', 'lawyer_phone', 
        'incident_details', 'custody_status', 'charges_details', 'arrest_date', 'case_no', 
        'family_communication_date', 'legal_representation', 'legal_representation_date', 
        'collected_vokalatnama_date', 'collected_case_doc', 'identify_sureties', 
        'witness_communication_date', 'medical_report_date', 'legal_assistance_date', 
        'assistance_under_custody_date', 'referral_service', 'referral_service_date', 
        'resolved_dispute_date', 'appoint_lawyer_date', 'release_status', 'fine_amount', 
        'release_status_date', 'application_mode', 'application_mode_date', 'received_application', 
        'reference_no', 'type_of_service', 'type_of_service_date', 'service_description', 
        'source_of_interview', 'prison_reg_no', 'section_no', 'present_court', 'lockup_no', 
        'entry_date', 'case_transferred', 'current_court', 'case_status', 'co_offenders', 
        'next_court_date', 'facts_of_case', 'imprisonment_condition', 'imprisonment_status', 
        'special_condition', 'surrender_date', 'prison_family_communication', 
        'prison_legal_representation', 'prison_legal_representation_date', 
        'next_court_collection_date', 'collected_case_doc_prison', 
        'identify_sureties_prison_nid', 'identify_sureties_prison_phone', 
        'witness_communication_prison', 'bail_bond_submission', 'court_order_communication', 
        'application_certified_copies', 'appeal_assistance', 'ministerial_communication', 
        'other_legal_assistance', 'other_legal_assistance_date', 'released_on', 
        'released_on_date', 'send_to', 'send_to_date', 'convicted_length', 
        'convicted_sentence_expire', 'result_of_appeal', 'date_of_reliefe', 'file_closure_date'
    ];
}
