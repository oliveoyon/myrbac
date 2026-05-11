@extends('dashboard.layouts.admin-layout')

@section('title', isset($register) ? 'Edit LSID Register' : 'LSID Register')

@push('styles')
<style>
    .lsid-page {
        background: #f5f6f8;
        max-width: 1320px;
        margin: 0 auto;
        color: #1f2933;
        min-height: calc(100vh - 80px);
        padding-bottom: 36px;
    }

    .lsid-header {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        align-items: flex-start;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdfb 100%);
        border: 1px solid #e1e5ea;
        border-left: 4px solid #78a891;
        border-radius: 8px;
        padding: 18px 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    .lsid-header h1 {
        margin: 4px 0 6px;
        font-size: 24px;
        font-weight: 700;
        color: #111827;
    }

    .lsid-header p,
    .lsid-kicker {
        margin: 0;
        color: #6b7280;
    }

    .lsid-kicker {
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .lsid-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .lsid-meta span,
    .lsid-status-pill {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 6px;
        background: #f3f4f6;
        color: #374151;
        font-size: 13px;
        font-weight: 600;
    }

    .lsid-status-pill {
        background: #eef7f1;
        color: #285d49;
        white-space: nowrap;
    }

    .lsid-card {
        background: #ffffff;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    .lsid-card-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        padding: 14px 18px;
        background: #ffffff;
        border-bottom: 1px solid #e7eaee;
    }

    .lsid-card-header h2 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
    }

    .lsid-card-header i {
        color: #5b8d74;
    }

    .lsid-card-body {
        padding: 18px;
    }

    .lsid-section-title {
        margin: 0 0 12px;
        font-size: 15px;
        font-weight: 700;
        color: #243b35;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .lsid-section-title::before {
        content: "";
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #7fae96;
        box-shadow: 0 0 0 4px #edf6f1;
    }

    .lsid-check-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 9px;
    }

    .lsid-check-card {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        min-height: 44px;
        padding: 10px 11px;
        border: 1px solid #dde7e1;
        border-radius: 6px;
        background: #ffffff;
        color: #263238;
        font-size: 13px;
        line-height: 1.3;
        transition: border-color .15s ease, background .15s ease;
    }

    .lsid-check-card:hover {
        border-color: #a9c9b9;
        background: #f8fcfa;
    }

    .lsid-check-card input {
        margin-top: 2px;
        flex: 0 0 auto;
        width: 16px;
        height: 16px;
        accent-color: #2f7d62;
    }

    .lsid-check-card.is-selected {
        border-color: #93bba7;
        background: #f1faf5;
    }

    .lsid-check-card.is-selected span {
        color: #214f3f;
        font-weight: 650;
    }

    .lsid-action-row {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 18px;
    }

    .lsid-action-row .btn-success {
        background: #2f7d62;
        border-color: #2f7d62;
    }

    .lsid-table-wrap {
        overflow-x: auto;
    }

    .lsid-table th {
        white-space: nowrap;
        color: #374151;
        font-size: 13px;
    }

    .lsid-table td {
        vertical-align: top;
        font-size: 13px;
    }

    .lsid-tag {
        display: inline-block;
        margin: 0 3px 4px 0;
        padding: 2px 7px;
        border-radius: 999px;
        background: #eef7f1;
        color: #235c47;
        font-size: 12px;
        font-weight: 600;
    }

    .lsid-card.recent-card .lsid-card-header {
        background: #f8faf9;
    }

    .lsid-option-block {
        border: 1px solid #dde7e1;
        border-radius: 8px;
        background: #fbfdfb;
        padding: 14px;
    }

    .lsid-option-block + .lsid-option-block {
        margin-top: 14px;
    }

    .lsid-option-block .lsid-section-title {
        margin-bottom: 10px;
    }

    .lsid-dependent-field {
        display: none;
        margin-top: 12px;
        padding: 12px;
        border: 1px dashed #bfd8cb;
        border-radius: 8px;
        background: #ffffff;
    }

    .lsid-dependent-field.is-visible {
        display: block;
    }

    @media (max-width: 768px) {
        .lsid-check-grid {
            grid-template-columns: 1fr;
        }

        .lsid-page {
            padding-bottom: 24px;
        }

        .lsid-header {
            flex-direction: column;
            padding: 16px;
        }

        .lsid-header h1 {
            font-size: 20px;
        }

        .lsid-card-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 13px 14px;
        }

        .lsid-card-body {
            padding: 14px;
        }

        .lsid-check-card {
            min-height: 48px;
            font-size: 14px;
        }

        .lsid-action-row {
            position: sticky;
            bottom: 0;
            z-index: 5;
            margin: 0 -10px 18px;
            padding: 10px;
            background: rgba(245, 246, 248, .96);
            border-top: 1px solid #e1e5ea;
        }

        .lsid-action-row .btn {
            width: 100%;
            min-height: 44px;
        }
    }
