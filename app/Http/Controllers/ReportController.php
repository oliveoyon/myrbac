<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormalCase;
use App\Models\District;
use App\Models\Pngo;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    public function index()
    {
        $sexCounts = FormalCase::selectRaw('sex, count(*) as count')
            ->whereIn('sex', ['Male', 'Female', 'Transgender'])
            ->groupBy('sex')
            ->get();

        // Access the counts like this:
        $maleCount = $sexCounts->where('sex', 'Male')->first()->count ?? 0;
        $femaleCount = $sexCounts->where('sex', 'Female')->first()->count ?? 0;
        $transgenderCount = $sexCounts->where('sex', 'Transgender')->first()->count ?? 0;

        $below18Count = FormalCase::where('age', '<', 18)->count();


        echo 'Male-'. $maleCount. ' Female-'. $femaleCount. ' Transgender- '. $transgenderCount.' Bellow 18-'. $below18Count;
    }

    public function showCaseAssistanceData()
    {

        // $districtId = $request->input('district_id', 1);
        // $pngoId = $request->input('pngo_id', 1);
        // $status = $request->input('status', 1);
        // $districtName = District::where('id', FormalCase::first()->district_id)->value('name');
        // $pngoName = Pngo::where('id', FormalCase::first()->pngo_id)->value('name');

        $districtId = 1;
        $pngoId = 1;
        $status = 1;
        
        
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
        
        
        return view('dashboard.report.caseassisted', compact('data', 'districtName', 'pngoName'));
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

    public function generatePdf(Request $request)
    {
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $fname = $request->input('fname');
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            'margin_top' => 30,
            'margin_bottom' => 5,
            'margin_header' => 5,
        ]);

        $mpdf->setAutoBottomMargin = 'stretch';

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        $bladeViewPath = 'dashboard.report.common-reports';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        // Save the PDF file in the public folder
        $pdfFilePath = public_path($fname);
        $mpdf->Output($pdfFilePath, 'F');

        // Construct the public URL of the saved PDF
        $pdfUrl = url($fname);

        // Return a JSON response with the PDF URL and a success message
        return response()->json(['pdf_url' => $pdfUrl, 'message' => 'PDF generated successfully']);
    }


    public function district_report()
    {
        $send['districts'] = District::get();
        return view('dashboard.report.district-list', $send);
    }

    public function districtWiseCaselist()
    {
        $districts = District::all();
        $pngos = Pngo::all();
        return view('dashboard.report.case_list', compact('districts', 'pngos'));
    }

    public function districtWiseCaselistDetail(Request $request)
    {
        $whr = ['district_id' => $request->district_id,'pngo_id' => $request->pngo_id,];
        $whr = array_filter($whr);
        $cases = FormalCase::with(['district:id,name', 'pngo:id,name'])->where($whr)->get();
        return response()->json(['cases' => $cases]);
    }

    public function generateForm(Request $request)
    {
        $send['details'] = FormalCase::find($request->id);
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $fname = $request->input('fname');
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
        ]);

        $mpdf->setAutoBottomMargin = 'stretch';

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        $bladeViewPath = 'dashboard.report.formtest';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        // Save the PDF file in the public folder
        $pdfFilePath = public_path($fname);
        $mpdf->Output($pdfFilePath, 'F');

        // Construct the public URL of the saved PDF
        $pdfUrl = url($fname);

        // Return a JSON response with the PDF URL and a success message
        return response()->json(['pdf_url' => $pdfUrl, 'message' => 'PDF generated successfully']);
    }

    public function getFormalCaseStats()
    {
        $fields = [
            'family_communication_date',
            'legal_representation',
            'legal_representation_date',
            'collected_vokalatnama_date',
            'collected_case_doc',
            'identify_sureties',
            'witness_communication_date',
            'medical_report_date',
            'legal_assistance_date',
            'assistance_under_custody_date',
            'referral_service',
            'referral_service_date',
        ];

        $districtId = 1; // Set your district ID
        $pngoId = 1; // Set your PNGO ID

        $results = collect($fields)->map(function ($field) use ($districtId, $pngoId) {
            return FormalCase::selectRaw("
                '$field' AS field,
                SUM(CASE WHEN sex = 'Male' AND age >= 18 THEN 1 ELSE 0 END) AS adult_males,
                SUM(CASE WHEN sex = 'Female' AND age >= 18 THEN 1 ELSE 0 END) AS adult_females,
                SUM(CASE WHEN sex = 'Transgender' AND age >= 18 THEN 1 ELSE 0 END) AS adult_transgenders,
                SUM(CASE WHEN age < 18 THEN 1 ELSE 0 END) AS under_18,
                COUNT(*) AS total
            ")
            ->whereNotNull($field)
            // ->where('district_id', $districtId)
            // ->where('pngo_id', $pngoId)
            ->first();
        });

        dd($results);

        return response()->json($results);
    }
    
    public function customReport()
    {
        $districts = District::all();
        $pngos = Pngo::all();
        $fields = include(app_path('Services/DbFields.php'));
        return view('dashboard.report.custom-report', compact('fields', 'districts', 'pngos'));
    }

    public function generateCustomReport(Request $request)
    {
        $fields = $request->input('fields', []); // 'fields' is the name of the checkbox array in your form
        $flatFields = collect($fields)->flatMap(function ($fieldGroup) {
            return is_array($fieldGroup) ? $fieldGroup : [$fieldGroup];
        })->all(); 

        if (empty($flatFields)) {
            return redirect()->back()->with('error', 'No fields selected');
        }

        // Fix: Define $whr properly
        $whr = [
            'district_id' => $request->district_id,
            'pngo_id' => $request->pngo_id,
        ];
        $whr = array_filter($whr); // Remove null/empty values

        // Fix: Pass $whr inside the closure
        $results = collect($flatFields)->map(function ($field) use ($whr) {
            return FormalCase::selectRaw("
                '$field' AS field,
                SUM(CASE WHEN sex = 'Male' AND age >= 18 THEN 1 ELSE 0 END) AS adult_males,
                SUM(CASE WHEN sex = 'Female' AND age >= 18 THEN 1 ELSE 0 END) AS adult_females,
                SUM(CASE WHEN sex = 'Transgender' AND age >= 18 THEN 1 ELSE 0 END) AS adult_transgenders,
                SUM(CASE WHEN age < 18 THEN 1 ELSE 0 END) AS under_18,
                COUNT(*) AS total
            ")
            ->whereNotNull($field) 
            ->where($whr)  // Correctly passing $whr
            ->first();
        });

        // Load field names
        $allfields = include(app_path('Services/DbFields.php'));
        $flattenedFields = [];

        foreach ($allfields as $category) {
            $flattenedFields = array_merge($flattenedFields, $category);
        }

        return view('dashboard.report.result-custom-report', compact('results', 'flattenedFields'));
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

