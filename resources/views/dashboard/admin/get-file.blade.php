@extends('dashboard.layouts.admin-layout')

@section('title', 'Court Prison Data')

@push('styles')
    <style>
        /* Global Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f6f9;
            color: #333;
            padding: 20px;
        }

        .accordion-item {
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .accordion-button {
            /* background-color: #4CAF50; */
            background-color: #545b62;
            color: white;
            font-weight: bold;
            padding: 15px;
            font-size: 16px;
            border: none;
            text-align: left;
            border-radius: 5px;
            width: 100%;
            transition: all 0.3s ease;
        }

        .accordion-button:hover,
        .accordion-button:focus {
            background-color: #45a049;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .accordion-button:not(.collapsed) {
            background-color: #45a049;
        }

        .accordion-collapse {
            padding: 20px;
            border-top: 1px solid #ddd;
        }

        /* Form Field Styles */
        .form-label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
        }

        .select2-container .form-select {
            padding: 10px;
            border-radius: 5px;
        }

        /* Apply focus effect to form-select dropdowns */
        .form-select:focus {
            border-color: #4CAF50 !important;
            /* Green border like input fields */
            box-shadow: 0 0 8px rgba(76, 175, 80, 0.3) !important;
            outline: none;
        }

        /* Improve dropdown appearance */
        .form-select {
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
            font-size: 14px;
            width: 100%;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: white;
        }

        /* Optional: Custom dropdown arrow */
        .form-select {
            appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="gray"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>');
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px;
            padding-right: 30px;
            /* Space for dropdown arrow */
        }

        /* Column Spacing and Alignment */
        .row.g-3 {
            margin-bottom: 20px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 767px) {
            .accordion-button {
                font-size: 14px;
                padding: 10px;
            }

            .form-control,
            .form-select {
                font-size: 14px;
            }

            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Accordion Header Styles */
        .accordion-header {
            padding: 10px;
            background-color: #f1f1f1;
            border-bottom: 1px solid #ddd;
        }

        .accordion-body {
            background-color: #fafafa;
            padding: 20px;
        }

        /* Transitions and Animations */
        .accordion-collapse {
            transition: height 0.3s ease-out;
        }
    </style>
@endpush

@section('content')


    <section>
        <div class="container-fluid">
            <div class="row">
                @foreach($caseFiles as $upload)
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                @php
                                    $filePath = asset('storage/uploads/formal_cases/' . $upload->file_name);
                                    $extension = strtolower(pathinfo($upload->file_name, PATHINFO_EXTENSION));
                                @endphp
            
                                @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $filePath }}" alt="{{ $upload->file_name }}" class="img-fluid rounded mb-2" style="max-height: 300px;">
                                @elseif($extension === 'pdf')
                                    <embed src="{{ $filePath }}" type="application/pdf" width="100%" height="300px" class="rounded border" />
                                @else
                                    <p class="text-muted">Unsupported file type: {{ $extension }}</p>
                                @endif
            
                                <p class="mt-2 mb-0 small text-secondary">{{ $upload->file_name }}</p>
                                <a href="{{ $filePath }}" target="_blank" class="btn btn-sm btn-primary mt-2">Open File</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            

        </div>
    </section>

@endsection

@push('scripts')

@endpush
