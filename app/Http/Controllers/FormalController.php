<?php

namespace App\Http\Controllers;

use App\Models\FormalCase;
use App\Models\FollowUpIntervention;
use App\Models\FileUpload; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Imports\FormalCaseImport;
use App\Exports\FormalCaseImportTemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Services\CommonService;
use Illuminate\Support\Facades\DB;
use App\Services\LogService;
use Illuminate\Support\Str;

class FormalController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.formal1');
    }

    public function courtPolicePrison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'family_informed' => 'required|string|max:255',
            'child_age' => 'nullable|integer|min:0|max:150',
            'child_2_age' => 'nullable|integer|min:0|max:150',
            'guardian_relation_details' => 'nullable|string|max:255',
            'lawyer_type_details' => 'nullable|string|max:255',
            'legal_representation_details' => 'nullable|string|max:255',
            'referral_service_details' => 'nullable|string|max:255',
            'source_of_interview_details' => 'nullable|string|max:255',
            'special_condition_details' => 'nullable|string|max:255',
            'prison_legal_representation_details' => 'nullable|string|max:255',
            'other_legal_assistance_details' => 'nullable|string|max:255',
            'send_to_details' => 'nullable|string|max:255',
            'other_result_details' => 'nullable|string|max:255',
            'ministerial_communication_details' => 'nullable|string|max:255',
            'convicted_length_details' => 'nullable|string|max:255',
            'convicted_sentence_expire_details' => 'nullable|string|max:255',
            'intervention_taken' => 'required|string|max:255',
        ], [
            'institute.required' => 'Institute is required. Please enter your name.',
            'institute.string' => 'Institute must be a valid text.',
            'institute.max' => 'Institute should not exceed 255 characters.',

            'full_name.required' => 'The full name is required. Please enter your name.',
            'full_name.string' => 'The full name must be a valid text.',
            'full_name.max' => 'The full name should not exceed 255 characters.',

            'sex.required' => 'Sex is required.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a valid number.',
            'age.min' => 'Age cannot be negative.',
            'age.max' => 'Age should not exceed 150.',
            'family_informed.required' => 'Please specify whether family or relatives have been informed.',
            
            'intervention_taken.required' => 'Please specify the intervention taken.',
            'intervention_taken.string' => 'Intervention details must be in text format.',
            'intervention_taken.max' => 'Intervention details should not exceed 255 characters.',
        ]);
    
        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
          
        }
        
        $districtName = (optional(auth()->user()->district)->name);
        $pngoName = (optional(auth()->user()->pngo)->name);
        $districtId = Auth::user()->district_id;
        $pngoId = Auth::user()->pngo_id;
        $existingCentralIds = FormalCase::where('district_id', $districtId)
        ->where('pngo_id', $pngoId)
        ->orderByDesc('id')
        ->first();
                            

        $highestCentralId = $existingCentralIds?->central_id;
        preg_match('/(\d+)$/', (string) $highestCentralId, $matches);
        $lastNumber = isset($matches[1]) ? $matches[1] : null;
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        $centralId = strtoupper(substr($districtName, 0, 3)) . '-LA-' . $nextNumber;

        // Create a new FormalCase object
        $case = new FormalCase();
        $case->central_id = $centralId;
        $case->user_id = auth()->id();
        $case->district_id = $districtId;
        $case->pngo_id = $pngoId;
        $case->status = FormalCase::STATUS_SUBMITTED;
        $case->institute = $request->institute;
        $case->full_name = $request->full_name;
        $case->nick_name = $request->nick_name;
        $case->father_name = $request->father_name;
        $case->mother_name = $request->mother_name;
        $case->sex = $request->sex;
        $case->age = $request->age;
        $case->disability = $request->disability;
        $case->nationality = $request->nationality;
        $case->nid_passport = $request->nid_passport;
        $case->phone_number = $request->phone_number;
        $case->address = $request->address;
        $case->interview_date = $request->interview_date;
        $case->interview_time = $request->interview_time;
        $case->interview_place = $request->interview_place;
        $case->marital_status = $request->marital_status;
        $case->spouse_name = $request->spouse_name;
        $case->education_level = $request->education_level;
        $case->occupation = $request->occupation;
        $case->monthly_income = $request->monthly_income;
        $case->family_informed = $request->family_informed;
        $case->children_with_prisoner = $request->children_with_prisoner;
        $case->child_sex = $request->child_sex;
        $case->child_age = $request->child_age;
        $case->child_2_sex = $request->child_2_sex;
        $case->child_2_age = $request->child_2_age;
        $case->has_guardian = $request->has_guardian;
        $case->guardian_name = $request->guardian_name;
        $case->guardian_phone = $request->guardian_phone;
        $case->guardian_address = $request->guardian_address;
        $case->guardian_relation = $request->guardian_relation;
        $case->guardian_relation_details = $request->guardian_relation_details;
        $case->guardian_surety = $request->guardian_surety;
        $case->has_lawyer = $request->has_lawyer;
        $case->lawyer_type = $request->lawyer_type;
        $case->lawyer_type_details = $request->lawyer_type_details;
        $case->lawyer_name = $request->lawyer_name;
        $case->lawyer_membership = $request->lawyer_membership;
        $case->lawyer_phone = $request->lawyer_phone;
        $case->incident_details = $request->incident_details;
        $case->custody_status = $request->custody_status;
        $case->charges_details = $request->charges_details;
        $case->arrest_date = $request->arrest_date;
        $case->case_no = $request->case_no;
        $case->family_communication_date = $request->family_communication_date;
        $case->legal_representation = $request->legal_representation;
        $case->legal_representation_details = $request->legal_representation_details;
        $case->legal_representation_date = $request->legal_representation_date;
        $case->collected_vokalatnama_date = $request->collected_vokalatnama_date;
        $case->collected_case_doc = $request->collected_case_doc;
        $case->identify_sureties = $request->identify_sureties;
        $case->identify_sureties_date = $request->identify_sureties_date;
        $case->witness_communication_date = $request->witness_communication_date;
        $case->medical_report_date = $request->medical_report_date;
        $case->legal_assistance_date = $request->legal_assistance_date;
        $case->assistance_under_custody_date = $request->assistance_under_custody_date;
        $case->referral_service = $request->referral_service;
        $case->referral_service_details = $request->referral_service_details;
        $case->referral_service_date = $request->referral_service_date;
        $case->case_resolved_date = $request->case_resolved_date;
        $case->resolved_dispute_date = $request->resolved_dispute_date;
        $case->appoint_lawyer_date = $request->appoint_lawyer_date;
        $case->release_status = $request->release_status;
        $case->fine_amount = $request->fine_amount;
        $case->release_status_date = $request->release_status_date;
        $case->other_result_details = $request->other_result_details;
        $case->other_result_date = $request->other_result_date;
        $case->application_mode = $request->application_mode;
        $case->application_mode_date = $request->application_mode_date;
        $case->received_application = $request->received_application;
        $case->reference_no = $request->reference_no;
        $case->type_of_service = $this->prepareMultiSelectValue($request->input('type_of_service'));
        $case->type_of_service_date = $request->type_of_service_date;
        $case->source_of_interview = $request->source_of_interview;
        $case->source_of_interview_details = $request->source_of_interview_details;
        $case->prison_reg_no = $request->prison_reg_no;
        $case->prison_case_no = $request->prison_case_no;
        $case->section_no = $request->section_no;
        $case->present_court = $request->present_court;
        $case->lockup_no = $request->lockup_no;
        $case->entry_date = $request->entry_date;
        $case->case_transferred = $request->case_transferred;
        $case->current_court = $request->current_court;
        $case->case_status = $request->case_status;
        $case->co_offenders = $request->co_offenders;
        $case->next_court_date = $request->next_court_date;
        $case->facts_of_case = $request->facts_of_case;
        $case->imprisonment_condition = $request->imprisonment_condition;
        $case->imprisonment_status = $request->imprisonment_status;
        $case->special_condition = $request->special_condition;
        $case->special_condition_details = $request->special_condition_details;
        $case->prison_arrest_date = $request->prison_arrest_date;
        $case->surrender_date = $request->surrender_date;
        $case->prison_family_communication = $request->prison_family_communication;
        $case->prison_legal_representation = $request->prison_legal_representation;
        $case->prison_legal_representation_details = $request->prison_legal_representation_details;
        $case->prison_legal_representation_date = $request->prison_legal_representation_date;
        $case->next_court_collection_date = $request->next_court_collection_date;
        $case->prison_next_court_date = $request->prison_next_court_date;
        $case->collected_case_doc_prison = $request->collected_case_doc_prison;
        $case->identify_sureties_prison_nid = $request->identify_sureties_prison_nid;
        $case->identify_sureties_prison_phone = $request->identify_sureties_prison_phone;
        $case->identify_sureties_prison_date = $request->identify_sureties_prison_date;
        $case->witness_communication_prison = $request->witness_communication_prison;
        $case->bail_bond_submission = $request->bail_bond_submission;
        $case->court_order_communication = $request->court_order_communication;
        $case->application_certified_copies = $request->application_certified_copies;
        $case->appeal_assistance = $request->appeal_assistance;
        $case->ministerial_communication = $request->ministerial_communication;
        $case->ministerial_communication_details = $request->ministerial_communication_details;
        $case->other_legal_assistance = $request->other_legal_assistance;
        $case->other_legal_assistance_details = $request->other_legal_assistance_details;
        $case->other_legal_assistance_date = $request->other_legal_assistance_date;
        $case->released_on = $request->released_on;
        $case->released_on_date = $request->released_on_date;
        $case->send_to = $request->send_to;
        $case->send_to_details = $request->send_to_details;
        $case->send_to_date = $request->send_to_date;
        $case->convicted_length = $request->convicted_length;
        $case->convicted_length_details = $request->convicted_length_details;
        $case->convicted_sentence_expire = $request->convicted_sentence_expire;
        $case->convicted_sentence_expire_details = $request->convicted_sentence_expire_details;
        $case->result_of_appeal = $request->result_of_appeal;
        $case->result_of_appeal_date = $request->result_of_appeal_date;
        $case->prison_case_resolved_date = $request->prison_case_resolved_date;
        $case->date_of_reliefe = $request->date_of_reliefe;
        $case->result_description = $request->result_description;
        $case->file_closure_date = $request->file_closure_date;
    
        DB::beginTransaction();

        try {
            // Save the case
            $case->save();

            // Create and save the follow-up intervention
            $followup = new FollowUpIntervention([
                'central_id' => $case->id,
                'user_id' => auth()->id(),
                'intervention_taken' => $request->intervention_taken,
                'intervention_taken_date' => $request->intervention_taken_date,
                'intervention_to_be_taken' => $request->intervention_to_be_taken,
                'to_be_taken_date' => $request->to_be_taken_date,
            ]);
            $followup->save();

            // Initialize the log data
            $logData = [
                'case_id' => $case->id,
                'central_id' => $centralId,  
                'created_by' => auth()->user()->name,
                'followup_intervention_taken' => $followup->intervention_taken,
                'followup_taken_on' => $followup->intervention_taken_date,
            ];

            // Initialize an array to store file upload details
            $uploadedFiles = [];

            // Handle file uploads, if any
            if ($request->hasFile('fileUpload')) {
                $logData['file_count'] = count($request->file('fileUpload'));

                foreach ($request->file('fileUpload') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $baseName = pathinfo($originalName, PATHINFO_FILENAME);
                    $newFileName = $case->id . '_' . Str::slug($baseName) . '_' . uniqid() . '.' . $extension;

                    // Store the file
                    $path = $file->storeAs('uploads/formal_cases', $newFileName, 'public');

                    // Save the file upload record
                    FileUpload::create([
                        'case_id' => $case->id,
                        'file_name' => $newFileName,
                        'file_path' => $path,
                        'uploaded_by' => auth()->id(),
                    ]);

                    // Add file details to the log
                    $uploadedFiles[] = [
                        'file_name' => $newFileName,
                        'file_path' => $path,
                    ];
                }

                // Add uploaded files details to the log
                $logData['uploaded_files'] = $uploadedFiles;
            }

            // Log the entire process in one go
            LogService::logAction('Case and Follow-up Created with File Uploads', $logData);

            // Commit the transaction
            DB::commit();

            return redirect()->route('form.index')->with('success', 'Case has been successfully created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    
        
    }
    
    public function editCourtPolicePrison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'institute' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'sex' => 'required|string|max:255',
            'age' => 'required|integer|min:0|max:150',
            'family_informed' => 'required|string|max:255',
            'child_age' => 'nullable|integer|min:0|max:150',
            'child_2_age' => 'nullable|integer|min:0|max:150',
            'guardian_relation_details' => 'nullable|string|max:255',
            'lawyer_type_details' => 'nullable|string|max:255',
            'legal_representation_details' => 'nullable|string|max:255',
            'referral_service_details' => 'nullable|string|max:255',
            'source_of_interview_details' => 'nullable|string|max:255',
            'special_condition_details' => 'nullable|string|max:255',
            'prison_legal_representation_details' => 'nullable|string|max:255',
            'other_legal_assistance_details' => 'nullable|string|max:255',
            'send_to_details' => 'nullable|string|max:255',
            'other_result_details' => 'nullable|string|max:255',
            'ministerial_communication_details' => 'nullable|string|max:255',
            'convicted_length_details' => 'nullable|string|max:255',
            'convicted_sentence_expire_details' => 'nullable|string|max:255',
            'intervention_taken' => 'required|string|max:255',
        ], [
            'institute.required' => 'Institute is required. Please enter your name.',
            'institute.string' => 'Institute must be a valid text.',
            'institute.max' => 'Institute should not exceed 255 characters.',
            
            'full_name.required' => 'The full name is required. Please enter your name.',
            'full_name.string' => 'The full name must be a valid text.',
            'full_name.max' => 'The full name should not exceed 255 characters.',

            'sex.required' => 'Sex is required.',
            'age.required' => 'Age is required.',
            'age.integer' => 'Age must be a valid number.',
            'age.min' => 'Age cannot be negative.',
            'age.max' => 'Age should not exceed 150.',
            'family_informed.required' => 'Please specify whether family or relatives have been informed.',
            
            'intervention_taken.required' => 'Please specify the intervention taken.',
            'intervention_taken.string' => 'Intervention details must be in text format.',
            'intervention_taken.max' => 'Intervention details should not exceed 255 characters.',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        
        }
        $id = $request->id;
        $case = FormalCase::find($id);
        
        $case->institute = $request->institute;
        $case->full_name = $request->full_name;
        $case->nick_name = $request->nick_name;
        $case->father_name = $request->father_name;
        $case->mother_name = $request->mother_name;
        $case->sex = $request->sex;
        $case->age = $request->age;
        $case->disability = $request->disability;
        $case->nationality = $request->nationality;
        $case->nid_passport = $request->nid_passport;
        $case->phone_number = $request->phone_number;
        $case->address = $request->address;
        $case->interview_date = $request->interview_date;
        $case->interview_time = $request->interview_time;
        $case->interview_place = $request->interview_place;
        $case->marital_status = $request->marital_status;
        $case->spouse_name = $request->spouse_name;
        $case->education_level = $request->education_level;
        $case->occupation = $request->occupation;
        $case->monthly_income = $request->monthly_income;
        $case->family_informed = $request->family_informed;
        $case->children_with_prisoner = $request->children_with_prisoner;
        $case->child_sex = $request->child_sex;
        $case->child_age = $request->child_age;
        $case->child_2_sex = $request->child_2_sex;
        $case->child_2_age = $request->child_2_age;
        $case->has_guardian = $request->has_guardian;
        $case->guardian_name = $request->guardian_name;
        $case->guardian_phone = $request->guardian_phone;
        $case->guardian_address = $request->guardian_address;
        $case->guardian_relation = $request->guardian_relation;
        $case->guardian_relation_details = $request->guardian_relation_details;
        $case->guardian_surety = $request->guardian_surety;
        $case->has_lawyer = $request->has_lawyer;
        $case->lawyer_type = $request->lawyer_type;
        $case->lawyer_type_details = $request->lawyer_type_details;
        $case->lawyer_name = $request->lawyer_name;
        $case->lawyer_membership = $request->lawyer_membership;
        $case->lawyer_phone = $request->lawyer_phone;
        $case->incident_details = $request->incident_details;
        $case->custody_status = $request->custody_status;
        $case->charges_details = $request->charges_details;
        $case->arrest_date = $request->arrest_date;
        $case->case_no = $request->case_no;
        $case->family_communication_date = $request->family_communication_date;
        $case->legal_representation = $request->legal_representation;
        $case->legal_representation_details = $request->legal_representation_details;
        $case->legal_representation_date = $request->legal_representation_date;
        $case->collected_vokalatnama_date = $request->collected_vokalatnama_date;
        $case->collected_case_doc = $request->collected_case_doc;
        $case->identify_sureties = $request->identify_sureties;
        $case->identify_sureties_date = $request->identify_sureties_date;
        $case->witness_communication_date = $request->witness_communication_date;
        $case->medical_report_date = $request->medical_report_date;
        $case->legal_assistance_date = $request->legal_assistance_date;
        $case->assistance_under_custody_date = $request->assistance_under_custody_date;
        $case->referral_service = $request->referral_service;
        $case->referral_service_details = $request->referral_service_details;
        $case->referral_service_date = $request->referral_service_date;
        $case->case_resolved_date = $request->case_resolved_date;
        $case->resolved_dispute_date = $request->resolved_dispute_date;
        $case->appoint_lawyer_date = $request->appoint_lawyer_date;
        $case->release_status = $request->release_status;
        $case->fine_amount = $request->fine_amount;
        $case->release_status_date = $request->release_status_date;
        $case->other_result_details = $request->other_result_details;
        $case->other_result_date = $request->other_result_date;
        $case->application_mode = $request->application_mode;
        $case->application_mode_date = $request->application_mode_date;
        $case->received_application = $request->received_application;
        $case->reference_no = $request->reference_no;
        $case->type_of_service = $this->prepareMultiSelectValue($request->input('type_of_service'));
        $case->type_of_service_date = $request->type_of_service_date;
        $case->source_of_interview = $request->source_of_interview;
        $case->source_of_interview_details = $request->source_of_interview_details;
        $case->prison_reg_no = $request->prison_reg_no;
        $case->prison_case_no = $request->prison_case_no;
        $case->section_no = $request->section_no;
        $case->present_court = $request->present_court;
        $case->lockup_no = $request->lockup_no;
        $case->entry_date = $request->entry_date;
        $case->case_transferred = $request->case_transferred;
        $case->current_court = $request->current_court;
        $case->case_status = $request->case_status;
        $case->co_offenders = $request->co_offenders;
        $case->next_court_date = $request->next_court_date;
        $case->facts_of_case = $request->facts_of_case;
        $case->imprisonment_condition = $request->imprisonment_condition;
        $case->imprisonment_status = $request->imprisonment_status;
        $case->special_condition = $request->special_condition;
        $case->special_condition_details = $request->special_condition_details;
        $case->prison_arrest_date = $request->prison_arrest_date;
        $case->surrender_date = $request->surrender_date;
        $case->prison_family_communication = $request->prison_family_communication;
        $case->prison_legal_representation = $request->prison_legal_representation;
        $case->prison_legal_representation_details = $request->prison_legal_representation_details;
        $case->prison_legal_representation_date = $request->prison_legal_representation_date;
        $case->next_court_collection_date = $request->next_court_collection_date;
        $case->prison_next_court_date = $request->prison_next_court_date;
        $case->collected_case_doc_prison = $request->collected_case_doc_prison;
        $case->identify_sureties_prison_nid = $request->identify_sureties_prison_nid;
        $case->identify_sureties_prison_phone = $request->identify_sureties_prison_phone;
        $case->identify_sureties_prison_date = $request->identify_sureties_prison_date;
        $case->witness_communication_prison = $request->witness_communication_prison;
        $case->bail_bond_submission = $request->bail_bond_submission;
        $case->court_order_communication = $request->court_order_communication;
        $case->application_certified_copies = $request->application_certified_copies;
        $case->appeal_assistance = $request->appeal_assistance;
        $case->ministerial_communication = $request->ministerial_communication;
        $case->ministerial_communication_details = $request->ministerial_communication_details;
        $case->other_legal_assistance = $request->other_legal_assistance;
        $case->other_legal_assistance_details = $request->other_legal_assistance_details;
        $case->other_legal_assistance_date = $request->other_legal_assistance_date;
        $case->released_on = $request->released_on;
        $case->released_on_date = $request->released_on_date;
        $case->send_to = $request->send_to;
        $case->send_to_details = $request->send_to_details;
        $case->send_to_date = $request->send_to_date;
        $case->convicted_length = $request->convicted_length;
        $case->convicted_length_details = $request->convicted_length_details;
        $case->convicted_sentence_expire = $request->convicted_sentence_expire;
        $case->convicted_sentence_expire_details = $request->convicted_sentence_expire_details;
        $case->result_of_appeal = $request->result_of_appeal;
        $case->result_of_appeal_date = $request->result_of_appeal_date;
        $case->prison_case_resolved_date = $request->prison_case_resolved_date;
        $case->date_of_reliefe = $request->date_of_reliefe;
        $case->result_description = $request->result_description;
        $case->file_closure_date = $request->file_closure_date;

        DB::beginTransaction();

        try {
            // Save the case
            $case->save();

            // Save each edit follow-up as a new intervention history row.
            $followup = new FollowUpIntervention();
            $followup->central_id = $case->id;
            $followup->user_id = auth()->id();
            $followup->intervention_taken = $request->intervention_taken;
            $followup->intervention_taken_date = $request->intervention_taken_date;
            $followup->intervention_to_be_taken = $request->intervention_to_be_taken;
            $followup->to_be_taken_date = $request->to_be_taken_date;
            $followup->save();

            // Initialize the log data
            $logData = [
                'case_id' => $case->id,
                'central_id' => $case->central_id,  // Assuming central_id is part of the case
                'updated_by' => auth()->user()->name,
                'followup_intervention_taken' => $followup->intervention_taken,
                'followup_taken_on' => $followup->intervention_taken_date,
            ];

            // Initialize an array to store file upload details
            $uploadedFiles = [];

            // Handle file uploads, if any
            if ($request->hasFile('fileUpload')) {
                $logData['file_count'] = count($request->file('fileUpload'));

                foreach ($request->file('fileUpload') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $newFileName = $case->id . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $extension;

                    // Store the file
                    $path = $file->storeAs('uploads/formal_cases', $newFileName, 'public');

                    // Save the file upload record
                    FileUpload::create([
                        'case_id' => $case->id,
                        'file_name' => $newFileName,
                        'file_path' => $path, // optional if your table has this column
                        'uploaded_by' => auth()->id(),
                    ]);

                    // Add file details to the log
                    $uploadedFiles[] = [
                        'file_name' => $newFileName,
                        'file_path' => $path,
                    ];
                }

                // Add uploaded files details to the log
                $logData['uploaded_files'] = $uploadedFiles;
            }

            // Log the entire process in one go
            LogService::logAction('Case and Follow-up Updated with File Uploads', $logData);

            // Commit the transaction
            DB::commit();

            return redirect()->route('form.index')->with('success', 'Case has been successfully edited.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }

    }

    public function editCase(Request $request)
    {
        Session::put('edit_id', $request->edit_id);
        return response()->json(['success' => true, 'redirect_url' => route('edit-case.get')]);
    }
    public function editCaseForm(Request $request)
    {
        $editId = Session::get('edit_id');
        // Session::forget('edit_id');

        if (!$editId) {
            return redirect()->route('case_list');
        }
        $caseData = FormalCase::find($editId);

        return view('dashboard.admin.edit-case', compact('caseData'));
    }

    public function fileCase(Request $request)
    {
        Session::put('file_id', $request->file_id);
        return response()->json(['success' => true, 'redirect_url' => route('edit-file.get')]);
    }
    public function fileCaseForm(Request $request)
    {
        $caseId = Session::get('file_id');
        // Session::forget('edit_id');

        if (!$caseId) {
            return redirect()->route('case_list');
        }
        $caseFiles = FileUpload::where('case_id', $caseId)->orderBy('id', 'asc')->get();

        return view('dashboard.admin.get-file', compact('caseFiles'));
    }

    public function importView()
    {
        return view('dashboard.admin.import');
    }

    public function downloadImportTemplate()
    {
        new FormalCaseImportTemplateExport();

        return response()->streamDownload(function () {
            $fields = \App\Exports\FormalCaseImportTemplateFields::fields();
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $dataSheet = $spreadsheet->getActiveSheet();
            $dataSheet->setTitle('Upload Data');
            $dataSheet->fromArray(array_column($fields, 'key'), null, 'A1');
            $dataSheet->freezePane('A2');
            $dataSheet->getStyle('1:1')->getFont()->setBold(true);
            $dataSheet->getStyle('1:1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8F5EE');

            $guideSheet = $spreadsheet->createSheet();
            $guideSheet->setTitle('Field Guide');
            $guideSheet->fromArray(['Upload header', 'Form no.', 'Field label', 'Required', 'Notes / sample values'], null, 'A1');

            foreach ($fields as $index => $field) {
                $guideSheet->fromArray([
                    $field['key'],
                    $field['no'] ?? '',
                    $field['label'],
                    $field['required'] ?? '',
                    $field['note'] ?? '',
                ], null, 'A' . ($index + 2));
            }

            $guideSheet->freezePane('A2');
            $guideSheet->getStyle('A1:E1')->getFont()->setBold(true);
            $guideSheet->getStyle('A1:E1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE8F5EE');
            $guideSheet->getStyle('A:E')->getAlignment()->setWrapText(true);

            foreach ([$dataSheet, $guideSheet] as $sheet) {
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestColumn());

                for ($columnIndex = 1; $columnIndex <= $highestColumnIndex; $columnIndex++) {
                    $column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex);
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }

            (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save('php://output');
        }, 'formal_cases_import_template.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048',
        ]);

        // Process the uploaded file and loop through the rows
        $data = Excel::toArray(new FormalCaseImport, $request->file('file'))[0];

        foreach ($data as $row) {
            // Ensure that child_age is null if empty
            $row['child_age'] = !empty($row['child_age']) && is_numeric($row['child_age']) ? $row['child_age'] : null;
            $row['child_2_age'] = !empty($row['child_2_age']) && is_numeric($row['child_2_age']) ? $row['child_2_age'] : null;

            // Convert dates to proper format (if necessary) and handle missing dates collected_case_doc
            $row['interview_date'] = $this->convertToDateFormat($row['interview_date']);
            $row['arrest_date'] = $this->convertToDateFormat($row['arrest_date']);
            $row['legal_representation_date'] = $this->convertToDateFormat($row['legal_representation_date']);
            $row['collected_vokalatnama_date'] = $this->convertToDateFormat($row['collected_vokalatnama_date']);
            $row['family_communication_date'] = $this->convertToDateFormat($row['family_communication_date']);
            $row['witness_communication_date'] = $this->convertToDateFormat($row['witness_communication_date']);
            $row['medical_report_date'] = $this->convertToDateFormat($row['medical_report_date']);
            $row['legal_assistance_date'] = $this->convertToDateFormat($row['legal_assistance_date']);
            $row['assistance_under_custody_date'] = $this->convertToDateFormat($row['assistance_under_custody_date']);
            $row['referral_service_date'] = $this->convertToDateFormat($row['referral_service_date']);
            $row['case_resolved_date'] = $this->convertToDateFormat($row['case_resolved_date'] ?? null);
            $row['resolved_dispute_date'] = $this->convertToDateFormat($row['resolved_dispute_date']);
            $row['appoint_lawyer_date'] = $this->convertToDateFormat($row['appoint_lawyer_date']);
            $row['release_status_date'] = $this->convertToDateFormat($row['release_status_date']);
            $row['other_result_date'] = $this->convertToDateFormat($row['other_result_date'] ?? null);
            $row['application_mode_date'] = $this->convertToDateFormat($row['application_mode_date']);
            $row['type_of_service_date'] = $this->convertToDateFormat($row['type_of_service_date']);
            $row['collected_case_doc'] = $this->convertToDateFormat($row['collected_case_doc']);
            $row['identify_sureties_date'] = $this->convertToDateFormat($row['identify_sureties_date'] ?? null);
            $row['entry_date'] = $this->convertToDateFormat($row['entry_date']);
            $row['next_court_date'] = $this->convertToDateFormat($row['next_court_date']);
            $row['surrender_date'] = $this->convertToDateFormat($row['surrender_date']);
            $row['prison_family_communication'] = $this->convertToDateFormat($row['prison_family_communication']);
            $row['prison_legal_representation'] = $this->convertToDateFormat($row['prison_legal_representation']);
            $row['prison_legal_representation_date'] = $this->convertToDateFormat($row['prison_legal_representation_date']);
            $row['prison_next_court_date'] = $this->convertToDateFormat($row['prison_next_court_date']);
            $row['identify_sureties_prison_date'] = $this->convertToDateFormat($row['identify_sureties_prison_date'] ?? null);
            $row['witness_communication_prison'] = $this->convertToDateFormat($row['witness_communication_prison']);
            $row['bail_bond_submission'] = $this->convertToDateFormat($row['bail_bond_submission']);
            $row['court_order_communication'] = $this->convertToDateFormat($row['court_order_communication']);
            $row['application_certified_copies'] = $this->convertToDateFormat($row['application_certified_copies']);
            $row['other_legal_assistance_date'] = $this->convertToDateFormat($row['other_legal_assistance_date']);
            $row['released_on'] = $this->convertToDateFormat($row['released_on']);
            $row['released_on_date'] = $this->convertToDateFormat($row['released_on_date']);
            $row['send_to_date'] = $this->convertToDateFormat($row['send_to_date']);
            $row['convicted_sentence_expire'] = $this->convertToDateFormat($row['convicted_sentence_expire']);
            $row['result_of_appeal_date'] = $this->convertToDateFormat($row['result_of_appeal_date'] ?? null);
            $row['prison_case_resolved_date'] = $this->convertToDateFormat($row['prison_case_resolved_date'] ?? null);
            $row['date_of_reliefe'] = $this->convertToDateFormat($row['date_of_reliefe']);
            $row['file_closure_date'] = $this->convertToDateFormat($row['file_closure_date']);

            if (!empty($row['reference_no'])) {
                $row['received_application'] = 'Yes';
            }

            
            // Insert the row into the database
            FormalCase::create([
                'institute' => $row['institute'],
                'central_id' => $row['central_id'],
                'user_id' => auth()->id(),
                'district_id' => $row['district_id'],
                'pngo_id' => $row['pngo_id'],
                'status' => $row['status'],
                'full_name' => $row['full_name'],
                'nick_name' => $row['nick_name'],
                'father_name' => $row['father_name'],
                'mother_name' => $row['mother_name'],
                'sex' => $row['sex'],
                'age' => $row['age'],
                'disability' => $row['disability'],
                'nationality' => $row['nationality'],
                'nid_passport' => $row['nid_passport'],
                'phone_number' => $row['phone_number'],
                'address' => $row['address'],
                'interview_date' => $row['interview_date'],
                'interview_time' => $row['interview_time'],
                'interview_place' => $row['interview_place'],
                'marital_status' => $row['marital_status'],
                'spouse_name' => $row['spouse_name'],
                'education_level' => $row['education_level'],
                'occupation' => $row['occupation'],
                'monthly_income' => $row['monthly_income'],
                'family_informed' => $row['family_informed'],
                'children_with_prisoner' => $row['children_with_prisoner'],
                'child_sex' => $row['child_sex'],
                'child_age' => $row['child_age'],  // Now null if empty
                'child_2_sex' => $row['child_2_sex'] ?? null,
                'child_2_age' => $row['child_2_age'] ?? null,
                'has_guardian' => $row['has_guardian'],
                'guardian_name' => $row['guardian_name'],
                'guardian_phone' => $row['guardian_phone'],
                'guardian_address' => $row['guardian_address'],
                'guardian_relation' => $row['guardian_relation'],
                'guardian_relation_details' => $row['guardian_relation_details'] ?? null,
                'guardian_surety' => $row['guardian_surety'],
                'has_lawyer' => $row['has_lawyer'],
                'lawyer_type' => $row['lawyer_type'],
                'lawyer_type_details' => $row['lawyer_type_details'] ?? null,
                'lawyer_name' => $row['lawyer_name'],
                'lawyer_membership' => $row['lawyer_membership'],
                'lawyer_phone' => $row['lawyer_phone'],
                'incident_details' => $row['incident_details'],
                'custody_status' => $row['custody_status'],
                'charges_details' => $row['charges_details'],
                'arrest_date' => $row['arrest_date'],
                'case_no' => $row['case_no'],
                'family_communication_date' => $row['family_communication_date'],
                'legal_representation' => $row['legal_representation'],
                'legal_representation_details' => $row['legal_representation_details'] ?? null,
                'legal_representation_date' => $row['legal_representation_date'],
                'collected_vokalatnama_date' => $row['collected_vokalatnama_date'],
                'collected_case_doc' => $row['collected_case_doc'],
                'identify_sureties' => $row['identify_sureties'],
                'identify_sureties_date' => $row['identify_sureties_date'] ?? null,
                'witness_communication_date' => $row['witness_communication_date'],
                'medical_report_date' => $row['medical_report_date'],
                'legal_assistance_date' => $row['legal_assistance_date'],
                'assistance_under_custody_date' => $row['assistance_under_custody_date'],
                'referral_service' => $row['referral_service'],
                'referral_service_details' => $row['referral_service_details'] ?? null,
                'referral_service_date' => $row['referral_service_date'],
                'case_resolved_date' => $row['case_resolved_date'] ?? null,
                'resolved_dispute_date' => $row['resolved_dispute_date'],
                'appoint_lawyer_date' => $row['appoint_lawyer_date'],
                'release_status' => $row['release_status'],
                'fine_amount' => $row['fine_amount'],
                'release_status_date' => $row['release_status_date'],
                'other_result_details' => $row['other_result_details'] ?? null,
                'other_result_date' => $row['other_result_date'] ?? null,
                'application_mode' => $row['application_mode'],
                'application_mode_date' => $row['application_mode_date'],
                'received_application' => $row['received_application'],
                'reference_no' => $row['reference_no'],
                'type_of_service' => $this->prepareMultiSelectValue($row['type_of_service'] ?? null),
                'type_of_service_date' => $row['type_of_service_date'],
                'source_of_interview' => $row['source_of_interview'],
                'source_of_interview_details' => $row['source_of_interview_details'] ?? null,
                'prison_reg_no' => $row['prison_reg_no'],
                'prison_case_no' => $row['prison_case_no'],
                'entry_date' => $row['entry_date'],
                'case_transferred' => $row['case_transferred'],
                'current_court' => $row['current_court'],
                'case_status' => $row['case_status'],
                'next_court_date' => $row['next_court_date'],
                'facts_of_case' => $row['facts_of_case'],
                'imprisonment_condition' => $row['imprisonment_condition'],
                'special_condition' => $row['special_condition'],
                'special_condition_details' => $row['special_condition_details'] ?? null,
                'prison_arrest_date' => $row['prison_arrest_date'],
                'surrender_date' => $row['surrender_date'],
                'released_on' => $row['released_on'],
                'identify_sureties_prison_date' => $row['identify_sureties_prison_date'] ?? null,
                'prison_legal_representation_details' => $row['prison_legal_representation_details'] ?? null,
                'ministerial_communication_details' => $row['ministerial_communication_details'] ?? null,
                'other_legal_assistance_details' => $row['other_legal_assistance_details'] ?? null,
                'result_of_appeal' => $row['result_of_appeal'],
                'convicted_length_details' => $row['convicted_length_details'] ?? null,
                'convicted_sentence_expire_details' => $row['convicted_sentence_expire_details'] ?? null,
                'result_of_appeal_date' => $row['result_of_appeal_date'] ?? null,
                'prison_case_resolved_date' => $row['prison_case_resolved_date'] ?? null,
                'send_to_details' => $row['send_to_details'] ?? null,
                'date_of_reliefe' => $row['date_of_reliefe'],
                'result_description' => $row['result_description'] ?? null,
                'file_closure_date' => $row['file_closure_date'],
            ]);

            $followup = new FollowUpIntervention();
            $followup->central_id = $row['central_id']; 
            $followup->user_id = auth()->id();
            $followup->intervention_taken = 'Initial Information Collected'; 
            $followup->intervention_taken_date = $row['interview_date']; 
            // $followup->intervention_to_be_taken = $row['intervention_to_be_taken']; 
            // $followup->to_be_taken_date = $this->convertToDateFormat($row['to_be_taken_date']); 
            $followup->save();
        }

        return redirect()->back()->with('success', 'Data Imported Successfully.');
    }

