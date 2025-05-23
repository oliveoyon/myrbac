<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormalCase;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Support\Facades\DB;

class CommontController extends Controller
{
    
    public function showCaseAssistanceData($did=Null, $pid=Null, $st=NULL)
    {

        // $districtId = $request->input('district_id', 1);
        // $pngoId = $request->input('pngo_id', 1);
        // $status = $request->input('status', 1);
        // $districtName = District::where('id', FormalCase::first()->district_id)->value('name');
        // $pngoName = Pngo::where('id', FormalCase::first()->pngo_id)->value('name');

        $districtId = $did;
        $pngoId = $pid;
        $status = $st;
        $districtName = District::where('id', $districtId)->value('name');
        $pngoName = Pngo::where('id', $pngoId)->value('name');

        
        $data = FormalCase::select(
            'institute',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        ->where('district_id', $districtId)
        ->where('pngo_id', $pngoId)
        ->where('status', $status)
        ->groupBy('institute')
        ->get();
        
        return $data;
    }

    private function buildCondition()
    {
        $courtFields = [
            'custody_status', 'charges_details', 'arrest_date', 'case_no', 'family_communication_date',
            'legal_representation', 'legal_representation_date', 'collected_vokalatnama_date',
            'collected_case_doc', 'identify_sureties', 'witness_communication_date', 'medical_report_date',
            'legal_assistance_date', 'assistance_under_custody_date', 'referral_service',
            'referral_service_date', 'resolved_dispute_date', 'appoint_lawyer_date', 'release_status',
            'fine_amount', 'release_status_date', 'application_mode', 'application_mode_date',
            'received_application', 'reference_no', 'type_of_service', 'type_of_service_date', 'service_description'
        ];

        $prisonFields = [
            'source_of_interview', 'prison_reg_no', 'section_no', 'present_court', 'lockup_no', 'entry_date',
            'case_transferred', 'current_court', 'case_status', 'co_offenders', 'next_court_date', 'facts_of_case',
            'imprisonment_condition', 'imprisonment_status', 'special_condition', 'surrender_date',
            'prison_family_communication', 'prison_legal_representation', 'prison_legal_representation_date',
            'next_court_collection_date', 'collected_case_doc_prison', 'identify_sureties_prison_nid',
            'identify_sureties_prison_phone', 'witness_communication_prison', 'bail_bond_submission',
            'court_order_communication', 'application_certified_copies', 'appeal_assistance',
            'ministerial_communication', 'other_legal_assistance', 'other_legal_assistance_date'
        ];

        $policeFields = [
            'custody_status', 'charges_details', 'arrest_date', 'case_no', 'family_communication_date',
            'legal_representation', 'legal_representation_date', 'collected_vokalatnama_date',
            'collected_case_doc', 'identify_sureties', 'witness_communication_date', 'medical_report_date',
            'legal_assistance_date', 'assistance_under_custody_date', 'referral_service',
            'referral_service_date', 'resolved_dispute_date', 'appoint_lawyer_date', 'release_status',
            'fine_amount', 'release_status_date', 'application_mode', 'application_mode_date',
            'received_application', 'reference_no', 'type_of_service', 'type_of_service_date', 'service_description'
        ];

        $courtCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $courtFields));
        $prisonCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $prisonFields));
        $policeCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $policeFields));

        return "(institute = 'Court' AND ($courtCondition)) 
                OR (institute = 'Prison' AND ($prisonCondition)) 
                OR (institute = 'Police Station' AND ($policeCondition))";
    }


    public function showCaseAssistanceData1()
    {
        $status = 1;

        // Fetch all districts and pngo_ids dynamically
        $districts = District::all();
        $pngos = Pngo::all();

        // Fetch the data and group it by district_id and pngo_id
        $data = FormalCase::select(
            'district_id',
            'pngo_id',
            'institute',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        ->where('status', $status)
        ->groupBy('district_id', 'pngo_id', 'institute')
        ->get();

        // Prepare data to display district and PNGO names for each result
        $resultData = $data->map(function ($row) use ($districts, $pngos) {
            $districtName = $districts->where('id', $row->district_id)->first()->name ?? 'Unknown';
            $pngoName = $pngos->where('id', $row->pngo_id)->first()->name ?? 'Unknown';
            
            return [
                'district_name' => $districtName,
                'pngo_name' => $pngoName,
                'institute' => $row->institute,
                'male' => $row->male,
                'female' => $row->female,
                'transgender' => $row->transgender,
                'under_18' => $row->under_18,
                'total' => $row->total
            ];
        });

        dd($resultData);


        // Return the view with the processed data
        return view('dashboard.report.caseassisted', compact('resultData'));
    }



}





        // $data = FormalCase::select('institute', 
        //             DB::raw('COUNT(*) as total'),
        //             DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 THEN 1 END) as male'),
        //             DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 THEN 1 END) as female'),
        //             DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 THEN 1 END) as transgender'),
        //             DB::raw('COUNT(CASE WHEN age < 18 THEN 1 END) as under_18')
        //         )
        //         ->groupBy('institute')
        //         ->get();
        
        // Data only be counted when institute is court, and one of some of column are filled. will be following prison and police
        
        // $data = FormalCase::select('institute', 
        //     DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 AND (institute = "Court" AND (a IS NOT NULL OR b IS NOT NULL)) 
        //                         OR (institute = "Prison" AND (c IS NOT NULL OR d IS NOT NULL)) 
        //                         OR (institute = "Police Station" AND (e IS NOT NULL OR f IS NOT NULL)) THEN 1 END) as male'),
        //     DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 AND (institute = "Court" AND (a IS NOT NULL OR b IS NOT NULL)) 
        //                         OR (institute = "Prison" AND (c IS NOT NULL OR d IS NOT NULL)) 
        //                         OR (institute = "Police Station" AND (e IS NOT NULL OR f IS NOT NULL)) THEN 1 END) as female'),
        //     DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 AND (institute = "Court" AND (a IS NOT NULL OR b IS NOT NULL)) 
        //                         OR (institute = "Prison" AND (c IS NOT NULL OR d IS NOT NULL)) 
        //                         OR (institute = "Police Station" AND (e IS NOT NULL OR f IS NOT NULL)) THEN 1 END) as transgender'),
        //     DB::raw('COUNT(CASE WHEN age < 18 AND (institute = "Court" AND (a IS NOT NULL OR b IS NOT NULL)) 
        //                         OR (institute = "Prison" AND (c IS NOT NULL OR d IS NOT NULL)) 
        //                         OR (institute = "Police Station" AND (e IS NOT NULL OR f IS NOT NULL)) THEN 1 END) as under_18'),
        //     DB::raw('COUNT(CASE WHEN (institute = "Court" AND (a IS NOT NULL OR b IS NOT NULL)) 
        //                         OR (institute = "Prison" AND (c IS NOT NULL OR d IS NOT NULL)) 
        //                         OR (institute = "Police Station" AND (e IS NOT NULL OR f IS NOT NULL)) THEN 1 END) as total')
        //     )->groupBy('institute')->get();

