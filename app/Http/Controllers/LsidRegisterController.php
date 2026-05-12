<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\LsidRegister;
use App\Models\Pngo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $validated['created_by'] = Auth::id();

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

        $lsidRegister->update($this->applyUserScopeToData($validated));

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
}
