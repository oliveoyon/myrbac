@extends('dashboard.layouts.admin-layout')

@section('title', 'Intervention Report')

@push('styles')
<style>
    .report-builder {
        display: grid;
        gap: 18px;
    }

    .report-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 22px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background:
            linear-gradient(135deg, rgba(195, 15, 8, 0.08), rgba(23, 133, 59, 0.06)),
            #ffffff;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.07);
    }

    .report-hero h1 {
        margin: 0;
        color: #1f2937;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .report-hero p {
        max-width: 780px;
        margin: 7px 0 0;
        color: #64748b;
        font-size: 14px;
    }

    .report-hero-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        border-radius: 8px;
        background: #c30f08;
        color: #fff;
        font-size: 22px;
        box-shadow: 0 12px 24px rgba(195, 15, 8, 0.24);
    }

    .report-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.06);
    }

    .report-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        background: #202832;
        color: #fff;
    }

    .report-panel-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .report-panel-title h2 {
        margin: 0;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .report-panel-title span {
        display: block;
        margin-top: 2px;
        color: #cbd5e1;
        font-size: 12px;
        font-weight: 500;
    }

    .report-panel-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .report-panel-toggle {
        width: 36px;
        height: 36px;
        padding: 0;
        border-color: rgba(255, 255, 255, 0.35);
        color: #fff;
    }

    .report-panel-body {
        padding: 18px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .filter-item label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 12px;
        font-weight: 800;
    }

    .field-section {
        display: grid;
        gap: 14px;
    }

    .field-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
    }

    .field-toolbar h2 {
        margin: 0;
        color: #1f2937;
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .field-toolbar p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .category-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .report-category {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    .report-category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 15px;
        border-bottom: 1px solid #f0d2cf;
        background: #fff7f6;
        color: #c30f08;
    }

    .report-category-title {
        display: flex;
        align-items: center;
        gap: 9px;
        min-width: 0;
        font-size: 15px;
        font-weight: 800;
    }

    .report-category-title i {
        opacity: 0.9;
    }

    .select-all-container {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #374151;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
        cursor: pointer;
    }

    .toggle-switch {
        position: relative;
        width: 42px;
        height: 22px;
        flex: 0 0 auto;
    }

    .toggle-switch input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        inset: 0;
        cursor: pointer;
        border-radius: 999px;
        background-color: #d1d5db;
        transition: background-color 0.22s ease;
    }

    .slider:before {
        position: absolute;
        content: "";
        width: 16px;
        height: 16px;
        left: 3px;
        top: 3px;
        border-radius: 50%;
        background-color: #fff;
        box-shadow: 0 2px 7px rgba(15, 23, 42, 0.22);
        transition: transform 0.22s ease;
    }

    input:checked + .slider {
        background-color: #c30f08;
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    .fields {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 8px;
        padding: 14px;
        background: #ffffff;
    }

    .field {
        min-height: 40px;
        border: 1px solid #d8dee6;
        border-radius: 6px;
        background-color: #ffffff;
        transition: border-color 0.18s ease, background-color 0.18s ease;
    }

    .field:hover {
        border-color: #c30f08;
        background-color: #fff7f6;
    }

    .field label {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 9px 10px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.35;
        cursor: pointer;
    }

    .field input[type="checkbox"] {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: #c30f08;
        flex: 0 0 auto;
    }

    .field:has(input[type="checkbox"]:checked) {
        border-color: #c30f08;
        background-color: #fff7f6;
    }

    .field:has(input[type="checkbox"]:checked) label {
        color: #9d0c06;
        font-weight: 700;
    }

    .report-submit-bar {
        position: sticky;
        bottom: 0;
        z-index: 5;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 14px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.94);
        box-shadow: 0 -8px 26px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(10px);
    }

    .report-submit-bar span {
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
    }

    .report-submit-bar .btn {
        min-width: 190px;
    }

    @media (max-width: 1200px) {
        .filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .category-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .report-builder {
            gap: 14px;
        }

        .report-hero {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .report-hero h1 {
            font-size: 20px;
        }

        .report-panel-header,
        .field-toolbar,
        .report-category-header,
        .report-submit-bar {
            align-items: stretch;
            flex-direction: column;
        }

        .report-panel-body {
            padding: 14px;
        }

        .filter-grid,
        .fields {
            grid-template-columns: 1fr;
        }

        .report-panel-toggle,
        .report-submit-bar .btn {
            width: 100%;
        }

        .select-all-container {
            justify-content: space-between;
            width: 100%;
        }

        .report-submit-bar {
            border-radius: 8px 8px 0 0;
        }
    }
</style>
@endpush

@section('content')
<form action="{{ route('custom.report.generate') }}" method="POST" class="report-builder">
    @csrf

    <section class="report-hero">
        <div>
            <h1>Intervention Report Builder</h1>
            <p>Filter the cases first, then choose the intervention fields you want to include in the generated report.</p>
        </div>
        <div class="report-hero-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
    </section>

    <section class="report-panel">
        <div class="report-panel-header">
            <div class="report-panel-title">
                <div class="report-panel-icon">
                    <i class="fas fa-filter"></i>
                </div>
                <div>
                    <h2>Report Filters</h2>
                    <span>Use one or more filters to narrow the result set</span>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-light report-panel-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#caseCardBody" aria-expanded="true" aria-controls="caseCardBody">
                <i class="fas fa-minus"></i>
            </button>
        </div>

        <div class="collapse show" id="caseCardBody">
            <div class="report-panel-body">
                <div class="filter-grid">
                    <div class="filter-item">
                        <label for="district_id">District</label>
                        <select class="form-control form-control-sm district_id" name="district_id" id="district_id">
                            @php
                            $userDistrictId = auth()->user()->district_id;
                            @endphp

                            @if ($userDistrictId)
                            @foreach ($districts as $district)
                            @if ($district->id == $userDistrictId)
                            <option value="{{ $district->id }}" selected>{{ $district->name }}</option>
                            @endif
                            @endforeach
                            @else
                            <option value="">All Districts</option>
                            @foreach ($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="pngo_id">PNGO</label>
                        <select class="form-control form-control-sm pngo_id" name="pngo_id" id="pngo_id">
                            @php
                            $userPngoId = auth()->user()->pngo_id;
                            @endphp

                            @if ($userPngoId)
                            @foreach ($pngos as $pngo)
                            @if ($pngo->id == $userPngoId)
                            <option value="{{ $pngo->id }}" selected>{{ $pngo->name }}</option>
                            @endif
                            @endforeach
                            @else
                            <option value="">All PNGO</option>
                            @foreach ($pngos as $pngo)
                            <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="institute">Institute</label>
                        <select class="form-control form-control-sm institute" name="institute" id="institute">
                            <option value="">All Institute</option>
                            <option value="Court">Court</option>
                            <option value="Prison">Prison</option>
                            <option value="Police Station">Police Station</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="education_level">Education Level</label>
                        <select class="form-control form-control-sm" id="education_level" name="education_level">
                            <option value="">Education Level</option>
                            <option value="Illiterate">Illiterate</option>
                            <option value="Can Sign">Can Sign</option>
                            <option value="Primary">Primary</option>
                            <option value="Secondary">Secondary</option>
                            <option value="Higher Secondary">Higher Secondary</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Postgraduate">Postgraduate</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="special_condition">Disability Status</label>
                        <select class="form-control form-control-sm" id="special_condition" name="special_condition">
                            <option value="">Disability Status</option>
                            <option value="Critical Ill">Critical Ill</option>
                            <option value="Disabled">Disabled</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="status">Status</label>
                        <select class="form-control form-control-sm status" name="status" id="status">
                            <option value="">Status</option>
                            <option value="1">Pending</option>
                            <option value="2">Verified by DPO</option>
                            <option value="3">Verified by MNEO</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="application_mode">Application Mode</label>
                        <select class="form-control form-control-sm" id="application_mode" name="application_mode">
                            <option value="">Application Mode</option>
                            <option value="Online">Online</option>
                            <option value="Office Application">Office Application</option>
                        </select>
                    </div>

                    <div class="filter-item">
                        <label for="type_of_service">Type of Service</label>
                        <select class="form-control form-control-sm" id="type_of_service" name="type_of_service">
                            <option value="">Type of Service</option>
                            <option value="Legal Advice">Legal Advice</option>
                            <option value="Alternate Dispute Resolution">Alternate Dispute Resolution</option>
                            <option value="Filing New Lawsuit">Filing New Lawsuit</option>
                            <option value="Legal Aid in Existing Case">Legal Aid in Existing Case</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="field-section">
        <div class="field-toolbar">
            <div>
                <h2>Report Fields</h2>
                <p>Select the intervention data points that should appear in the report.</p>
            </div>
        </div>

        <div class="category-grid">
            @foreach($fields as $category => $fieldsArray)
            <div class="report-category">
                <div class="report-category-header">
                    <div class="report-category-title">
                        <i class="fas fa-layer-group"></i>
                        <span>{{ ucwords(str_replace('_', ' ', $category)) }}</span>
                    </div>
                    <label class="select-all-container">
                        <span>Select All</span>
                        <span class="toggle-switch">
                            <input type="checkbox" class="select-all" data-category="{{ $category }}">
                            <span class="slider"></span>
                        </span>
                    </label>
                </div>

                <div class="fields">
                    @foreach($fieldsArray as $fieldKey => $fieldLabel)
                    <div class="field">
                        <label>
                            <input type="checkbox" name="fields[{{ $category }}][]" value="{{ $fieldKey }}">
                            <span>{{ $fieldLabel }}</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </section>

    <div class="report-submit-bar">
        <span>Generate the report after choosing at least one field.</span>
        <button type="submit" class="btn btn-success">
            <i class="fas fa-file-alt"></i>
            Generate Report
        </button>
    </div>
</form>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const category = selectAllCheckbox.getAttribute('data-category');
            const checkboxes = document.querySelectorAll(`input[name='fields[${category}][]']`);

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllCheckbox.checked;
            });
        });
    });
</script>
@endpush
