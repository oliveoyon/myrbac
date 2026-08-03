<?php

// DashboardController.php

namespace App\Http\Controllers;
use App\Models\FormalCase;
use App\Models\District;
use App\Models\FollowUpIntervention;
use App\Models\LsidRegister;
use App\Models\Pngo;
use App\Models\Todo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\CommonService;
use App\Services\LogService;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $commonService = new CommonService();
        // $data = $commonService->showCaseAssistanceData(Null,Null,Null);
        $districtWise = $commonService->showCaseAssistanceDistrictWise();
        $pngoWise = $commonService->showCaseAssistancePngoWise();
        $todoSummary = $this->dashboardTodoSummary();
        $lsidTotal = Auth::user()->applyDistrictPngoScope(LsidRegister::query())->count();
        // dd($pngoWise);

        return view('dashboard.admin.dashboard', compact('districtWise', 'pngoWise', 'todoSummary', 'lsidTotal'));
    }

    public function dashboardTwo()
    {
        $commonService = new CommonService();
        $districtWise = collect($commonService->showCaseAssistanceDistrictWise());
        $pngoWise = collect($commonService->showCaseAssistancePngoWise());
        $institutionWise = collect($commonService->showCaseAssistanceInstitutionWise());
        $todoSummary = $this->dashboardTodoSummary();

        $formalTotal = (int) $districtWise->sum('total');
        $maleTotal = (int) $districtWise->sum('male');
        $femaleTotal = (int) $districtWise->sum('female');
        $transgenderTotal = (int) $districtWise->sum('transgender');
        $under18Total = (int) $districtWise->sum('under_18');
        $disabilityTotal = (int) $districtWise->sum('disability');
        $lsidTotal = (int) Auth::user()->applyDistrictPngoScope(LsidRegister::query())->count();

        $statusRows = Auth::user()->applyDistrictPngoScope(
            FormalCase::query()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
        )->pluck('total', 'status');

        $statusSummary = [
            'submitted' => (int) ($statusRows[FormalCase::STATUS_SUBMITTED] ?? 0),
            'dpo_verified' => (int) ($statusRows[FormalCase::STATUS_DPO_VERIFIED] ?? 0),
            'mneo_verified' => (int) ($statusRows[FormalCase::STATUS_MNEO_VERIFIED] ?? 0),
        ];
        $caseRecordTotal = array_sum($statusSummary);
        $verifiedRecordTotal = $statusSummary['dpo_verified'] + $statusSummary['mneo_verified'];

        $monthlyTrend = collect(range(5, 0))
            ->map(function ($offset) use ($commonService) {
                $month = now()->copy()->subMonths($offset)->startOfMonth();
                $fromDate = $month->toDateString();
                $toDate = $month->copy()->endOfMonth()->toDateString();
                $institutionRows = collect($commonService->showCaseAssistanceInstitutionWise([
                    'from_date' => $fromDate,
                    'to_date' => $toDate,
                ]));

                return [
                    'label' => $month->format('M Y'),
                    'formal' => (int) $institutionRows->sum('total'),
                    'court' => (int) ($institutionRows->firstWhere('institution_name', 'Court')['total'] ?? 0),
                    'police_station' => (int) ($institutionRows->firstWhere('institution_name', 'Police Station')['total'] ?? 0),
                    'prison' => (int) ($institutionRows->firstWhere('institution_name', 'Prison')['total'] ?? 0),
                    'lsid' => (int) Auth::user()->applyDistrictPngoScope(
                        LsidRegister::query()
                            ->whereDate('service_date', '>=', $fromDate)
                            ->whereDate('service_date', '<=', $toDate)
                    )->count(),
                ];
            })
            ->values();

        $topDistricts = $districtWise
            ->sortByDesc('total')
            ->take(6)
            ->values();

        $topPngos = $pngoWise
            ->groupBy(fn ($row) => trim(mb_strtolower($row['pngo_name'] ?? 'Unknown')))
            ->map(function ($rows) {
                $first = $rows->first();

                return [
                    'pngo_name' => $first['pngo_name'] ?? 'Unknown',
                    'total' => (int) $rows->sum('total'),
                    'male' => (int) $rows->sum('male'),
                    'female' => (int) $rows->sum('female'),
                    'transgender' => (int) $rows->sum('transgender'),
                    'under_18' => (int) $rows->sum('under_18'),
                    'disability' => (int) $rows->sum('disability'),
                ];
            })
            ->sortByDesc('total')
            ->take(6)
            ->values();

        $ageRows = Auth::user()->applyDistrictPngoScope(
            FormalCase::query()
                ->selectRaw("
                    SUM(CASE WHEN age IS NOT NULL AND age < 18 THEN 1 ELSE 0 END) AS under_18,
                    SUM(CASE WHEN age BETWEEN 18 AND 24 THEN 1 ELSE 0 END) AS age_18_24,
                    SUM(CASE WHEN age BETWEEN 25 AND 34 THEN 1 ELSE 0 END) AS age_25_34,
                    SUM(CASE WHEN age BETWEEN 35 AND 44 THEN 1 ELSE 0 END) AS age_35_44,
                    SUM(CASE WHEN age >= 45 THEN 1 ELSE 0 END) AS age_45_plus,
                    SUM(CASE WHEN age IS NULL OR age = '' THEN 1 ELSE 0 END) AS unknown_age
                ")
                ->where('status', '>', 1)
                ->whereRaw($commonService->interventionConditionSql())
        )->first();

        $interventionSignals = $this->dashboardInterventionSignals($commonService);
        $lsidServiceTypes = $this->dashboardLsidServiceTypes();
        $dataQuality = $this->dashboardDataQuality($commonService);
        $pendingVerification = $this->dashboardPendingVerification();
        $followUpWorkload = $this->dashboardFollowUpWorkload();
        $duplicateRisk = $this->dashboardDuplicateRisk();

        $dashboardData = [
            'kpis' => [
                'formal_total' => $formalTotal,
                'lsid_total' => $lsidTotal,
                'under_18' => $under18Total,
                'disability' => $disabilityTotal,
                'today_tasks' => $todoSummary['today_total'],
                'case_record_total' => $caseRecordTotal,
                'verified_record_total' => $verifiedRecordTotal,
                'verification_rate' => $caseRecordTotal ? round(($verifiedRecordTotal / $caseRecordTotal) * 100, 1) : 0,
                'mneo_completion_rate' => $caseRecordTotal ? round(($statusSummary['mneo_verified'] / $caseRecordTotal) * 100, 1) : 0,
                'data_quality_score' => $dataQuality['score'],
                'pending_dpo' => (int) $pendingVerification->sum('submitted'),
                'pending_mneo' => (int) $pendingVerification->sum('dpo_verified'),
                'overdue_followups' => $followUpWorkload['overdue'],
                'duplicate_phone_groups' => $duplicateRisk['phone_groups'],
            ],
            'sex' => [
                'labels' => ['Male', 'Female', 'Transgender'],
                'values' => [$maleTotal, $femaleTotal, $transgenderTotal],
            ],
            'status' => [
                'labels' => ['Submitted', 'DPO Verified', 'M&EO Verified'],
                'values' => [
                    $statusSummary['submitted'],
                    $statusSummary['dpo_verified'],
                    $statusSummary['mneo_verified'],
                ],
            ],
            'institution' => [
                'labels' => $institutionWise->pluck('institution_name')->values(),
                'values' => $institutionWise->pluck('total')->map(fn ($value) => (int) $value)->values(),
            ],
            'service_balance' => [
                'labels' => ['Formal Intervention', 'LSID Register'],
                'values' => [$formalTotal, $lsidTotal],
            ],
            'trend' => [
                'labels' => $monthlyTrend->pluck('label'),
                'formal' => $monthlyTrend->pluck('formal'),
                'lsid' => $monthlyTrend->pluck('lsid'),
                'court' => $monthlyTrend->pluck('court'),
                'police_station' => $monthlyTrend->pluck('police_station'),
                'prison' => $monthlyTrend->pluck('prison'),
            ],
            'age' => [
                'labels' => ['Under 18', '18-24', '25-34', '35-44', '45+', 'Unknown'],
                'values' => [
                    (int) ($ageRows->under_18 ?? 0),
                    (int) ($ageRows->age_18_24 ?? 0),
                    (int) ($ageRows->age_25_34 ?? 0),
                    (int) ($ageRows->age_35_44 ?? 0),
                    (int) ($ageRows->age_45_plus ?? 0),
                    (int) ($ageRows->unknown_age ?? 0),
                ],
            ],
            'disability_share' => [
                'labels' => ['Disability', 'No/Not Recorded'],
                'values' => [$disabilityTotal, max($formalTotal - $disabilityTotal, 0)],
            ],
            'intervention_signals' => [
                'labels' => $interventionSignals->pluck('label'),
                'values' => $interventionSignals->pluck('total'),
            ],
            'lsid_services' => [
                'labels' => $lsidServiceTypes->pluck('label'),
                'values' => $lsidServiceTypes->pluck('total'),
            ],
            'districts' => [
                'labels' => $topDistricts->pluck('district_name')->values(),
                'values' => $topDistricts->pluck('total')->map(fn ($value) => (int) $value)->values(),
            ],
            'pngos' => [
                'labels' => $topPngos->pluck('pngo_name')->values(),
                'values' => $topPngos->pluck('total')->map(fn ($value) => (int) $value)->values(),
            ],
            'data_quality' => [
                'score' => $dataQuality['score'],
                'labels' => $dataQuality['missing_fields']->pluck('label'),
                'values' => $dataQuality['missing_fields']->pluck('total'),
            ],
            'pending_verification' => [
                'labels' => $pendingVerification->pluck('district_name'),
                'submitted' => $pendingVerification->pluck('submitted'),
                'dpo_verified' => $pendingVerification->pluck('dpo_verified'),
            ],
            'followups' => [
                'labels' => ['Overdue', 'Due Today', 'Next 7 Days'],
                'values' => [
                    $followUpWorkload['overdue'],
                    $followUpWorkload['due_today'],
                    $followUpWorkload['next_7_days'],
                ],
            ],
            'duplicate_risk' => [
                'labels' => ['Phone Duplicate Groups', 'Affected Records'],
                'values' => [$duplicateRisk['phone_groups'], $duplicateRisk['phone_records']],
            ],
        ];

        return view('dashboard.admin.dashboard-two', compact(
            'dashboardData',
            'topDistricts',
            'topPngos',
            'institutionWise',
            'todoSummary',
            'dataQuality',
            'pendingVerification',
            'followUpWorkload',
            'duplicateRisk'
        ));
    }

    private function dashboardDataQuality(CommonService $commonService): array
    {
        $fields = [
            'interview_date' => 'Interview Date',
            'institute' => 'Institution',
            'sex' => 'Sex',
            'age' => 'Age',
            'disability' => 'Disability',
            'district_id' => 'District',
            'pngo_id' => 'PNGO',
            'phone_number' => 'Mobile Number',
        ];

        $baseQuery = FormalCase::query()
            ->where('status', '>', 1)
            ->whereRaw($commonService->interventionConditionSql());

        $totalRecords = (int) Auth::user()->applyDistrictPngoScope(clone $baseQuery)->count();

        if (! $totalRecords) {
            return [
                'score' => 0,
                'total_records' => 0,
                'missing_fields' => collect(),
            ];
        }

        $selectSql = collect($fields)
            ->map(function ($label, $field) {
                return "SUM(CASE WHEN {$field} IS NULL OR CAST({$field} AS CHAR) = '' THEN 1 ELSE 0 END) AS {$field}";
            })
            ->implode(', ');

        $row = Auth::user()->applyDistrictPngoScope(
            (clone $baseQuery)->selectRaw($selectSql)
        )->first();

        $missingFields = collect($fields)
            ->map(fn ($label, $field) => [
                'field' => $field,
                'label' => $label,
                'total' => (int) ($row->{$field} ?? 0),
            ])
            ->sortByDesc('total')
            ->values();

        $missingTotal = (int) $missingFields->sum('total');
        $possibleTotal = $totalRecords * count($fields);

        return [
            'score' => $possibleTotal ? round((1 - ($missingTotal / $possibleTotal)) * 100, 1) : 0,
            'total_records' => $totalRecords,
            'missing_fields' => $missingFields,
        ];
    }

    private function dashboardPendingVerification()
    {
        $query = FormalCase::query()
            ->select(
                'district_id',
                DB::raw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) AS submitted'),
                DB::raw('SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) AS dpo_verified')
            )
            ->whereIn('status', [FormalCase::STATUS_SUBMITTED, FormalCase::STATUS_DPO_VERIFIED])
            ->groupBy('district_id');

        $rows = Auth::user()->applyDistrictPngoScope($query)->get();
        $districts = District::whereIn('id', $rows->pluck('district_id')->filter()->unique())->get()->keyBy('id');

        return $rows
            ->map(fn ($row) => [
                'district_name' => $districts[$row->district_id]->name ?? 'Unknown',
                'submitted' => (int) $row->submitted,
                'dpo_verified' => (int) $row->dpo_verified,
                'total_pending' => (int) $row->submitted + (int) $row->dpo_verified,
            ])
            ->filter(fn ($row) => $row['total_pending'] > 0)
            ->sortByDesc('total_pending')
            ->take(8)
            ->values();
    }

    private function dashboardFollowUpWorkload(): array
    {
        $today = now()->toDateString();
        $nextWeek = now()->copy()->addDays(7)->toDateString();

        $baseQuery = FollowUpIntervention::query()
            ->leftJoin('formal_cases', function ($join) {
                $join->on('follow_up_interventions.central_id', '=', 'formal_cases.id')
                    ->orOn('follow_up_interventions.central_id', '=', 'formal_cases.central_id');
            })
            ->whereNull('formal_cases.deleted_at')
            ->whereNotNull('follow_up_interventions.to_be_taken_date')
            ->whereNotNull('follow_up_interventions.intervention_to_be_taken')
            ->where('follow_up_interventions.task_status', '!=', Todo::STATUS_DONE);

        Auth::user()->applyDistrictPngoScope($baseQuery, 'formal_cases.district_id', 'formal_cases.pngo_id');

        return [
            'overdue' => (int) (clone $baseQuery)->whereDate('follow_up_interventions.to_be_taken_date', '<', $today)->count(),
            'due_today' => (int) (clone $baseQuery)->whereDate('follow_up_interventions.to_be_taken_date', $today)->count(),
            'next_7_days' => (int) (clone $baseQuery)
                ->whereDate('follow_up_interventions.to_be_taken_date', '>', $today)
                ->whereDate('follow_up_interventions.to_be_taken_date', '<=', $nextWeek)
                ->count(),
        ];
    }

    private function dashboardDuplicateRisk(): array
    {
        $duplicateRows = Auth::user()->applyDistrictPngoScope(
            FormalCase::query()
                ->select('phone_number', DB::raw('COUNT(*) AS total'))
                ->whereNotNull('phone_number')
                ->whereRaw("CAST(phone_number AS CHAR) <> ''")
                ->groupBy('phone_number')
                ->havingRaw('COUNT(*) > 1')
        )->get();

        return [
            'phone_groups' => (int) $duplicateRows->count(),
            'phone_records' => (int) $duplicateRows->sum('total'),
        ];
    }

    private function dashboardInterventionSignals(CommonService $commonService)
    {
        $fields = [
            'family_communication_date' => 'Family Communication',
            'legal_representation_date' => 'Legal Representation',
            'collected_case_doc' => 'Case Document Collection',
            'identify_sureties_date' => 'Surety Identification',
            'witness_communication_date' => 'Witness Communication',
            'referral_service_date' => 'Referral Service',
            'prison_legal_representation_date' => 'Prison Legal Representation',
            'court_order_communication' => 'Court Order Communication',
            'application_certified_copies' => 'Certified Copies',
            'appeal_assistance' => 'Appeal Assistance',
            'ministerial_communication' => 'Ministerial Communication',
            'other_legal_assistance_date' => 'Other Legal Assistance',
        ];

        $selectSql = collect($fields)
            ->map(fn ($label, $field) => "SUM(CASE WHEN {$field} IS NOT NULL AND CAST({$field} AS CHAR) <> '' THEN 1 ELSE 0 END) AS {$field}")
            ->implode(', ');

        $row = Auth::user()->applyDistrictPngoScope(
            FormalCase::query()
                ->selectRaw($selectSql)
                ->where('status', '>', 1)
                ->whereRaw($commonService->interventionConditionSql())
        )->first();

        return collect($fields)
            ->map(fn ($label, $field) => [
                'label' => $label,
                'total' => (int) ($row->{$field} ?? 0),
            ])
            ->sortByDesc('total')
            ->take(8)
            ->values();
    }

    private function dashboardLsidServiceTypes()
    {
        $serviceCounts = [];

        Auth::user()->applyDistrictPngoScope(
            LsidRegister::query()->select('service_types')
        )
            ->get()
            ->each(function ($register) use (&$serviceCounts) {
                foreach ((array) ($register->service_types ?? []) as $serviceType) {
                    if (blank($serviceType)) {
                        continue;
                    }

                    $serviceCounts[$serviceType] = ($serviceCounts[$serviceType] ?? 0) + 1;
                }
            });

        return collect($serviceCounts)
            ->map(fn ($total, $label) => [
                'label' => $label,
                'total' => (int) $total,
            ])
            ->sortByDesc('total')
            ->take(8)
            ->values();
    }

    private function dashboardTodoSummary(): array
    {
        $user = Auth::user();
        $today = now()->toDateString();

        $personalCount = Todo::where('user_id', $user->id)
            ->whereDate('task_date', $today)
            ->where('status', '!=', Todo::STATUS_DONE)
            ->count();

        $followUpQuery = FollowUpIntervention::query()
            ->leftJoin('formal_cases', function ($join) {
                $join->on('follow_up_interventions.central_id', '=', 'formal_cases.id')
                    ->orOn('follow_up_interventions.central_id', '=', 'formal_cases.central_id');
            })
            ->whereNull('formal_cases.deleted_at')
            ->whereNotNull('follow_up_interventions.to_be_taken_date')
            ->whereNotNull('follow_up_interventions.intervention_to_be_taken')
            ->where('follow_up_interventions.task_status', '!=', Todo::STATUS_DONE);

        $user->applyDistrictPngoScope($followUpQuery, 'formal_cases.district_id', 'formal_cases.pngo_id');

        $todayFollowUpCount = (clone $followUpQuery)
            ->whereDate('follow_up_interventions.to_be_taken_date', $today)
            ->count();

        $upcoming = collect(range(0, 6))->map(function ($offset) use ($user, $followUpQuery) {
            $date = now()->startOfDay()->addDays($offset);
            $dateString = $date->toDateString();

            $personal = Todo::where('user_id', $user->id)
                ->whereDate('task_date', $dateString)
                ->where('status', '!=', Todo::STATUS_DONE)
                ->count();

            $followUp = (clone $followUpQuery)
                ->whereDate('follow_up_interventions.to_be_taken_date', $dateString)
                ->count();

            return [
                'date' => $dateString,
                'label' => $date->format('j M'),
                'day' => $date->format('D'),
                'total' => $personal + $followUp,
            ];
        });

        return [
            'today_total' => $personalCount + $todayFollowUpCount,
            'upcoming' => $upcoming,
        ];
    }

    public function districts()
    {
        $districts = District::all();
        return view('dashboard.admin.district', compact('districts'));
    }
    // Function to Add a New District
    public function districtAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:districts,name',
        ]);

        $district = new District();
        $district->name = $request->name;
        $district->save();

        // Log the creation
        LogService::logAction('District Created', [
            'district_id' => $district->id,
            'district_name' => $district->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District added successfully!',
            'district' => $district,
        ]);
    }


    // Function to Update an Existing District
    public function districtUpdate(Request $request, $districtId)
    {
        $request->validate([
            'name' => 'required|unique:districts,name,' . $districtId,
        ]);

        $district = District::findOrFail($districtId);

        $changes = [];

        if ($district->name !== $request->name) {
            $changes['name'] = [
                'from' => $district->name,
                'to' => $request->name,
            ];
        }

        $district->name = $request->name;
        $district->save();

        if (!empty($changes)) {
            LogService::logAction('District Updated', [
                'district_id' => $district->id,
                'changed_fields' => $changes,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'District updated successfully!',
            'district' => $district,
        ]);
    }


    // Function to Delete a District
    public function districtDelete($districtId)
    {
        $district = District::findOrFail($districtId);
        $deletedName = $district->name;

        $district->delete();

        // Log the deletion
        LogService::logAction('District Deleted', [
            'district_id' => $districtId,
            'district_name' => $deletedName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'District deleted successfully!',
        ]);
    }



    public function pngos()
    {
        $pngos = Pngo::with('district:id,name')
            ->orderBy('district_id')
            ->orderBy('name')
            ->get();
        $districts = District::orderBy('name')->get();
        return view('dashboard.admin.pngo', compact('pngos', 'districts'));
    }
    // Function to Add a New Pngo
    public function pngoAdd(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pngos', 'name')->where(fn ($query) => $query->where('district_id', $request->district_id)),
            ],
        ], [
            'name.unique' => 'This PNGO already exists in the selected district.',
        ]);

        // Create a new PNGO
        $pngo = new Pngo();
        $pngo->name = $request->name;
        $pngo->district_id = $request->district_id;
        $pngo->save();

        // Log the creation
        LogService::logAction('PNGO Added', [
            'pngo_id' => $pngo->id,
            'name' => $pngo->name,
            'district_id' => $pngo->district_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO added successfully!',
            'pngo' => $pngo->load('district:id,name'),
        ]);
    }


    // Function to Update an Existing Pngo
    public function pngoUpdate(Request $request, $pngoId)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pngos', 'name')
                    ->where(fn ($query) => $query->where('district_id', $request->district_id))
                    ->ignore($pngoId),
            ],
        ], [
            'name.unique' => 'This PNGO already exists in the selected district.',
        ]);

        $pngo = Pngo::findOrFail($pngoId);
        $oldName = $pngo->name;
        $oldDistrictId = $pngo->district_id;
        $pngo->name = $request->name;
        $pngo->district_id = $request->district_id;
        $pngo->save();

        // Log the update
        LogService::logAction('PNGO Update', [
            'pngo_id' => $pngo->id,
            'changed_fields' => [
                'name' => ['from' => $oldName, 'to' => $pngo->name],
                'district_id' => ['from' => $oldDistrictId, 'to' => $pngo->district_id],
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO updated successfully!',
            'pngo' => $pngo->load('district:id,name'),
        ]);
    }


    // Function to Delete a Pngo
    public function pngoDelete($pngoId)
    {
        $pngo = Pngo::findOrFail($pngoId);
        $pngoName = $pngo->name;
        $pngo->delete();

        // Log the delete action
        LogService::logAction('PNGO Delete', [
            'pngo_id' => $pngoId,
            'deleted_name' => $pngoName,
            'message' => "PNGO '{$pngoName}' (ID: {$pngoId}) was deleted.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PNGO deleted successfully!',
        ]);
    }


    

    

}
