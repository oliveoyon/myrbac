@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')
@push('styles')
    <style>
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .category {
            border: 1px solid #ccc;
            padding: 10px;
        }

        .fields {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .field {
            display: flex;
            align-items: center;
        }

        .field input {
            margin-right: 5px;
        }
    </style>
@endpush

@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
            <h2>Custom Report</h2>
                <!-- Start of the form -->
        <form action="{{ route('custom.report.generate') }}" method="POST">
            @csrf
            
            <!-- Create a grid with 3 columns for categories -->
            <div class="category-grid">
                @foreach($fields as $category => $fieldsArray)
                    <div class="category">
                        <h4>{{ ucwords(str_replace('_', ' ', $category)) }}</h4>
                        
                        <!-- Select All Checkbox for Category -->
                        <label>
                            <input type="checkbox" class="select-all" data-category="{{ $category }}">
                            Select All {{ ucwords(str_replace('_', ' ', $category)) }}
                        </label>
                        
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
            </div>
            
            <!-- Submit button -->
            <button type="submit" class="btn btn-primary mt-4">Generate Report</button>
        </form>
            </div>

        </div>

     
    </section>
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
