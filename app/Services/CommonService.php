<?php

// app/Services/CommonService.php

namespace App\Services;
use Illuminate\Http\Request;
use App\Models\FormalCase;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CommonService
{
    public function showCaseAssistanceData($did=Null, $pid=Null, $st=NULL)
    {

       

        $filter = ['district_id' => $did, 'pngo_id' => $pid, 'status' => $st];
        $whr = array_filter($filter);

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
        // ->where('district_id', $districtId)
        // ->where('pngo_id', $pngoId)
        // ->where('status', $status)
        ->where($whr)
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

    public function showCaseAssistanceDistrictWise()
    {
        $districtId = Auth::user()->district_id;
        $pngoId = Auth::user()->pngo_id;
        $whr = ['district_id' => $districtId,'pngo_id' => $pngoId];
        $whr = array_filter($whr);

        // Fetch the district data grouped by district_id
        $data = FormalCase::select(
            'district_id',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        // Add condition to filter by status (if required)
        ->where('status', '>', 1) 
        ->where($whr)
        ->groupBy('district_id') // Group by district only, not by institute
        ->get();

        // Fetch district names based on district_id
        $districts = District::all();

        // Map the data with district names
        $resultData = $data->map(function ($row) use ($districts) {
            $districtName = $districts->where('id', $row->district_id)->first()->name ?? 'Unknown';

            return [
                'district_name' => $districtName,
                'male' => $row->male,
                'female' => $row->female,
                'transgender' => $row->transgender,
                'under_18' => $row->under_18,
                'total' => $row->total
            ];
        });

        return $resultData;
  
    }

    public function showCaseAssistancePngoWise()
    {
        $districtId = Auth::user()->district_id;
        $pngoId = Auth::user()->pngo_id;
        $whr = ['district_id' => $districtId,'pngo_id' => $pngoId];
        $whr = array_filter($whr);

        // Fetch the case data grouped by pngo_id
        $data = FormalCase::select(
            'pngo_id',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        ->where('status', '>', 1) 
        ->where($whr)
        ->groupBy('pngo_id') // Group by pngo_id instead of district_id
        ->get();

        // Fetch PNGO names based on pngo_id
        $pngos = Pngo::all(); 

        // Map the data with PNGO names
        $resultData = $data->map(function ($row) use ($pngos) {
            $pngoName = $pngos->where('id', $row->pngo_id)->first()->name ?? 'Unknown';

            return [
                'pngo_name' => $pngoName,
                'male' => $row->male,
                'female' => $row->female,
                'transgender' => $row->transgender,
                'under_18' => $row->under_18,
                'total' => $row->total
            ];
        });

        return $resultData;
    }


    



}
