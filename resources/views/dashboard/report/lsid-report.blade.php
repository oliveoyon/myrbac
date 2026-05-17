@extends('dashboard.layouts.admin-layout')

@section('title', 'LSID Report')

@push('styles')
<style>
    .lsid-report-page {
        display: grid;
        gap: 16px;
    }

    .lsid-report-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
    }

    .lsid-report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 16px 18px;
        background: #202832;
        color: #fff;
    }

    .lsid-report-header h1,
    .lsid-report-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
    }

    .lsid-report-body {
        padding: 18px;
    }

    .lsid-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .lsid-filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .lsid-filter-actions .btn {
        min-height: 31px;
    }

    .lsid-filter-chip {
        display: inline-block;
        margin: 4px 6px 0 0;
        padding: 3px 8px;
        border-radius: 999px;
        background: #e8f5ee;
        color: #17643a;
        font-size: 12px;
        font-weight: 700;
    }

    .lsid-tag {
        display: inline-block;
        margin: 0 3px 4px 0;
        padding: 2px 7px;
        border-radius: 999px;
        background: #eef7f1;
        color: #235c47;
        font-size: 13px;
        font-weight: 600;
    }

    .lsid-report-note {
        padding: 18px;
        border: 1px dashed #bfd8cb;
        border-radius: 8px;
        background: #f8fcfa;
        color: #385448;
        text-align: center;
        font-weight: 650;
    }

    .lsid-print-table th,
    .lsid-print-table td {
        font-size: 13px;
        padding: 8px 9px;
        vertical-align: top;
    }

    .lsid-result-table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .lsid-screen-table {
        min-width: 980px;
    }

    @media (max-width: 992px) {
        .lsid-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .lsid-report-page {
            gap: 12px;
        }

        .lsid-report-panel {
            border-radius: 7px;
        }

        .lsid-report-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 12px 14px;
        }

        .lsid-report-header h1,
        .lsid-report-header h2 {
            font-size: 16px;
            line-height: 1.3;
        }

        .lsid-report-header small {
            font-size: 12px;
            line-height: 1.4;
        }

        .lsid-report-header .btn {
            width: 100%;
            min-height: 38px;
        }

        .lsid-report-body {
            padding: 12px;
        }

        .lsid-filter-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .lsid-filter-grid .form-label {
            margin-bottom: 5px;
            color: #475569;
            font-size: 12px;
            font-weight: 800;
        }

        .lsid-filter-grid .form-control {
            min-height: 38px;
            font-size: 13px;
        }

        .lsid-filter-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .lsid-filter-actions .btn {
            width: 100%;
            min-height: 38px;
            font-size: 13px;
            font-weight: 700;
        }

        .lsid-report-note {
            padding: 16px 12px;
            font-size: 13px;
            line-height: 1.45;
        }

        .lsid-result-table-wrap {
            margin: 0 -2px;
        }

        .lsid-screen-table {
            min-width: 900px;
        }

        .lsid-print-table th,
        .lsid-print-table td {
            font-size: 12px;
            padding: 7px 8px;
        }

        .lsid-official-scope {
            min-width: 640px;
        }
    }

    @media (max-width: 390px) {
        .lsid-screen-table {
            min-width: 840px;
        }
    }
</style>
@endpush