// Function to convert date format to 'Y-m-d' using Carbon
private function convertToDateFormat($date)
{
    if ($date) {
        // Here, we'll try to convert different formats
        try {
            return Carbon::createFromFormat('d-M-y', $date)->format('Y-m-d');  // Example: 15-Jul-24 => 2024-07-15
        } catch (\Exception $e) {
            return null;  // Return null if the format is incorrect
        }
    }
    return null;
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


    // Function to read Excel
    private function readExcel($file)
    {
        return Excel::toArray([], $file)[0]; // Read the first sheet
    }


    public function verifyCase(Request $request)
    {
        // Validate the case ID
        $request->validate([
            'id' => 'required|exists:formal_cases,id',
        ]);

        // Check if the case has data in any of the specified fields
        if (!$this->hasDataInFields($request->id)) {
            // If the case doesn't have valid data, return an error response
            return response()->json(['success' => false, 'message' => 'The case is not valid for verification.'], 400);
        }

        // Find the case and update the status
        $case = FormalCase::findOrFail($request->id);
        $oldStatus = $case->status; // Store the previous status

        if ((int) $oldStatus !== FormalCase::STATUS_SUBMITTED) {
            return response()->json([
                'success' => false,
                'message' => 'Only submitted cases can be verified by DPO.',
            ], 400);
        }

        $case->status = FormalCase::STATUS_DPO_VERIFIED; // Update status to "Verified by DPO"
        $case->save();

        // Log the action in the LogService
        LogService::logAction('Case Verified by DPO', [
            'case_id' => $case->id,
            'previous_status' => $oldStatus,
            'new_status' => $case->status,
            'verified_by' => auth()->user()->name,
        ]);

        return response()->json(['success' => true, 'message' => 'Case verified successfully.']);
    }


    public function verifyCaseMneo(Request $request)
    {
        // Validate the case ID
        $request->validate([
            'id' => 'required|exists:formal_cases,id',
        ]);
    
        // Check if the case has data in any of the specified fields
        if (!$this->hasDataInFields($request->id)) {
            // If the case doesn't have valid data, return an error response
            return response()->json(['success' => false, 'message' => 'The case is not valid for verification.'], 400);
        }
    
        // Find the case and update the status
        $case = FormalCase::findOrFail($request->id);
        $oldStatus = $case->status; // Store the previous status

        if ((int) $oldStatus !== FormalCase::STATUS_DPO_VERIFIED) {
            return response()->json([
                'success' => false,
                'message' => 'The case must be verified by DPO before MNEO verification.',
            ], 400);
        }

        $case->status = FormalCase::STATUS_MNEO_VERIFIED; // Update status to "Verified by MNEO"
        $case->save();
    
        // Log the action in the LogService
        LogService::logAction('Case Verified by MNEO', [
            'case_id' => $case->id,
            'previous_status' => $oldStatus,
            'new_status' => $case->status,
            'verified_by' => auth()->user()->name,
        ]);
    
        return response()->json(['success' => true, 'message' => 'Case verified successfully by MNEO.']);
    }
    


    public function hasDataInFields($caseId)
    {
        // Fields for Court and Prison data
        $courtFields = [
            'custody_status', 'charges_details', 'arrest_date', 'case_no', 'family_communication_date',
            'legal_representation', 'legal_representation_date', 'collected_vokalatnama_date',
            'collected_case_doc', 'identify_sureties', 'identify_sureties_date', 'witness_communication_date', 'medical_report_date',
            'legal_assistance_date', 'assistance_under_custody_date', 'referral_service', 'referral_service_details',
            'referral_service_date', 'resolved_dispute_date', 'case_resolved_date', 'appoint_lawyer_date', 'release_status',
            'fine_amount', 'release_status_date', 'other_result_details', 'other_result_date', 'application_mode', 'application_mode_date',
            'received_application', 'reference_no', 'type_of_service', 'type_of_service_date'
        ];

        $prisonFields = [
            'source_of_interview', 'prison_reg_no', 'section_no', 'present_court', 'lockup_no', 'entry_date',
            'case_transferred', 'current_court', 'case_status', 'co_offenders', 'next_court_date', 'facts_of_case',
            'imprisonment_condition', 'imprisonment_status', 'special_condition', 'surrender_date',
            'prison_family_communication', 'prison_legal_representation', 'prison_legal_representation_date',
            'next_court_collection_date', 'collected_case_doc_prison', 'identify_sureties_prison_nid',
            'identify_sureties_prison_phone', 'identify_sureties_prison_date', 'witness_communication_prison', 'bail_bond_submission',
            'court_order_communication', 'application_certified_copies', 'appeal_assistance',
            'ministerial_communication', 'ministerial_communication_details', 'other_legal_assistance', 'other_legal_assistance_date',
            'convicted_length_details', 'convicted_sentence_expire_details', 'result_of_appeal_date', 'prison_case_resolved_date'
        ];

        // Merge both arrays into one for easier checking
        $fieldsToCheck = array_merge($courtFields, $prisonFields);

        // Get the case record based on the provided ID
        $case = FormalCase::find($caseId);

        // If no case is found, return false
        if (!$case) {
            return false;
        }

        // Check if any of the fields have data
        foreach ($fieldsToCheck as $field) {
            if (!empty($case->$field)) {
                return true; // At least one field has data
            }
        }

        // If no field has data, return false
        return false;
    }



    

}
