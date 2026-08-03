<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\LsidRegister;
use App\Models\Pngo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LsidRegisterController extends Controller
{
    public const SEX_OPTIONS = [
        'Male' => 'পুরুষ (Male)',
        'Female' => 'নারী (Female)',
        'Transgender Person' => 'তৃতীয় লিঙ্গ (Transgender Person)',
    ];

    public const OTHER_INFORMATION_OPTIONS = [
        'Under 18' => '১৮ বছরের নিচে (Under 18)',
        'Person with Disability' => 'প্রতিবন্ধী ব্যক্তি (Person with Disability)',
    ];

    public const RECEIVER_TYPE_OPTIONS = [
        'Plaintiff/Plaintiff Family' => 'বাদী/বাদীর পরিবার (Plaintiff/Plaintiff’s Family)',
        'Defendant/Defendant Family' => 'বিবাদী/বিবাদীর পরিবার (Defendant/Defendant’s Family)',
        'Lawyer' => 'আইনজীবী (Lawyer)',
        'Witness-General' => 'সাক্ষী-সাধারণ (Witness-General)',
        'Witness-Doctor' => 'সাক্ষী-ডাক্তার (Witness-Doctor)',
        'Witness-Police' => 'সাক্ষী-পুলিশ (Witness-Police)',
        'Police' => 'পুলিশ (Police)',
        'Other People' => 'অন্যান্য ব্যক্তি (Other People)',
    ];

    public const INTERVENTION_OPTIONS = [
        'Information' => 'তথ্য প্রদান (Information)',
        'Service' => 'সেবা প্রদান (Service)',
    ];

    public const SERVICE_TYPE_OPTIONS = [
        'District Legal Aid Office' => 'জেলা লিগ্যাল এইড অফিস (District Legal Aid Office)',
        'Location of Courts Ajlas' => 'আদালতের এজলাস সংক্রান্ত (Location of Courts Ajlas)',
        'Location of Court Offices' => 'আদালতের অফিসসমূহের অবস্থান (Location of Court Offices)',
        'GO and NGO Victim Support Center Service' => 'সরকারি ও বেসরকারি ভিকটিম সাপোর্ট সেন্টার সেবা সংক্রান্ত (GO and NGO Victim Support Center Service)',
        'Basic Law Information' => 'মৌলিক আইন বিষয়ক তথ্য (Basic Law Information)',
        'Paralegal Advisory Service' => 'প্যারালিগ্যাল অ্যাডভাইজরি সার্ভিস (Paralegal Advisory Service)',
        'Other' => 'অন্যান্য (Other)',
    ];

    public function index()
    {
        [$districts, $pngos] = $this->allowedDistrictsAndPngos();

        return view('dashboard.admin.lsid-register', [
            'districts' => $districts,
            'pngos' => $pngos,
            'sexOptions' => self::SEX_OPTIONS,
            'otherInformationOptions' => self::OTHER_INFORMATION_OPTIONS,
            'receiverTypeOptions' => self::RECEIVER_TYPE_OPTIONS,
            'interventionOptions' => self::INTERVENTION_OPTIONS,
            'serviceTypeOptions' => self::SERVICE_TYPE_OPTIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'district_id' => ['nullable', 'exists:districts,id'],
            'pngo_id' => ['nullable', 'exists:pngos,id'],
            'service_date' => ['required', 'date'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:30'],
            'sex' => ['required', 'in:' . implode(',', array_keys(self::SEX_OPTIONS))],
            'other_information' => ['nullable', 'array'],
            'other_information.*' => ['in:' . implode(',', array_keys(self::OTHER_INFORMATION_OPTIONS))],
            'receiver_types' => ['required', 'array', 'min:1'],
            'receiver_types.*' => ['in:' . implode(',', array_keys(self::RECEIVER_TYPE_OPTIONS))],
            'interventions_taken' => ['required', 'array', 'min:1'],
            'interventions_taken.*' => ['in:' . implode(',', array_keys(self::INTERVENTION_OPTIONS))],
            'service_types' => ['required', 'array', 'min:1'],
            'service_types.*' => ['in:' . implode(',', array_keys(self::SERVICE_TYPE_OPTIONS))],
            'receiver_type_other' => ['nullable', 'string', 'max:255'],
            'service_type_other' => ['nullable', 'string', 'max:255'],
        ], [
            'receiver_types.required' => 'Please select at least one type of information/service receiver.',
            'receiver_types.min' => 'Please select at least one type of information/service receiver.',
            'interventions_taken.required' => 'Please select at least one intervention taken.',
            'interventions_taken.min' => 'Please select at least one intervention taken.',
            'service_types.required' => 'Please select at least one type of information/service provided.',
            'service_types.min' => 'Please select at least one type of information/service provided.',
        ]);

        $validated = $this->applyUserScopeToData($validated);

        if (empty($validated['district_id']) || empty($validated['pngo_id'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['district_id' => 'District and PNGO are required for LSID register entries.']);
        }

        $validated['created_by'] = Auth::id();
        $validated['lsid_id'] = $this->generateLsidId((int) $validated['district_id']);

        LsidRegister::create($validated);

        return redirect()
            ->route('lsid-register.index')
            ->with('success', 'LSID register entry saved successfully.');
    }

    public function manage(Request $request)
    {
        $managementRequested = $request->query->count() > 0;

        $query = $this->scopedQuery()
            ->with(['district:id,name', 'pngo:id,name']);

        if ($managementRequested) {
            $query
                ->when($request->filled('district_id'), function ($query) use ($request) {
                    $query->where('district_id', $request->district_id);
                })
                ->when($request->filled('pngo_id'), function ($query) use ($request) {
                    $query->where('pngo_id', $request->pngo_id);
                })
                ->when($request->filled('sex'), function ($query) use ($request) {
                    $query->where('sex', $request->sex);
                })
                ->when($request->filled('from_date'), function ($query) use ($request) {
                    $query->whereDate('service_date', '>=', $request->from_date);
                })
                ->when($request->filled('to_date'), function ($query) use ($request) {
                    $query->whereDate('service_date', '<=', $request->to_date);
                })
                ->when($request->filled('intervention'), function ($query) use ($request) {
                    $query->whereJsonContains('interventions_taken', $request->intervention);
                });
        } else {
            $query->whereRaw('1 = 0');
        }

        $registers = $query
            ->latest('service_date')
            ->paginate(25)
            ->withQueryString();

        [$districts, $pngos] = $this->allowedDistrictsAndPngos();

        return view('dashboard.admin.lsid-management', $this->viewData([
            'registers' => $registers,
            'districts' => $districts,
            'pngos' => $pngos,
            'filters' => $request->only(['district_id', 'pngo_id', 'sex', 'from_date', 'to_date', 'intervention']),
            'managementRequested' => $managementRequested,
        ]));
    }

    public function update(Request $request, LsidRegister $lsidRegister)
    {
        $this->authorizeScope($lsidRegister);

        $validated = $request->validate([
            'district_id' => ['nullable', 'exists:districts,id'],
            'pngo_id' => ['nullable', 'exists:pngos,id'],
            'service_date' => ['required', 'date'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'mobile_number' => ['nullable', 'string', 'max:30'],
            'sex' => ['required', 'in:' . implode(',', array_keys(self::SEX_OPTIONS))],
            'other_information' => ['nullable', 'array'],
            'other_information.*' => ['in:' . implode(',', array_keys(self::OTHER_INFORMATION_OPTIONS))],
            'receiver_types' => ['required', 'array', 'min:1'],
            'receiver_types.*' => ['in:' . implode(',', array_keys(self::RECEIVER_TYPE_OPTIONS))],
            'interventions_taken' => ['required', 'array', 'min:1'],
            'interventions_taken.*' => ['in:' . implode(',', array_keys(self::INTERVENTION_OPTIONS))],
            'service_types' => ['required', 'array', 'min:1'],
            'service_types.*' => ['in:' . implode(',', array_keys(self::SERVICE_TYPE_OPTIONS))],
            'receiver_type_other' => ['nullable', 'string', 'max:255'],
            'service_type_other' => ['nullable', 'string', 'max:255'],
        ]);

        $validated = $this->applyUserScopeToData($validated);

        if (empty($validated['district_id']) || empty($validated['pngo_id'])) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['district_id' => 'District and PNGO are required for LSID register entries.']);
        }

        $lsidRegister->update($validated);

        return redirect()
            ->route('lsid-register.manage', $request->only(['district_id', 'pngo_id', 'sex', 'from_date', 'to_date', 'intervention']))
            ->with('success', 'LSID register entry updated successfully.');
    }

    public function edit(LsidRegister $lsidRegister)
    {
        $this->authorizeScope($lsidRegister);

        [$districts, $pngos] = $this->allowedDistrictsAndPngos();

        return view('dashboard.admin.lsid-register', $this->viewData([
            'register' => $lsidRegister,
            'districts' => $districts,
            'pngos' => $pngos,
        ]));
    }

    public function destroy(LsidRegister $lsidRegister)
    {
        $this->authorizeScope($lsidRegister);
        $lsidRegister->delete();

        return redirect()
            ->route('lsid-register.manage')
            ->with('success', 'LSID register entry deleted successfully.');
    }

    public function importView()
    {
        return view('dashboard.admin.lsid-import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:51200'],
        ]);

        $rows = $this->readLsidImportRows($request->file('file')->getRealPath());
        $preparedRows = [];
        $importErrors = [];

        foreach ($rows as $row) {
            $rowNumber = $row['__row_number'];

            if ($this->isEmptyLsidImportRow($row)) {
                continue;
            }

            $prepared = $this->prepareLsidImportRow($row, $rowNumber);

            if (! empty($prepared['errors'])) {
                $importErrors = array_merge($importErrors, $prepared['errors']);
                continue;
            }

            $preparedRows[] = $prepared['data'];
        }

        if (! empty($importErrors)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('import_errors', $importErrors);
        }

        if (empty($preparedRows)) {
            return redirect()->back()->with('error', 'No importable LSID rows found in the uploaded file.');
        }

        try {
            DB::transaction(function () use ($preparedRows) {
                $districtCounters = [];

                foreach ($preparedRows as $row) {
                    $districtId = (int) $row['district_id'];
                    $districtCounters[$districtId] ??= $this->lastLsidNumberForDistrict($districtId);
                    $districtCounters[$districtId]++;

                    $row['lsid_id'] = $this->buildLsidId($districtId, $districtCounters[$districtId]);
                    $row['created_by'] = Auth::id();

                    LsidRegister::create($row);
                }
            });
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('import_errors', [
                    'Import stopped before completion. No LSID rows from this upload were kept. ' . $e->getMessage(),
                ]);
        }

        return redirect()
            ->back()
            ->with('success', count($preparedRows) . ' LSID register row(s) imported successfully.');
    }

    public function report(Request $request)
    {
        $reportRequested = $request->query->count() > 0;
        $districtSelected = Auth::user()->district_id || $request->filled('district_id');

        $query = $this->scopedQuery()
            ->with(['district:id,name', 'pngo:id,name', 'creator:id,name'])
            ->when($request->filled('district_id'), function ($query) use ($request) {
                $query->where('district_id', $request->district_id);
            })
            ->when($request->filled('pngo_id'), function ($query) use ($request) {
                $query->where('pngo_id', $request->pngo_id);
            })
            ->when($request->filled('sex'), function ($query) use ($request) {
                $query->where('sex', $request->sex);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('service_date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('service_date', '<=', $request->to_date);
            })
            ->when($request->filled('intervention'), function ($query) use ($request) {
                $query->whereJsonContains('interventions_taken', $request->intervention);
            });

        $registers = ($reportRequested && $districtSelected)
            ? $query->latest('service_date')->get()
            : collect();
        $appliedFilters = ($reportRequested && $districtSelected) ? $this->appliedFilters($request) : [];
        $reportDistrictName = Auth::user()->district_id
            ? District::whereKey(Auth::user()->district_id)->value('name')
            : ($request->filled('district_id') ? District::whereKey($request->district_id)->value('name') : null);
        $reportPngoName = Auth::user()->pngo_id
            ? Pngo::whereKey(Auth::user()->pngo_id)->value('name')
            : ($request->filled('pngo_id') ? Pngo::whereKey($request->pngo_id)->value('name') : 'All PNGO');

        [$districts, $pngos] = $this->allowedDistrictsAndPngos();

        return view('dashboard.report.lsid-report', $this->viewData([
            'registers' => $registers,
            'districts' => $districts,
            'pngos' => $pngos,
            'filters' => $request->only(['district_id', 'pngo_id', 'sex', 'from_date', 'to_date', 'intervention']),
            'appliedFilters' => $appliedFilters,
            'reportRequested' => $reportRequested,
            'districtSelected' => $districtSelected,
            'reportDistrictName' => $reportDistrictName,
            'reportPngoName' => $reportPngoName,
        ]));
    }

    public function reportPdf(Request $request)
    {
        $reportRequested = $request->query->count() > 0;
        $districtSelected = Auth::user()->district_id || $request->filled('district_id');

        $query = $this->scopedQuery()
            ->with(['district:id,name', 'pngo:id,name', 'creator:id,name'])
            ->when($request->filled('district_id'), function ($query) use ($request) {
                $query->where('district_id', $request->district_id);
            })
            ->when($request->filled('pngo_id'), function ($query) use ($request) {
                $query->where('pngo_id', $request->pngo_id);
            })
            ->when($request->filled('sex'), function ($query) use ($request) {
                $query->where('sex', $request->sex);
            })
            ->when($request->filled('from_date'), function ($query) use ($request) {
                $query->whereDate('service_date', '>=', $request->from_date);
            })
            ->when($request->filled('to_date'), function ($query) use ($request) {
                $query->whereDate('service_date', '<=', $request->to_date);
            })
            ->when($request->filled('intervention'), function ($query) use ($request) {
                $query->whereJsonContains('interventions_taken', $request->intervention);
            });

        $registers = ($reportRequested && $districtSelected)
            ? $query->latest('service_date')->get()
            : collect();

        $reportDistrictName = Auth::user()->district_id
            ? District::whereKey(Auth::user()->district_id)->value('name')
            : ($request->filled('district_id') ? District::whereKey($request->district_id)->value('name') : null);
        $reportPngoName = Auth::user()->pngo_id
            ? Pngo::whereKey(Auth::user()->pngo_id)->value('name')
            : ($request->filled('pngo_id') ? Pngo::whereKey($request->pngo_id)->value('name') : 'All PNGO');

        $mpdf = $this->reportMpdf('L');
        $html = view('dashboard.report.lsid-report-pdf', $this->viewData([
            'registers' => $registers,
            'appliedFilters' => ($reportRequested && $districtSelected) ? $this->appliedFilters($request) : [],
            'reportDistrictName' => $reportDistrictName,
            'reportPngoName' => $reportPngoName,
        ]))->render();

        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="lsid-register-report.pdf"',
        ]);
    }

    private function scopedQuery()
    {
        $query = LsidRegister::query();
        $user = Auth::user();

        if ($user->district_id) {
            $query->where('district_id', $user->district_id);
        }

        if ($user->pngo_id) {
            $query->where('pngo_id', $user->pngo_id);
        }

        if ($user->hasPngoScopes()) {
            $user->applyDistrictPngoScope($query);
        }

        return $query;
    }

    private function authorizeScope(LsidRegister $lsidRegister): void
    {
        $user = Auth::user();

        abort_if(! $user->canAccessDistrictPngo($lsidRegister->district_id, $lsidRegister->pngo_id), 403);
    }

    private function applyUserScopeToData(array $data): array
    {
        $user = Auth::user();

        if ($user->district_id) {
            $data['district_id'] = $user->district_id;
        }

        if ($user->pngo_id) {
            $data['pngo_id'] = $user->pngo_id;
        }

        if ($user->hasPngoScopes()) {
            abort_if(empty($data['district_id']) || empty($data['pngo_id']), 403);
            abort_if(! $user->canAccessDistrictPngo($data['district_id'], $data['pngo_id']), 403);
        }

        return $data;
    }

    private function generateLsidId(int $districtId): string
    {
        return $this->buildLsidId($districtId, $this->lastLsidNumberForDistrict($districtId) + 1);
    }

    private function buildLsidId(int $districtId, int $number): string
    {
        $districtName = District::whereKey($districtId)->value('name') ?: 'LSI';

        return strtoupper(substr($districtName, 0, 3)) . '-LSID-' . $number;
    }

    private function lastLsidNumberForDistrict(int $districtId): int
    {
        return LsidRegister::where('district_id', $districtId)
            ->pluck('lsid_id')
            ->reduce(function ($highest, $lsidId) {
                preg_match('/(\d+)$/', (string) $lsidId, $matches);

                return isset($matches[1]) ? max($highest, (int) $matches[1]) : $highest;
            }, 0);
    }

    private function readLsidImportRows(string $path): array
    {
        @ini_set('memory_limit', '512M');
        @set_time_limit(300);

        $reader = IOFactory::createReaderForFile($path);
        $reader->setReadDataOnly(true);

        if (method_exists($reader, 'setLoadSheetsOnly')) {
            $reader->setLoadSheetsOnly(['Data_Sheet']);
        }

        if (method_exists($reader, 'setReadFilter')) {
            $reader->setReadFilter(new class implements IReadFilter {
                public function readCell($columnAddress, $row, $worksheetName = ''): bool
                {
                    return $row >= 1
                        && $row <= 30000
                        && in_array($columnAddress, ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'], true)
                        && ($worksheetName === '' || $worksheetName === 'Data_Sheet');
                }
            });
        }

        $spreadsheet = $reader->load($path);
        $sheet = $spreadsheet->getSheetByName('Data_Sheet') ?: $spreadsheet->getActiveSheet();
        $rows = [];
        $emptyRowStreak = 0;
        $foundData = false;
        $startRow = $this->detectLsidImportStartRow($sheet);

        for ($rowNumber = $startRow; $rowNumber <= $sheet->getHighestDataRow(); $rowNumber++) {
            $row = [
                '__row_number' => $rowNumber,
                'district' => $this->cellValue($sheet, 'A', $rowNumber),
                'service_date' => $sheet->getCell('C' . $rowNumber)->getValue(),
                'sex' => $this->cellValue($sheet, 'D', $rowNumber),
                'under_18' => $this->cellValue($sheet, 'E', $rowNumber),
                'pwd' => $this->cellValue($sheet, 'F', $rowNumber),
                'receiver_type' => $this->cellValue($sheet, 'G', $rowNumber),
                'receiver_type_other' => $this->cellValue($sheet, 'H', $rowNumber),
                'intervention' => $this->cellValue($sheet, 'I', $rowNumber),
                'service_type' => $this->cellValue($sheet, 'J', $rowNumber),
                'service_type_other' => $this->cellValue($sheet, 'K', $rowNumber),
                'remarks' => $this->cellValue($sheet, 'L', $rowNumber),
            ];

            if ($this->isEmptyLsidImportRow($row)) {
                if ($foundData) {
                    $emptyRowStreak++;

                    if ($emptyRowStreak >= 250) {
                        break;
                    }
                }

                continue;
            }

            $foundData = true;
            $emptyRowStreak = 0;
            $rows[] = $row;
        }

        $spreadsheet->disconnectWorksheets();

        return $rows;
    }

    private function detectLsidImportStartRow($sheet): int
    {
        for ($rowNumber = 1; $rowNumber <= min(10, $sheet->getHighestDataRow()); $rowNumber++) {
            $district = $this->cellValue($sheet, 'A', $rowNumber);
            $date = $sheet->getCell('C' . $rowNumber)->getValue();
            $sex = $this->cellValue($sheet, 'D', $rowNumber);
            $receiverType = $this->cellValue($sheet, 'G', $rowNumber);
            $intervention = $this->cellValue($sheet, 'I', $rowNumber);
            $serviceType = $this->cellValue($sheet, 'J', $rowNumber);

            if (
                $this->findDistrictByName($district)
                && $this->parseLsidImportDate($date)
                && $this->normalizeLsidSex($sex)
                && ! empty($this->normalizeLsidReceiverTypes($receiverType))
                && ! empty($this->normalizeLsidInterventions($intervention))
                && filled($serviceType)
            ) {
                return $rowNumber;
            }
        }

        return 6;
    }

    private function cellValue($sheet, string $column, int $row): ?string
    {
        $value = $sheet->getCell($column . $row)->getValue();

        if ($value === null) {
            return null;
        }

        $value = trim(str_replace(["\r", "\n"], ' ', (string) $value));

        return $value === '' ? null : $value;
    }

    private function isEmptyLsidImportRow(array $row): bool
    {
        foreach (['district', 'service_date', 'sex', 'under_18', 'pwd', 'receiver_type', 'receiver_type_other', 'intervention', 'service_type', 'service_type_other', 'remarks'] as $key) {
            if (filled($row[$key] ?? null)) {
                return false;
            }
        }

        return true;
    }

    private function prepareLsidImportRow(array $row, int $rowNumber): array
    {
        $errors = [];
        $district = $this->findDistrictByName($row['district'] ?? null);
        $pngo = $district ? $this->findSinglePngoForDistrict($district) : null;
        $serviceDate = $this->parseLsidImportDate($row['service_date'] ?? null);
        $sex = $this->normalizeLsidSex($row['sex'] ?? null);
        $otherInformation = $this->normalizeLsidOtherInformation($row['under_18'] ?? null, $row['pwd'] ?? null);
        $receiverTypes = $this->normalizeLsidReceiverTypes($row['receiver_type'] ?? null);
        $interventions = $this->normalizeLsidInterventions($row['intervention'] ?? null);
        $serviceTypes = $this->normalizeLsidServiceTypes($row['service_type'] ?? null);
        $serviceTypeOther = $row['service_type_other'] ?? null;

        if (empty($serviceTypes) && filled($row['service_type'] ?? null)) {
            $serviceTypes = ['Other'];
            $serviceTypeOther = trim(implode(' - ', array_filter([
                $serviceTypeOther,
                $row['service_type'],
            ])));
        }

        if (! $district) {
            $errors[] = "Row {$rowNumber}: District was not found.";
        }

        if ($district && ! $pngo) {
            $errors[] = "Row {$rowNumber}: Could not determine one PNGO for district {$district->name}.";
        }

        if ($district && $pngo && ! Auth::user()->canAccessDistrictPngo($district->id, $pngo->id)) {
            $errors[] = "Row {$rowNumber}: District-PNGO pair is outside your access scope.";
        }

        if (! $serviceDate) {
            $errors[] = "Row {$rowNumber}: Date is required or invalid.";
        }

        if (! $sex) {
            $errors[] = "Row {$rowNumber}: Sex is required or invalid.";
        }

        if (empty($receiverTypes)) {
            $errors[] = "Row {$rowNumber}: Type of information/service receiver is required or invalid.";
        }

        if (empty($interventions)) {
            $errors[] = "Row {$rowNumber}: Intervention taken is required or invalid.";
        }

        if (empty($serviceTypes)) {
            $errors[] = "Row {$rowNumber}: Type of information/service provided is required or invalid.";
        }

        if (! empty($errors)) {
            return ['errors' => $errors, 'data' => []];
        }

        return [
            'errors' => [],
            'data' => [
                'district_id' => $district->id,
                'pngo_id' => $pngo->id,
                'service_date' => $serviceDate,
                'receiver_name' => null,
                'mobile_number' => null,
                'sex' => $sex,
                'other_information' => $otherInformation,
                'receiver_types' => $receiverTypes,
                'interventions_taken' => $interventions,
                'service_types' => $serviceTypes,
                'receiver_type_other' => $row['receiver_type_other'] ?? null,
                'service_type_other' => $serviceTypeOther,
                'remarks' => $row['remarks'] ?? null,
            ],
        ];
    }

    private function findDistrictByName(?string $name): ?District
    {
        if (blank($name)) {
            return null;
        }

        $normalized = $this->normalizeImportText($name);

        return District::all()->first(fn ($district) => $this->normalizeImportText($district->name) === $normalized);
    }

    private function findSinglePngoForDistrict(District $district): ?Pngo
    {
        $pngos = Pngo::where('district_id', $district->id)->get();

        return $pngos->count() === 1 ? $pngos->first() : null;
    }

    private function parseLsidImportDate($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return Carbon::instance($value)->format('Y-m-d');
        }

        if (is_numeric($value)) {
            try {
                return Carbon::instance(ExcelDate::excelToDateTimeObject($value))->format('Y-m-d');
            } catch (\Throwable $e) {
                return null;
            }
        }

        $value = trim((string) $value);
        $formats = ['Y-m-d', 'm/d/Y', 'm/d/y', 'n/j/Y', 'n/j/y', 'd/m/Y', 'd/m/y', 'd-M-y', 'd-M-Y', 'j M Y', 'j M, Y'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
                $errors = Carbon::getLastErrors();

                if ($date !== false && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                    return $date->format('Y-m-d');
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function normalizeLsidSex(?string $value): ?string
    {
        return $this->matchImportOption($value, [
            'male' => 'Male',
            'female' => 'Female',
            'woman' => 'Female',
            'women' => 'Female',
            'transgender' => 'Transgender Person',
            'transgender person' => 'Transgender Person',
            'third gender' => 'Transgender Person',
        ]);
    }

    private function normalizeLsidOtherInformation(?string $under18, ?string $pwd): array
    {
        $values = [];

        if ($this->isImportYes($under18)) {
            $values[] = 'Under 18';
        }

        if ($this->isImportYes($pwd)) {
            $values[] = 'Person with Disability';
        }

        return $values;
    }

    private function normalizeLsidReceiverTypes(?string $value): array
    {
        return $this->normalizeLsidMultiValue($value, [
            'plaintiff/plaintiff family' => 'Plaintiff/Plaintiff Family',
            'plaintiff/plaintiffs family' => 'Plaintiff/Plaintiff Family',
            'plaintiff plaintiff family' => 'Plaintiff/Plaintiff Family',
            'defendant/defendant family' => 'Defendant/Defendant Family',
            'defendant/defendants family' => 'Defendant/Defendant Family',
            'defendant defendant family' => 'Defendant/Defendant Family',
            'lawyer' => 'Lawyer',
            'witness general' => 'Witness-General',
            'witness doctor' => 'Witness-Doctor',
            'witness police' => 'Witness-Police',
            'police' => 'Police',
            'other persons' => 'Other People',
            'other people' => 'Other People',
            'others' => 'Other People',
            'other' => 'Other People',
        ]);
    }

    private function normalizeLsidInterventions(?string $value): array
    {
        if (filled($value)) {
            $value = preg_replace('/\s+and\s+/i', ' & ', (string) $value);
        }

        return $this->normalizeLsidMultiValue($value, [
            'information' => 'Information',
            'service' => 'Service',
        ]);
    }

    private function normalizeLsidServiceTypes(?string $value): array
    {
        return $this->normalizeLsidMultiValue($value, [
            'district legal aid office' => 'District Legal Aid Office',
            'location of courts ajlas' => 'Location of Courts Ajlas',
            'location of court ajlas' => 'Location of Courts Ajlas',
            'location of courts offices' => 'Location of Court Offices',
            'location of court offices' => 'Location of Court Offices',
            'go and ngo victim support center service' => 'GO and NGO Victim Support Center Service',
            'victim support center service' => 'GO and NGO Victim Support Center Service',
            'basic law information' => 'Basic Law Information',
            'basic law' => 'Basic Law Information',
            'paralegal advisory service' => 'Paralegal Advisory Service',
            'other' => 'Other',
            'others' => 'Other',
        ]);
    }

    private function normalizeLsidMultiValue(?string $value, array $map): array
    {
        if (blank($value)) {
            return [];
        }

        $parts = preg_split('/\s*(?:,|;|&|\+)\s*/i', (string) $value);
        $normalized = [];

        foreach ($parts as $part) {
            $matched = $this->matchImportOption($part, $map);

            if ($matched && ! in_array($matched, $normalized, true)) {
                $normalized[] = $matched;
            }
        }

        return $normalized;
    }

    private function matchImportOption(?string $value, array $map): ?string
    {
        $key = $this->normalizeImportText($value);

        return $map[$key] ?? null;
    }

    private function normalizeImportText(?string $value): string
    {
        $value = strtolower(trim((string) $value));
        $value = str_replace(["’", "'", '.', '-', '_'], ['', '', '', ' ', ' '], $value);
        $value = preg_replace('/\s*\/\s*/', '/', $value);
        $value = preg_replace('/\s+/', ' ', $value);

        return trim($value);
    }

    private function isImportYes(?string $value): bool
    {
        return in_array($this->normalizeImportText($value), ['yes', 'y', '1', 'true'], true);
    }

    private function appliedFilters(Request $request): array
    {
        $filters = [];
        $user = Auth::user();

        if ($user->district_id) {
            $filters['District'] = District::whereKey($user->district_id)->value('name');
        } elseif ($request->filled('district_id')) {
            $filters['District'] = District::whereKey($request->district_id)->value('name') ?: $request->district_id;
        }

        if ($user->pngo_id) {
            $filters['PNGO'] = Pngo::whereKey($user->pngo_id)->value('name');
        } elseif ($request->filled('pngo_id')) {
            $filters['PNGO'] = Pngo::whereKey($request->pngo_id)->value('name') ?: $request->pngo_id;
        }

        if ($request->filled('from_date')) {
            $filters['From Date'] = \Carbon\Carbon::parse($request->from_date)->format('j M, Y');
        }

        if ($request->filled('to_date')) {
            $filters['To Date'] = \Carbon\Carbon::parse($request->to_date)->format('j M, Y');
        }

        if ($request->filled('intervention')) {
            $filters['Intervention Taken'] = self::INTERVENTION_OPTIONS[$request->intervention] ?? $request->intervention;
        }

        if ($request->filled('sex')) {
            $filters['Sex'] = self::SEX_OPTIONS[$request->sex] ?? $request->sex;
        }

        return array_filter($filters);
    }

    private function viewData(array $data = []): array
    {
        return array_merge([
            'sexOptions' => self::SEX_OPTIONS,
            'otherInformationOptions' => self::OTHER_INFORMATION_OPTIONS,
            'receiverTypeOptions' => self::RECEIVER_TYPE_OPTIONS,
            'interventionOptions' => self::INTERVENTION_OPTIONS,
            'serviceTypeOptions' => self::SERVICE_TYPE_OPTIONS,
        ], $data);
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
            'margin_top' => 8,
            'margin_bottom' => 8,
            'margin_left' => 7,
            'margin_right' => 7,
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
}
