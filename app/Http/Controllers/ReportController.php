<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormalCase;
use Illuminate\Support\Facades\DB;

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
    $data = FormalCase::select('institute', 
                DB::raw('COUNT(*) as total'),
                DB::raw('COUNT(CASE WHEN sex = "Male" AND age >= 18 THEN 1 END) as male'),
                DB::raw('COUNT(CASE WHEN sex = "Female" AND age >= 18 THEN 1 END) as female'),
                DB::raw('COUNT(CASE WHEN sex = "Transgender" AND age >= 18 THEN 1 END) as transgender'),
                DB::raw('COUNT(CASE WHEN age < 18 THEN 1 END) as under_18')
            )
            ->groupBy('institute')
            ->get();
    
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

    // Prepare the response in the required structure
    $response = [
        ['Sl No', 'Nature of Support', 'Total', 'Male', 'Female', 'Transgender Person', 'Under 18'],
    ];

    $slNo = 1;
    foreach ($data as $row) {
        $response[] = [
            $slNo++, 
            "Person Assisted in " . $row->institute, 
            $row->total, 
            $row->male, 
            $row->female, 
            $row->transgender, 
            $row->under_18
        ];
    }

    // Adding Sub Total
    $subTotal = [
        'Sl No' => 'Sub Total',
        'Nature of Support' => '',
        'Total' => $data->sum('total'),
        'Male' => $data->sum('male'),
        'Female' => $data->sum('female'),
        'Transgender Person' => $data->sum('transgender'),
        'Under 18' => $data->sum('under_18')
    ];

    $response[] = $subTotal;

    // Return view with data
    return view('dashboard.report.caseassisted', compact('response'));
}

}
