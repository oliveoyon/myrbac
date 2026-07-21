<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormalCase;
use App\Models\District;
use App\Models\FollowUpIntervention;
use App\Models\Pngo;
use Illuminate\Support\Facades\DB;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use App\Services\CommonService;
use App\Exports\FormalCaseExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

class ReportController extends Controller
{
    public function generatePdf(Request $request)
    {
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $fname = $this->pdfFileName($request->input('fname', 'report.pdf'));
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $mpdfTempDir = storage_path('app/mpdf');
        if (!is_dir($mpdfTempDir)) {
            mkdir($mpdfTempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            'margin_top' => 30,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'bangla' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'bangla',
            'cacheCleanupInterval' => false,
            'tempDir' => $mpdfTempDir,
        ]);

        $mpdf->setAutoBottomMargin = 'stretch';

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        $bladeViewPath = 'dashboard.report.common-reports';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        if ($request->boolean('inline')) {
            return response($mpdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fname . '"',
            ]);
        }

        // Save the PDF file in the public folder
        $pdfFilePath = public_path($fname);
        $pdfDirectory = dirname($pdfFilePath);
        if (!is_dir($pdfDirectory)) {
            mkdir($pdfDirectory, 0775, true);
        }
        $mpdf->Output($pdfFilePath, 'F');

        // Construct the public URL of the saved PDF
        $pdfUrl = url($fname);

