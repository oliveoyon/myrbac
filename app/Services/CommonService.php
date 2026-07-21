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

        
        $query = FormalCase::select(
            'institute',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN LOWER(TRIM(disability)) = "yes" AND (' . $this->buildCondition() . ') THEN 1 END) as disability'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        // ->where('district_id', $districtId)
        // ->where('pngo_id', $pngoId)
        // ->where('status', $status)
        ->where($whr)
        ->groupBy('institute');

        $data = Auth::user()->applyDistrictPngoScope($query)->get();
        
        return $data;
    }

    private function buildCondition()
    {
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

        $policeFields = [
            'custody_status', 'charges_details', 'arrest_date', 'case_no', 'family_communication_date',
            'legal_representation', 'legal_representation_date', 'collected_vokalatnama_date',
            'collected_case_doc', 'identify_sureties', 'identify_sureties_date', 'witness_communication_date', 'medical_report_date',
            'legal_assistance_date', 'assistance_under_custody_date', 'referral_service', 'referral_service_details',
            'referral_service_date', 'resolved_dispute_date', 'case_resolved_date', 'appoint_lawyer_date', 'release_status',
            'fine_amount', 'release_status_date', 'other_result_details', 'other_result_date', 'application_mode', 'application_mode_date',
            'received_application', 'reference_no', 'type_of_service', 'type_of_service_date'
        ];

        $courtCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $courtFields));
        $prisonCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $prisonFields));
        $policeCondition = implode(' OR ', array_map(fn($field) => "$field IS NOT NULL", $policeFields));

        return "(institute = 'Court' AND ($courtCondition)) 
                OR (institute = 'Prison' AND ($prisonCondition)) 
                OR (institute = 'Police Station' AND ($policeCondition))";
    }

    public function showCaseAssistanceDistrictWise($fromDate = null, $toDate = null)
    {
        // Fetch the district data grouped by district_id
        $query = FormalCase::select(
            'district_id',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN LOWER(TRIM(disability)) = "yes" AND (' . $this->buildCondition() . ') THEN 1 END) as disability'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        // Add condition to filter by status (if required)
        ->where('status', '>', 1) 
        ->groupBy('district_id'); // Group by district only, not by institute

        $this->applyDateRange($query, $fromDate, $toDate);

        $data = Auth::user()->applyDistrictPngoScope($query)->get();

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
                'disability' => $row->disability,
                'total' => $row->total
            ];
        });

        return $resultData;
  
    }

    public function showCaseAssistancePngoWise($fromDate = null, $toDate = null)
    {
        // Fetch the case data grouped by pngo_id
        $query = FormalCase::select(
            'pngo_id',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN LOWER(TRIM(disability)) = "yes" AND (' . $this->buildCondition() . ') THEN 1 END) as disability'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
        ->where('status', '>', 1) 
        ->groupBy('pngo_id'); // Group by pngo_id instead of district_id

        $this->applyDateRange($query, $fromDate, $toDate);

        $data = Auth::user()->applyDistrictPngoScope($query)->get();

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
                'disability' => $row->disability,
                'total' => $row->total
            ];
        });

        return $resultData;
    }

    public function showCaseAssistanceInstitutionWise(array $filters = [])
    {
        $query = FormalCase::select(
            'institute',
            DB::raw('COUNT(CASE WHEN sex = "Male" AND (' . $this->buildCondition() . ') THEN 1 END) as male'),
            DB::raw('COUNT(CASE WHEN sex = "Female" AND (' . $this->buildCondition() . ') THEN 1 END) as female'),
            DB::raw('COUNT(CASE WHEN sex = "Transgender" AND (' . $this->buildCondition() . ') THEN 1 END) as transgender'),
            DB::raw('COUNT(CASE WHEN age < 18 AND (' . $this->buildCondition() . ') THEN 1 END) as under_18'),
            DB::raw('COUNT(CASE WHEN LOWER(TRIM(disability)) = "yes" AND (' . $this->buildCondition() . ') THEN 1 END) as disability'),
            DB::raw('COUNT(CASE WHEN (' . $this->buildCondition() . ') THEN 1 END) as total')
        )
            ->where('status', '>', 1)
            ->when(! empty($filters['district_id']), fn ($query) => $query->where('district_id', $filters['district_id']))
            ->when(! empty($filters['pngo_id']), fn ($query) => $query->where('pngo_id', $filters['pngo_id']))
            ->when(! empty($filters['institute']), fn ($query) => $query->where('institute', $filters['institute']))
            ->groupBy('institute');

        $this->applyDateRange($query, $filters['from_date'] ?? null, $filters['to_date'] ?? null);

        return Auth::user()->applyDistrictPngoScope($query)
            ->get()
            ->map(fn ($row) => [
                'institution_name' => $row->institute ?: 'Unknown',
                'male' => $row->male,
                'female' => $row->female,
                'transgender' => $row->transgender,
                'under_18' => $row->under_18,
                'disability' => $row->disability,
                'total' => $row->total,
            ])
            ->sortByDesc('total')
            ->values();
    }

    private function applyDateRange($query, $fromDate = null, $toDate = null): void
    {
        if ($fromDate) {
            $query->whereDate('interview_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('interview_date', '<=', $toDate);
        }
    }


    



}
