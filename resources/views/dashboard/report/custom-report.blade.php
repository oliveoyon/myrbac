@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')

@push('styles')
<style>
    .category {
        border: 2px solid #870093; /* Blue border for better visibility */
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        background-color: #f8f9fa; /* Light gray background */
    }

    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #870093;
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
                
                    <div class="card mb-3" style="border: 2px solid #870093;">
                        <div class="card-header text-bg-dark d-flex justify-content-between align-items-center">
                            <h5 class="card-title ">Select Interventions</h5>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#caseCardBody">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="caseCardBody">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm district_id" name="district_id" id="district_id">
                                                <option value="">All Districts</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm pngo_id" name="pngo_id" id="pngo_id">
                                                <option value="">All PNGO</option>
                                                @foreach ($pngos as $pngo)
                                                    <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
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
                <button type="submit" class="btn btn-primary mt-4">Generate Report</button>
            
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
