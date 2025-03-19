<?php

namespace App\Http\Controllers;

use App\Models\FormalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormalController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.formal1');
    }

    public function courtPolicePrison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'interview_place' => 'required|string|max:255',
        ]);
    
        // Check if the validation fails
        if ($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
            return response()->json([
                'errors' => $validator->errors(),
            ]);
        }
        
        $districtName = (optional(auth()->user()->district)->name);
        $pngoName = (optional(auth()->user()->pngo)->name);
        $districtId = Auth::user()->district_id;
        $pngoId = Auth::user()->pngo_id;
        $existingCentralIds = FormalCase::where('district_id', $districtId)
                                ->where('pngo_id', $pngoId)
                                ->get();
        $highestCentralId = $existingCentralIds->max('central_id');
        $nextNumber = $highestCentralId ? $highestCentralId + 1 : 1;

        $centralId = $districtName . '-' . $nextNumber;
        // Create a new FormalCase object
        $case = new FormalCase();
        $case->central_id = $centralId;
        $case->user_id = auth()->id();
        $case->district_id = $districtId;
        $case->pngo_id = $pngoId;
        $case->profile_no = $centralId;
        $case->status = 1;
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
        $case->has_guardian = $request->has_guardian;
        $case->guardian_name = $request->guardian_name;
        $case->guardian_phone = $request->guardian_phone;
        $case->guardian_address = $request->guardian_address;
        $case->guardian_relation = $request->guardian_relation;
        $case->guardian_surety = $request->guardian_surety;
        $case->has_lawyer = $request->has_lawyer;
        $case->lawyer_type = $request->lawyer_type;
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
        $case->legal_representation_date = $request->legal_representation_date;
        $case->collected_vokalatnama_date = $request->collected_vokalatnama_date;
        $case->collected_case_doc = $request->collected_case_doc;
        $case->identify_sureties = $request->identify_sureties;
        $case->witness_communication_date = $request->witness_communication_date;
        $case->medical_report_date = $request->medical_report_date;
        $case->legal_assistance_date = $request->legal_assistance_date;
        $case->assistance_under_custody_date = $request->assistance_under_custody_date;
        $case->referral_service = $request->referral_service;
        $case->referral_service_date = $request->referral_service_date;
        $case->resolved_dispute_date = $request->resolved_dispute_date;
        $case->appoint_lawyer_date = $request->appoint_lawyer_date;
        $case->release_status = $request->release_status;
        $case->fine_amount = $request->fine_amount;
        $case->release_status_date = $request->release_status_date;
        $case->application_mode = $request->application_mode;
        $case->application_mode_date = $request->application_mode_date;
        $case->received_application = $request->received_application;
        $case->reference_no = $request->reference_no;
        $case->type_of_service = $request->type_of_service;
        $case->type_of_service_date = $request->type_of_service_date;
        $case->service_description = $request->service_description;
        $case->source_of_interview = $request->source_of_interview;
        $case->prison_reg_no = $request->prison_reg_no;
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
        $case->surrender_date = $request->surrender_date;
        $case->prison_family_communication = $request->prison_family_communication;
        $case->prison_legal_representation = $request->prison_legal_representation;
        $case->prison_legal_representation_date = $request->prison_legal_representation_date;
        $case->next_court_collection_date = $request->next_court_collection_date;
        $case->collected_case_doc_prison = $request->collected_case_doc_prison;
        $case->identify_sureties_prison_nid = $request->identify_sureties_prison_nid;
        $case->identify_sureties_prison_phone = $request->identify_sureties_prison_phone;
        $case->witness_communication_prison = $request->witness_communication_prison;
        $case->bail_bond_submission = $request->bail_bond_submission;
        $case->court_order_communication = $request->court_order_communication;
        $case->application_certified_copies = $request->application_certified_copies;
        $case->appeal_assistance = $request->appeal_assistance;
        $case->ministerial_communication = $request->ministerial_communication;
        $case->other_legal_assistance = $request->other_legal_assistance;
        $case->other_legal_assistance_date = $request->other_legal_assistance_date;
        $case->released_on = $request->released_on;
        $case->released_on_date = $request->released_on_date;
        $case->send_to = $request->send_to;
        $case->send_to_date = $request->send_to_date;
        $case->convicted_length = $request->convicted_length;
        $case->convicted_sentence_expire = $request->convicted_sentence_expire;
        $case->result_of_appeal = $request->result_of_appeal;
        $case->date_of_reliefe = $request->date_of_reliefe;
        $case->file_closure_date = $request->file_closure_date;
    
        // Save the case
        $case->save();

        if ($request->hasFile('fileUpload')) {
            $uploadedFiles = [];
            foreach ($request->file('fileUpload') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $newFileName = $centralId . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
                $filePath = $file->storeAs('uploads/formal_cases', $newFileName, 'public');
                $uploadedFiles[] = $filePath;
            }
            // You can save the uploaded files here if necessary
            // $case->update(['fileUpload' => json_encode($uploadedFiles)]);
        }
    
        // dd($case);
    
        return redirect()->route('form.index')->with('success', 'Case has been successfully created.');
    }
    

}