@section('content')
<section class="lsid-report-page">
    <div class="lsid-report-panel">
        <div class="lsid-report-header">
            <div>
                <h1>LSID Tabular Report</h1>
                <small>Filtered by your permitted district/PNGO scope.</small>
            </div>
        </div>
        <div class="lsid-report-body">
            <form method="GET" action="{{ route('lsid-register.report') }}" class="lsid-filter-grid">
                @if (!auth()->user()->district_id)
                    <div>
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-control form-control-sm" required data-lsid-district-select>
                            <option value="">Select District</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if (!auth()->user()->pngo_id)
                    <div>
                        <label class="form-label">PNGO</label>
                        <select name="pngo_id" class="form-control form-control-sm" data-lsid-pngo-select data-selected-pngo="{{ $filters['pngo_id'] ?? '' }}" data-fixed-district="{{ auth()->user()->district_id }}">
                            <option value="">Select District First</option>
                        </select>
                    </div>
                @endif
                <div>
                    <label class="form-label">Intervention Taken</label>
                    <select name="intervention" class="form-control form-control-sm">
                        <option value="">All Interventions</option>
                        @foreach ($interventionOptions as $value => $label)
                            <option value="{{ $value }}" {{ (string) ($filters['intervention'] ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Sex</label>
                    <select name="sex" class="form-control form-control-sm">
                        <option value="">All Sex</option>
                        @foreach ($sexOptions as $value => $label)
                            <option value="{{ $value }}" {{ (string) ($filters['sex'] ?? '') === (string) $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label d-none d-lg-block">&nbsp;</label>
                    <div class="lsid-filter-actions">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Apply
                        </button>
                        <a href="{{ route('lsid-register.report') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="lsid-report-panel">
        <div class="lsid-report-header">
            <h2>Report Result</h2>
            @can('Generate LSID Report')
                @if ($reportRequested && $districtSelected && $registers->count())
                <button class="btn btn-success btn-sm" id="printButton">
                    <i class="fas fa-print"></i> Print PDF
                </button>
                @endif
            @endcan
        </div>
        <div class="lsid-report-body table-responsive">
            @if (!$reportRequested)
                <div class="lsid-report-note">Please select a district and apply filter to load the LSID report.</div>
            @elseif (!$districtSelected)
                <div class="lsid-report-note text-danger">District is mandatory for this report.</div>
            @else
                <div id="reportDiv">
                    <style>
                        .lsid-pdf-table { width: 100%; border-collapse: collapse; font-size: 12px; }
                        .lsid-pdf-table th, .lsid-pdf-table td { border: 1px solid #d7dee3; padding: 6px 7px; vertical-align: top; line-height: 1.35; }
                        .lsid-pdf-table th { background: #f2f4f7; color: #111827; font-weight: 700; }
                        .lsid-pdf-table td { color: #111827; }
                        .lsid-pdf-list { margin: 0; padding-left: 13px; }
                        .lsid-pdf-list li { margin: 0 0 2px 0; padding: 0; color: #111827; font-size: 12px; line-height: 1.35; }
                        .lsid-official-scope { margin-bottom: 12px; border: 1px solid #d7dee3; background: #fafafa; }
                        .lsid-official-scope td { border: 1px solid #d7dee3; padding: 7px 9px; font-size: 12px; color: #111827; }
                        .lsid-official-scope .label { width: 110px; font-weight: 700; background: #f2f4f7; }
                    </style>
                    <table class="lsid-official-scope" style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
                        <tr>
                            <td class="label">District</td>
                            <td>{{ $reportDistrictName ?: '-' }}</td>
                            <td class="label">PNGO</td>
                            <td>{{ $reportPngoName ?: 'All PNGO' }}</td>
                        </tr>
                    </table>

                <div class="lsid-result-table-wrap">
                <table class="table table-bordered table-striped table-hover table-sm lsid-print-table lsid-pdf-table lsid-screen-table">
                    <thead>
                        <tr>
                            <th style="width: 36px;">SL</th>
                            <th>Date</th>
                            <th>Service Given By</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Sex</th>
                            <th>Other Info</th>
                            <th>Receiver Type</th>
                            <th>Intervention</th>
                            <th>Service Provided</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($registers as $register)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($register->service_date)->format('j M, Y') }}</td>
                                <td>{{ $register->creator->name ?? '-' }}</td>
                                <td>{{ $register->receiver_name }}</td>
                                <td>{{ $register->mobile_number ?: '-' }}</td>
                                <td>{{ $sexOptions[$register->sex] ?? $register->sex }}</td>
                                <td>
                                    <ul class="lsid-pdf-list">
                                        @foreach (($register->other_information ?? []) as $item)
                                            <li>{{ $otherInformationOptions[$item] ?? $item }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="lsid-pdf-list">
                                        @foreach (($register->receiver_types ?? []) as $type)
                                            <li>{{ $receiverTypeOptions[$type] ?? $type }}</li>
                                        @endforeach
                                        @if ($register->receiver_type_other)
                                            <li>{{ $register->receiver_type_other }}</li>
                                        @endif
                                    </ul>
                                </td>
                                <td>
                                    <ul class="lsid-pdf-list">
                                        @foreach (($register->interventions_taken ?? []) as $intervention)
                                            <li>{{ $interventionOptions[$intervention] ?? $intervention }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="lsid-pdf-list">
                                        @foreach (($register->service_types ?? []) as $type)
                                            <li>{{ $serviceTypeOptions[$type] ?? $type }}</li>
                                        @endforeach
                                        @if ($register->service_type_other)
                                            <li>{{ $register->service_type_other }}</li>
                                        @endif
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No LSID register entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        pngoSelect.innerHTML = '<option value="">' + (districtId ? 'All PNGO' : 'Select District First') + '</option>';
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

    document.querySelectorAll('form').forEach(function(form) {
        syncLsidPngoDropdown(form);
    });

    $('#printButton').click(function() {
        $('#loader-overlay').show();

        $.ajax({
            url: '{{ route('lsid-register.report.pdf') }}',
            method: 'POST',
            data: {
                pdf_data: $('#reportDiv').html(),
                title: 'LSID Register Report',
                orientation: 'L',
                fname: 'lsid-register-report.pdf',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url) {
                    window.open(response.pdf_url, '_blank');
                } else {
                    alert('Error generating PDF. Please try again.');
                }
            },
            error: function() {
                alert('Error generating PDF. Please try again.');
            },
            complete: function() {
                $('#loader-overlay').hide();
            }
        });
    });
</script>
@endpush
