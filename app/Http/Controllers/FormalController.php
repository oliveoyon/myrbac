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
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

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
            'intervention_taken' => 'required|string|max:255',
        ], [
            'full_name.required' => 'The full name is required. Please enter your name.',
            'full_name.string' => 'The full name must be a valid text.',
            'full_name.max' => 'The full name should not exceed 255 characters.',
            
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
                            

        $highestCentralId = $existingCentralIds->central_id;
        preg_match('/(\d+)$/', $highestCentralId, $matches);
        $lastNumber = isset($matches[1]) ? $matches[1] : null;
        $nextNumber = $lastNumber ? $lastNumber + 1 : 1;

        $centralId = strtoupper(substr($districtName, 0, 3)) . '-LA-' . $nextNumber;

        // Create a new FormalCase object
        $case = new FormalCase();
        $case->central_id = $centralId;
        $case->user_id = auth()->id();
        $case->district_id = $districtId;
        $case->pngo_id = $pngoId;
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
        $case->prison_arrest_date = $request->prison_arrest_date;
        $case->surrender_date = $request->surrender_date;
        $case->prison_family_communication = $request->prison_family_communication;
        $case->prison_legal_representation = $request->prison_legal_representation;
        $case->prison_legal_representation_date = $request->prison_legal_representation_date;
        $case->next_court_collection_date = $request->next_court_collection_date;
        $case->prison_next_court_date = $request->prison_next_court_date;
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
        $case->result_description = $request->result_description;
        $case->file_closure_date = $request->file_closure_date;
    
        // Save the case
        $case->save();

        $followup = new FollowUpIntervention();
        $followup->central_id = $case->id;
        $followup->user_id = auth()->id();
        $followup->intervention_taken = $request->intervention_taken;
        $followup->intervention_taken_date = $request->intervention_taken_date;
        $followup->intervention_to_be_taken = $request->intervention_to_be_taken;
        $followup->to_be_taken_date = $request->to_be_taken_date;
        $followup->save();

        if ($request->hasFile('fileUpload')) {
            foreach ($request->file('fileUpload') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $newFileName = $case->id . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
        
                // Store the file
                $file->storeAs('uploads/formal_cases', $newFileName, 'public');
        
                // Save to database
                FileUpload::create([
                    'case_id' => $case->id,
                    'file_name' => $newFileName,
                ]);
            }
        }
    
        // dd($case);
    
        return redirect()->route('form.index')->with('success', 'Case has been successfully created.');
    }
    
    public function editCourtPolicePrison(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'intervention_taken' => 'required|string|max:255',
        ], [
            'full_name.required' => 'The full name is required. Please enter your name.',
            'full_name.string' => 'The full name must be a valid text.',
            'full_name.max' => 'The full name should not exceed 255 characters.',
            
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
        
        $case->status = 2;
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
        $case->prison_arrest_date = $request->prison_arrest_date;
        $case->surrender_date = $request->surrender_date;
        $case->prison_family_communication = $request->prison_family_communication;
        $case->prison_legal_representation = $request->prison_legal_representation;
        $case->prison_legal_representation_date = $request->prison_legal_representation_date;
        $case->next_court_collection_date = $request->next_court_collection_date;
        $case->prison_next_court_date = $request->prison_next_court_date;
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
        $case->result_description = $request->result_description;
        $case->file_closure_date = $request->file_closure_date;

        // Save the case
        $case->save();

        $followup = new FollowUpIntervention();
        $followup->central_id = $case->id;
        $followup->user_id = auth()->id();
        $followup->intervention_taken = $request->intervention_taken;
        $followup->intervention_taken_date = $request->intervention_taken_date;
        $followup->intervention_to_be_taken = $request->intervention_to_be_taken;
        $followup->to_be_taken_date = $request->to_be_taken_date;
        $followup->save();

        if ($request->hasFile('fileUpload')) {
            foreach ($request->file('fileUpload') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $newFileName = $case->id . '_' . pathinfo($originalName, PATHINFO_FILENAME) . '.' . $extension;
        
                // Store the file
                $file->storeAs('uploads/formal_cases', $newFileName, 'public');
        
                // Save to database
                FileUpload::create([
                    'case_id' => $case->id,
                    'file_name' => $newFileName,
                ]);
            }
        }

        // dd($case);

        return redirect()->route('form.index')->with('success', 'Case has been successfully Edited.');
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
            $row['resolved_dispute_date'] = $this->convertToDateFormat($row['resolved_dispute_date']);
            $row['appoint_lawyer_date'] = $this->convertToDateFormat($row['appoint_lawyer_date']);
            $row['release_status_date'] = $this->convertToDateFormat($row['release_status_date']);
            $row['application_mode_date'] = $this->convertToDateFormat($row['application_mode_date']);
            $row['type_of_service_date'] = $this->convertToDateFormat($row['type_of_service_date']);
            $row['collected_case_doc'] = $this->convertToDateFormat($row['collected_case_doc']);
            $row['entry_date'] = $this->convertToDateFormat($row['entry_date']);
            $row['next_court_date'] = $this->convertToDateFormat($row['next_court_date']);
            $row['surrender_date'] = $this->convertToDateFormat($row['surrender_date']);
            $row['prison_family_communication'] = $this->convertToDateFormat($row['prison_family_communication']);
            $row['prison_legal_representation'] = $this->convertToDateFormat($row['prison_legal_representation']);
            $row['prison_legal_representation_date'] = $this->convertToDateFormat($row['prison_legal_representation_date']);
            $row['prison_next_court_date'] = $this->convertToDateFormat($row['prison_next_court_date']);
            $row['witness_communication_prison'] = $this->convertToDateFormat($row['witness_communication_prison']);
            $row['bail_bond_submission'] = $this->convertToDateFormat($row['bail_bond_submission']);
            $row['court_order_communication'] = $this->convertToDateFormat($row['court_order_communication']);
            $row['application_certified_copies'] = $this->convertToDateFormat($row['application_certified_copies']);
            $row['other_legal_assistance_date'] = $this->convertToDateFormat($row['other_legal_assistance_date']);
            $row['released_on'] = $this->convertToDateFormat($row['released_on']);
            $row['released_on_date'] = $this->convertToDateFormat($row['released_on_date']);
            $row['send_to_date'] = $this->convertToDateFormat($row['send_to_date']);
            $row['convicted_sentence_expire'] = $this->convertToDateFormat($row['convicted_sentence_expire']);
            $row['date_of_reliefe'] = $this->convertToDateFormat($row['date_of_reliefe']);
            $row['file_closure_date'] = $this->convertToDateFormat($row['file_closure_date']);

            
            // Insert the row into the database
            FormalCase::create([
                'institute' => $row['institute'],
                'central_id' => $row['central_id'],
                'user_id' => auth()->id(),
                'district_id' => Auth::user()->district_id,
                'pngo_id' => Auth::user()->pngo_id,
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
                'has_guardian' => $row['has_guardian'],
                'guardian_name' => $row['guardian_name'],
                'guardian_phone' => $row['guardian_phone'],
                'guardian_address' => $row['guardian_address'],
                'guardian_relation' => $row['guardian_relation'],
                'guardian_surety' => $row['guardian_surety'],
                'has_lawyer' => $row['has_lawyer'],
                'lawyer_type' => $row['lawyer_type'],
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
                'legal_representation_date' => $row['legal_representation_date'],
                'collected_vokalatnama_date' => $row['collected_vokalatnama_date'],
                'collected_case_doc' => $row['collected_case_doc'],
                'identify_sureties' => $row['identify_sureties'],
                'witness_communication_date' => $row['witness_communication_date'],
                'medical_report_date' => $row['medical_report_date'],
                'legal_assistance_date' => $row['legal_assistance_date'],
                'assistance_under_custody_date' => $row['assistance_under_custody_date'],
                'referral_service' => $row['referral_service'],
                'referral_service_date' => $row['referral_service_date'],
                'resolved_dispute_date' => $row['resolved_dispute_date'],
                'appoint_lawyer_date' => $row['appoint_lawyer_date'],
                'release_status' => $row['release_status'],
                'fine_amount' => $row['fine_amount'],
                'release_status_date' => $row['release_status_date'],
                'application_mode' => $row['application_mode'],
                'application_mode_date' => $row['application_mode_date'],
                'received_application' => $row['received_application'],
                'reference_no' => $row['reference_no'],
                'type_of_service' => $row['type_of_service'],
                'type_of_service_date' => $row['type_of_service_date'],
                'service_description' => $row['service_description'],
                'source_of_interview' => $row['source_of_interview'],
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
                'prison_arrest_date' => $row['prison_arrest_date'],
                'surrender_date' => $row['surrender_date'],
                'released_on' => $row['released_on'],
                'result_of_appeal' => $row['result_of_appeal'],
                'date_of_reliefe' => $row['date_of_reliefe'],
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


    // Function to read Excel
    private function readExcel($file)
    {
        return Excel::toArray([], $file)[0]; // Read the first sheet
    }
    

}