</style>
@endpush

@section('content')
<section class="lsid-page">
    @php
        $isEdit = isset($register);
        $selectedDistrictId = old('district_id', $isEdit ? $register->district_id : '');
        $selectedPngoId = old('pngo_id', $isEdit ? $register->pngo_id : '');
        $selectedSex = old('sex', $isEdit ? $register->sex : '');
        $selectedOtherInformation = old('other_information', $isEdit ? ($register->other_information ?? []) : []);
        $selectedReceiverTypes = old('receiver_types', $isEdit ? ($register->receiver_types ?? []) : []);
        $selectedInterventions = old('interventions_taken', $isEdit ? ($register->interventions_taken ?? []) : []);
        $selectedServiceTypes = old('service_types', $isEdit ? ($register->service_types ?? []) : []);
    @endphp

    <div class="lsid-header">
        <div>
            <div class="lsid-kicker">Legal Service Information Desk</div>
            <h1>লিগ্যাল সার্ভিস ইনফরমেশন ডেস্ক রেজিস্টার</h1>
            <p>{{ $isEdit ? 'Update information/service receiver details within your permitted scope.' : 'Record information/service receiver details and the type of support provided from the information desk.' }}</p>
            <div class="lsid-meta">
                <span>Separate Register</span>
                <span>District/PNGO scoped</span>
            </div>
        </div>
        <span class="lsid-status-pill">{{ $isEdit ? 'Edit entry' : 'Draft entry form' }}</span>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please check the form.</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ $isEdit ? route('lsid-register.update', $register) : route('lsid-register.store') }}" method="POST" autocomplete="off">
        @csrf
        @if ($isEdit)
            @method('PUT')
        @endif

        <div class="lsid-card">
            <div class="lsid-card-header">
                <h2><i class="fas fa-user-edit"></i>Receiver Information</h2>
            </div>
            <div class="lsid-card-body">
                <div class="row g-3">
                    @if (!auth()->user()->district_id)
                        <div class="col-md-3">
                            <label class="form-label">District</label>
                            <select name="district_id" class="form-control" data-lsid-district-select>
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}" {{ (string) $selectedDistrictId === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if (!auth()->user()->pngo_id)
                        <div class="col-md-3">
                            <label class="form-label">PNGO</label>
                            <select name="pngo_id" class="form-control" data-lsid-pngo-select data-selected-pngo="{{ $selectedPngoId }}" data-fixed-district="{{ auth()->user()->district_id }}">
                                <option value="">Select PNGO</option>
                                @foreach ($pngos as $pngo)
                                    <option value="{{ $pngo->id }}" {{ (string) old('pngo_id') === (string) $pngo->id ? 'selected' : '' }}>{{ $pngo->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <label class="form-label">তারিখ (Date) <span class="text-danger">*</span></label>
                        <input type="date" name="service_date" class="form-control" value="{{ old('service_date', $isEdit ? optional($register->service_date)->format('Y-m-d') : now()->toDateString()) }}" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">তথ্য/সেবা গ্রহণকারীর নাম (Information/Service Receiver Name) <span class="text-danger">*</span></label>
                        <input type="text" name="receiver_name" class="form-control" value="{{ old('receiver_name', $isEdit ? $register->receiver_name : '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">মোবাইল নম্বর (Mobile Number)</label>
                        <input type="text" name="mobile_number" class="form-control" value="{{ old('mobile_number', $isEdit ? $register->mobile_number : '') }}">
                    </div>
                    <div class="col-12">
                        <h3 class="lsid-section-title">লিঙ্গ (Sex) <span class="text-danger">*</span></h3>
                        <div class="lsid-check-grid">
                            @foreach ($sexOptions as $value => $label)
                                <label class="lsid-check-card">
                                    <input type="radio" name="sex" value="{{ $value }}" {{ $selectedSex === $value ? 'checked' : '' }} required>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lsid-card">
            <div class="lsid-card-header">
                <h2><i class="fas fa-list-check"></i>Classification</h2>
            </div>
            <div class="lsid-card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <h3 class="lsid-section-title">অন্যান্য তথ্য (Other Information)</h3>
                        <div class="lsid-check-grid">
                            @foreach ($otherInformationOptions as $value => $label)
                                <label class="lsid-check-card">
                                    <input type="checkbox" name="other_information[]" value="{{ $value }}" {{ in_array($value, $selectedOtherInformation, true) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h3 class="lsid-section-title">গৃহীত পদক্ষেপ (Intervention Taken) <span class="text-danger">*</span></h3>
                        <div class="lsid-check-grid">
                            @foreach ($interventionOptions as $value => $label)
                                <label class="lsid-check-card">
                                    <input type="checkbox" name="interventions_taken[]" value="{{ $value }}" {{ in_array($value, $selectedInterventions, true) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="lsid-option-block">
                            <h3 class="lsid-section-title">তথ্য/সেবা গ্রহণকারীর ধরণ (Type of Information/Service Receiver) <span class="text-danger">*</span></h3>
                            <div class="lsid-check-grid">
                                @foreach ($receiverTypeOptions as $value => $label)
                                    <label class="lsid-check-card">
                                        <input type="checkbox" name="receiver_types[]" value="{{ $value }}" {{ in_array($value, $selectedReceiverTypes, true) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="lsid-dependent-field" data-dependent-field="receiver-type-other">
                                <label class="form-label">অন্যান্য ব্যক্তি, উল্লেখ করুন (Other People, please specify)</label>
                                <input type="text" name="receiver_type_other" class="form-control" value="{{ old('receiver_type_other', $isEdit ? $register->receiver_type_other : '') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="lsid-option-block">
                            <h3 class="lsid-section-title">তথ্য/সেবা প্রদানের ধরণ (Type of Information/Service Provided) <span class="text-danger">*</span></h3>
                            <div class="lsid-check-grid">
                                @foreach ($serviceTypeOptions as $value => $label)
                                    <label class="lsid-check-card">
                                        <input type="checkbox" name="service_types[]" value="{{ $value }}" {{ in_array($value, $selectedServiceTypes, true) ? 'checked' : '' }}>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <div class="lsid-dependent-field" data-dependent-field="service-type-other">
                                <label class="form-label">অন্যান্য, উল্লেখ করুন (Other, please specify)</label>
                                <input type="text" name="service_type_other" class="form-control" value="{{ old('service_type_other', $isEdit ? $register->service_type_other : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lsid-action-row">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i>
                {{ $isEdit ? 'Save Changes' : 'Save Register Entry' }}
            </button>
            @if ($isEdit)
                <a href="{{ route('lsid-register.manage') }}" class="btn btn-light">Back to Management</a>
            @endif
        </div>
    </form>
</section>
@endsection

@push('scripts')
<script>
    const lsidPngos = @json($pngos->map(fn ($pngo) => ['id' => $pngo->id, 'name' => $pngo->name, 'district_id' => $pngo->district_id])->values());

    function syncLsidPngoDropdown(root) {
        var districtSelect = root.querySelector('[data-lsid-district-select]');
        var pngoSelect = root.querySelector('[data-lsid-pngo-select]');

        if (!pngoSelect) {
            return;
        }

        var selectedPngo = pngoSelect.getAttribute('data-selected-pngo') || pngoSelect.value;
        var districtId = districtSelect ? districtSelect.value : (pngoSelect.getAttribute('data-fixed-district') || '');
        var availablePngos = lsidPngos.filter(function(pngo) {
            return districtId && String(pngo.district_id) === String(districtId);
        });

        pngoSelect.innerHTML = '<option value="">' + (districtId ? 'Select PNGO' : 'Select District First') + '</option>';
        availablePngos.forEach(function(pngo) {
            var option = document.createElement('option');
            option.value = pngo.id;
            option.textContent = pngo.name;
            option.selected = String(selectedPngo) === String(pngo.id);
            pngoSelect.appendChild(option);
        });
    }

    document.querySelectorAll('[data-lsid-district-select]').forEach(function(select) {
        select.addEventListener('change', function() {
            var form = select.closest('form') || document;
            var pngoSelect = form.querySelector('[data-lsid-pngo-select]');
            if (pngoSelect) {
                pngoSelect.setAttribute('data-selected-pngo', '');
            }
            syncLsidPngoDropdown(form);
        });
    });

    syncLsidPngoDropdown(document);

    function syncLsidDependentFields() {
        var receiverOther = document.querySelector('input[name="receiver_types[]"][value="Other People"]');
        var serviceOther = document.querySelector('input[name="service_types[]"][value="Other"]');
        var receiverField = document.querySelector('[data-dependent-field="receiver-type-other"]');
        var serviceField = document.querySelector('[data-dependent-field="service-type-other"]');

        if (receiverField && receiverOther) {
            receiverField.classList.toggle('is-visible', receiverOther.checked);
            receiverField.querySelector('input').disabled = !receiverOther.checked;
        }

        if (serviceField && serviceOther) {
            serviceField.classList.toggle('is-visible', serviceOther.checked);
            serviceField.querySelector('input').disabled = !serviceOther.checked;
        }
    }

    document.querySelectorAll('.lsid-check-card input').forEach(function(input) {
        function syncSelectedState() {
            if (input.type === 'radio') {
                document.querySelectorAll('input[name="' + input.name + '"]').forEach(function(radio) {
                    radio.closest('.lsid-check-card').classList.toggle('is-selected', radio.checked);
                });
                syncLsidDependentFields();
                return;
            }

            input.closest('.lsid-check-card').classList.toggle('is-selected', input.checked);
            syncLsidDependentFields();
        }

        input.addEventListener('change', syncSelectedState);
        syncSelectedState();
    });

    syncLsidDependentFields();
</script>
@endpush
