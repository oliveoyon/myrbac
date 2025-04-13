@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')

@push('styles')
<style>
    .category {
        border: 2px solid #005e17; /* Blue border for better visibility */
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f8f9fa; /* Light gray background */
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #005e17;
        color: #fff;
        padding: 10px;
        border-radius: 6px;
        font-weight: bold;
        font-size: 18px;
    }

    .fields {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); /* Fields wrap automatically */
        gap: 10px;
        margin-top: 10px;
    }

    .field {
        display: flex;
        align-items: center;
        background-color: #ffffff;
        padding: 8px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .field input {
        margin-right: 8px;
    }

    /* Toggle switch for Select All */
    .select-all-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 20px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: 0.4s;
        border-radius: 20px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 14px;
        width: 14px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #28a745;
    }

    input:checked + .slider:before {
        transform: translateX(20px);
    }

    

</style>
@endpush

@section('content')

<form action="{{ route('custom.report.generate') }}" method="POST">
@csrf
<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                
                    <div class="card mb-3" style="border: 2px solid #005e17;">
                        <div class="card-header text-bg-dark d-flex justify-content-between align-items-center">
                            <h5 class="card-title ">Select Interventions</h5>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#caseCardBody">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="caseCardBody">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm institute" name="institute" id="institute">
                                                <option value="">All Institute</option>
                                                <option value="Court">Court</option>
                                                <option value="Prison">Prison</option>
                                                <option value="Police Station">Police Station</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
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
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm" id="special_condition" name="special_condition">
                                                <option value="">Disability Status</option>
                                                <option value="Critical Ill">Critical Ill</option>
                                                <option value="Disabled">Disabled</option>
                                                <option value="Other">Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mb-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm status" name="status"
                                                id="status">
                                                <option value="">Status</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Verified by DPO</option>
                                                <option value="3">Verified by MNEO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select class="form-control form-control-sm" id="application_mode" name="application_mode">
                                            <option value="">Application Mode</option>
                                            <option value="Online">Online</option>
                                            <option value="Office Application">Office Application</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <select class="form-control form-control-sm" id="type_of_service" name="type_of_service">
                                            <option value="">Type of Service</option>
                                            <option value="Legal Advice">Legal Advice</option>
                                            <option value="Alternate Dispute Resolution">Alternate Dispute Resolution
                                            </option>
                                            <option value="Filing New Lawsuit">Filing New Lawsuit</option>
                                            <option value="Legal Aid in Existing Case">Legal Aid in Existing Case</option>
                                        </select>
                                    </div>
                                    
                                </div> <!-- row -->
                            </div>
                        </div> <!-- collapse -->
                    </div>
               
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container-fluid">
        <div class="row mb-3">

                @foreach($fields as $category => $fieldsArray)
                    <div class="category">
                        <!-- Category Header with Select All -->
                        <div class="category-header">
                            <span>{{ ucwords(str_replace('_', ' ', $category)) }}</span>
                            <label class="select-all-container">
                                <span>Select All</span>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="select-all" data-category="{{ $category }}">
                                    <span class="slider"></span>
                                </label>
                            </label>
                        </div>

                        <!-- Fields displayed in columns within the row -->
                        <div class="fields">
                            @foreach($fieldsArray as $fieldKey => $fieldLabel)
                                <div class="field">
                                    <label>
                                        <input type="checkbox" name="fields[{{ $category }}][]" value="{{ $fieldKey }}">
                                        {{ $fieldLabel }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <!-- Submit button -->
                    <button type="submit" class="btn btn-success">Generate Report</button>
            
        </div>
    </div>
</section>
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