        // Return a JSON response with the PDF URL and a success message
        return response()->json(['pdf_url' => $pdfUrl, 'message' => 'PDF generated successfully']);
    }

    public function generatePdfChart(Request $request)
    {
        // Get the input data
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $fname = $this->pdfFileName($request->input('fname', 'report.pdf'));
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $mpdfTempDir = storage_path('app/mpdf');
        if (!is_dir($mpdfTempDir)) {
            mkdir($mpdfTempDir, 0775, true);
        }

        // Get the chart image data
        $chartImage = $request->input('chart_image');

        // Decode the base64 chart image
        if ($chartImage) {
            list($type, $data) = explode(';', $chartImage);
            list(, $data) = explode(',', $data);
            $decodedData = base64_decode($data);

            // Save the chart image to a temporary file
            $chartDir = public_path('images');
            if (!is_dir($chartDir)) {
                mkdir($chartDir, 0775, true);
            }
            $chartImagePath = public_path('images/chart.png');
            file_put_contents($chartImagePath, $decodedData);
            $send['chartImagePath'] = $chartImagePath;
        }

        // Initialize mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            'margin_top' => 30,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'bangla' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'bangla',
            'cacheCleanupInterval' => false,
            'tempDir' => $mpdfTempDir,
        ]);

        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        // Render the HTML for the PDF from the Blade view
        $bladeViewPath = 'dashboard.report.common-reports-chart';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        if ($request->boolean('inline')) {
            return response($mpdf->Output('', 'S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fname . '"',
            ]);
        }

        // Save the PDF file in the public folder
        $pdfFilePath = public_path($fname);
        $pdfDirectory = dirname($pdfFilePath);
        if (!is_dir($pdfDirectory)) {
            mkdir($pdfDirectory, 0775, true);
        }
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
        [$districts, $pngos] = $this->allowedDistrictsAndPngos();
        return view('dashboard.report.case_list', compact('districts', 'pngos'));
    }

    public function districtWiseCaselistDetaild(Request $request)
    {
        $whr = ['district_id' => $request->district_id, 'pngo_id' => $request->pngo_id,];
        $whr = array_filter($whr);
        $cases = Auth::user()
            ->applyDistrictPngoScope(FormalCase::with(['district:id,name', 'pngo:id,name'])->where($whr))
            ->get();
        return response()->json(['cases' => $cases]);
    }

    public function districtWiseCaselistDetail(Request $request)
    {
        $whr = [
            'institute' => $request->institute,
            'district_id' => $request->district_id,
            'pngo_id' => $request->pngo_id,
            'status' => $request->status,
        ];

        $whr = array_filter($whr);
        $cases = FormalCase::with(['district:id,name', 'pngo:id,name', 'creator:id,name,full_name'])
            ->withCount([
                'fileUploads',
                'messageThreads as case_message_threads_count',
                'caseMessages as unread_case_messages_count' => function ($query) {
                    $query
                        ->where('receiver_id', Auth::id())
                        ->whereNull('read_at');
                },
            ])
            ->where($whr);

        if (Auth::user()->can('View Deleted Formal Cases')) {
            if ($request->deleted_status === 'deleted') {
                $cases->onlyTrashed();
            } elseif ($request->deleted_status === 'all') {
                $cases->withTrashed();
            }
        }

        Auth::user()->applyDistrictPngoScope($cases);

        if ($request->filled('from_date') && $request->filled('to_date')) {
            $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->from_date)->startOfDay();
            $toDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->to_date)->endOfDay();

            // Apply the date filter
            $cases->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $cases1 = $cases->latest('id')->get();
        return response()->json(['cases' => $cases1]);
    }

    public function generateForm(Request $request)
    {
        $send['details'] = Auth::user()->can('View Deleted Formal Cases')
            ? FormalCase::withTrashed()->find($request->id)
            : FormalCase::find($request->id);
        abort_if(! $send['details'] || ! Auth::user()->canAccessDistrictPngo($send['details']->district_id, $send['details']->pngo_id), 403);
        $send['followups'] = FollowUpIntervention::where('central_id', $request->id)->get();
        // dd($send['followups']);
        $send['data'] = $request->input('pdf_data');
        $send['title'] = $request->input('title');
        $fname = $this->pdfFileName($request->input('fname', 'report.pdf'));
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $mpdfTempDir = storage_path('app/mpdf');
        if (!is_dir($mpdfTempDir)) {
            mkdir($mpdfTempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $request->input('orientation'),
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_header' => 5,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'bangla' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'bangla',
            'cacheCleanupInterval' => false,
            'tempDir' => $mpdfTempDir,
        ]);

        $mpdf->setAutoBottomMargin = 'stretch';

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        $bladeViewPath = 'dashboard.report.formtest';
        $html = view($bladeViewPath, $send)->render();
        $mpdf->WriteHTML($html);

        // Save the PDF file in the public folder
        $pdfFilePath = public_path($fname);
        $pdfDirectory = dirname($pdfFilePath);
        if (!is_dir($pdfDirectory)) {
            mkdir($pdfDirectory, 0775, true);
        }
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

        return response()->json($results);
    }

    public function customReport()
    {
        [$districts, $pngos] = $this->allowedDistrictsAndPngos();
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

        $whr = [
            'district_id' => $request->district_id,
            'pngo_id' => $request->pngo_id,
            'institute' => $request->institute,
            'application_mode' => $request->application_mode,
        ];
        $whr = array_filter($whr);

        $appliedFilters = [];

        if ($request->filled('district_id')) {
            $districtName = District::whereKey($request->district_id)->value('name');
            $appliedFilters['District'] = $districtName ?: $request->district_id;
        }

        if ($request->filled('pngo_id')) {
            $pngoName = Pngo::whereKey($request->pngo_id)->value('name');
            $appliedFilters['PNGO'] = $pngoName ?: $request->pngo_id;
        }

        if ($request->filled('institute')) {
            $appliedFilters['Institute'] = $request->institute;
        }

        if ($request->filled('application_mode')) {
            $appliedFilters['Application Mode'] = $request->application_mode;
        }

        // Fix: Pass $whr inside the closure
        $user = Auth::user();
        $results = collect($flatFields)->map(function ($field) use ($whr, $user) {
            $query = FormalCase::selectRaw("
                '$field' AS field,
                SUM(CASE WHEN sex = 'Male' AND age >= 18 THEN 1 ELSE 0 END) AS adult_males,
                SUM(CASE WHEN sex = 'Female' AND age >= 18 THEN 1 ELSE 0 END) AS adult_females,
                SUM(CASE WHEN sex = 'Transgender' AND age >= 18 THEN 1 ELSE 0 END) AS adult_transgenders,
                SUM(CASE WHEN age < 18 THEN 1 ELSE 0 END) AS under_18,
                COUNT(*) AS total
            ")
                ->whereNotNull($field)
                ->where($whr);

            return $user->applyDistrictPngoScope($query)->first();
        });

        // Load field names
        $allfields = include(app_path('Services/DbFields.php'));
        $flattenedFields = [];

        foreach ($allfields as $category) {
            $flattenedFields = array_merge($flattenedFields, $category);
        }

        return view('dashboard.report.result-custom-report', compact('results', 'flattenedFields', 'appliedFilters'));
    }

    public function districtSummery()
    {
        $filters = $this->summaryDateFilters();
        $commonService = new CommonService();
        $districtWise = $commonService->showCaseAssistanceDistrictWise($filters['from_date'], $filters['to_date']);
        return view('dashboard.report.district-summery', compact('districtWise', 'filters'));
    }

    public function districtSummeryPdf()
    {
        $filters = $this->summaryDateFilters();
        $commonService = new CommonService();
        $districtWise = $commonService->showCaseAssistanceDistrictWise($filters['from_date'], $filters['to_date']);

        $mpdf = $this->reportMpdf('P');
        $html = view('dashboard.report.summary-report-pdf', [
            'title' => 'District Wise Summery',
            'nameColumn' => 'District',
            'nameKey' => 'district_name',
            'rows' => $districtWise,
            'filters' => $filters,
        ])->render();

        $mpdf->WriteHTML($html);

        return $this->inlinePdfResponse($mpdf, 'district-wise-summery.pdf');
    }

    public function pngoSummery()
    {
        $filters = $this->summaryDateFilters();
        $commonService = new CommonService();
        $pngoWise = $this->mergePngoSummaryByName($commonService->showCaseAssistancePngoWise($filters['from_date'], $filters['to_date']));
        return view('dashboard.report.pngo-summery', compact('pngoWise', 'filters'));
    }

    public function pngoSummeryPdf()
    {
        $filters = $this->summaryDateFilters();
        $commonService = new CommonService();
        $pngoWise = $this->mergePngoSummaryByName($commonService->showCaseAssistancePngoWise($filters['from_date'], $filters['to_date']));

        $mpdf = $this->reportMpdf('P');
        $html = view('dashboard.report.summary-report-pdf', [
            'title' => 'PNGO Wise Summery',
            'nameColumn' => 'PNGO',
            'nameKey' => 'pngo_name',
            'rows' => $pngoWise,
            'filters' => $filters,
        ])->render();

        $mpdf->WriteHTML($html);

        return $this->inlinePdfResponse($mpdf, 'pngo-wise-summery.pdf');
    }

    private function mergePngoSummaryByName($pngoWise)
    {
        return collect($pngoWise)
            ->groupBy(fn ($row) => trim(mb_strtolower($row['pngo_name'] ?? 'Unknown')))
            ->map(function ($rows) {
                $firstRow = $rows->first();

                return [
                    'pngo_name' => $firstRow['pngo_name'] ?? 'Unknown',
                    'male' => $rows->sum('male'),
                    'female' => $rows->sum('female'),
                    'transgender' => $rows->sum('transgender'),
                    'under_18' => $rows->sum('under_18'),
                    'total' => $rows->sum('total'),
                ];
            })
            ->sortByDesc('total')
            ->values();
    }

    public function institutionWiseReport(Request $request)
    {
        $filters = $this->institutionWiseFilters($request);
        [$districts, $pngos] = $this->allowedDistrictsAndPngos();
        $institutionOptions = $this->institutionOptions();
        $rows = (new CommonService())->showCaseAssistanceInstitutionWise($filters);
        $appliedFilters = $this->institutionWiseAppliedFilters($filters);

        return view('dashboard.report.institution-wise-report', compact(
            'rows',
            'filters',
            'districts',
            'pngos',
            'institutionOptions',
            'appliedFilters'
        ));
    }

    public function institutionWiseReportPdf(Request $request)
    {
        $filters = $this->institutionWiseFilters($request);
        $rows = (new CommonService())->showCaseAssistanceInstitutionWise($filters);
        $appliedFilters = $this->institutionWiseAppliedFilters($filters);

        $mpdf = $this->reportMpdf('P');
        $html = view('dashboard.report.institution-wise-report-pdf', [
            'title' => 'Institution Wise Report',
            'rows' => $rows,
            'appliedFilters' => $appliedFilters,
        ])->render();

        $mpdf->WriteHTML($html);

        return $this->inlinePdfResponse($mpdf, 'institution-wise-report.pdf');
    }

    private function summaryDateFilters(): array
    {
        request()->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        return [
            'from_date' => request('from_date'),
            'to_date' => request('to_date'),
        ];
    }

    private function institutionWiseFilters(Request $request): array
    {
        $validated = $request->validate([
            'district_id' => 'nullable|integer|exists:districts,id',
            'pngo_id' => 'nullable|integer|exists:pngos,id',
            'institute' => 'nullable|in:Court,Police Station,Prison',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
        ]);

        if (! empty($validated['pngo_id'])) {
            $pngo = Pngo::find($validated['pngo_id']);

            if (! empty($validated['district_id']) && $pngo && (int) $pngo->district_id !== (int) $validated['district_id']) {
                abort(403);
            }
        }

        $districtIds = Auth::user()->accessibleDistrictIds();
        $pngoIds = Auth::user()->accessiblePngoIds();

        if (! empty($validated['district_id']) && is_array($districtIds)) {
            abort_if(! in_array((int) $validated['district_id'], array_map('intval', $districtIds), true), 403);
        }

        if (! empty($validated['pngo_id']) && is_array($pngoIds)) {
            abort_if(! in_array((int) $validated['pngo_id'], array_map('intval', $pngoIds), true), 403);
        }

        return [
            'district_id' => $validated['district_id'] ?? null,
            'pngo_id' => $validated['pngo_id'] ?? null,
            'institute' => $validated['institute'] ?? null,
            'from_date' => $validated['from_date'] ?? null,
            'to_date' => $validated['to_date'] ?? null,
        ];
    }

    private function institutionWiseAppliedFilters(array $filters): array
    {
        $applied = [];

        if (! empty($filters['district_id'])) {
            $applied['District'] = District::where('id', $filters['district_id'])->value('name') ?: 'Unknown';
        }

        if (! empty($filters['pngo_id'])) {
            $applied['PNGO'] = Pngo::where('id', $filters['pngo_id'])->value('name') ?: 'Unknown';
        }

        if (! empty($filters['institute'])) {
            $applied['Institution'] = $filters['institute'];
        }

        if (! empty($filters['from_date'])) {
            $applied['From Date'] = \Carbon\Carbon::parse($filters['from_date'])->format('j M, Y');
        }

        if (! empty($filters['to_date'])) {
            $applied['To Date'] = \Carbon\Carbon::parse($filters['to_date'])->format('j M, Y');
        }

        return $applied;
    }

    private function institutionOptions(): array
    {
        return ['Court', 'Police Station', 'Prison'];
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $cases = FormalCase::with(['district:id,name', 'pngo:id,name'])
            ->where(function ($q) use ($query) {
                $q->where('central_id', 'like', "%{$query}%")
                    ->orWhere('full_name', 'like', "%{$query}%")
                    ->orWhere('phone_number', 'like', "%{$query}%");
            });

        $cases = Auth::user()->applyDistrictPngoScope($cases)->get();
        return view('dashboard.report.search-list', compact('cases'));
        // return response()->json(['cases' => $cases1]);
    }

    public function exportExcel()
    {
        // Log the export action
        LogService::logAction('Formal Cases Exported', [
            'exported_by' => auth()->user()->name,
            'exported_at' => now(),
        ]);

        // Proceed with the Excel download
        return Excel::download(new FormalCaseExport, 'formal_cases.xlsx');
    }

    private function allowedDistrictsAndPngos(): array
    {
        $user = Auth::user();
        $districtIds = $user->accessibleDistrictIds();
        $pngoIds = $user->accessiblePngoIds();

        $districts = District::query()
            ->when(is_array($districtIds), fn ($query) => $query->whereIn('id', $districtIds))
            ->orderBy('name')
            ->get();

        $pngos = Pngo::with('district:id,name')
            ->when(is_array($pngoIds), fn ($query) => $query->whereIn('id', $pngoIds))
            ->orderBy('name')
            ->get();

        return [$districts, $pngos];
    }

    private function pdfFileName(string $fileName): string
    {
        $fileName = trim(str_replace(['\\', '/'], '-', $fileName));

        if ($fileName === '') {
            $fileName = 'report.pdf';
        }

        return str_ends_with(strtolower($fileName), '.pdf') ? $fileName : $fileName . '.pdf';
    }

    private function reportMpdf(string $orientation = 'P'): Mpdf
    {
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];
        $mpdfTempDir = storage_path('app/mpdf');

        if (! is_dir($mpdfTempDir)) {
            mkdir($mpdfTempDir, 0775, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => $orientation,
            'margin_top' => 10,
            'margin_bottom' => 8,
            'margin_left' => 8,
            'margin_right' => 8,
            'fontDir' => array_merge($fontDirs, [
                resource_path('fonts'),
            ]),
            'fontdata' => $fontData + [
                'bangla' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
                'solaimanlipi' => [
                    'R' => 'SolaimanLipi.ttf',
                    'useOTL' => 0xFF,
                ],
            ],
            'default_font' => 'bangla',
            'cacheCleanupInterval' => false,
            'tempDir' => $mpdfTempDir,
        ]);

        $mpdf->SetAutoPageBreak(true);
        $mpdf->SetAuthor('GIZ');

        return $mpdf;
    }

    private function inlinePdfResponse(Mpdf $mpdf, string $fileName)
    {
        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $this->pdfFileName($fileName) . '"',
        ]);
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
