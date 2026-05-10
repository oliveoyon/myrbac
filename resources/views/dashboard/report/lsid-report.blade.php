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
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
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
        font-size: 12px;
        font-weight: 600;
    }

    @media (max-width: 992px) {
        .lsid-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .lsid-filter-grid {
            grid-template-columns: 1fr;
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
                        <select name="district_id" class="form-control form-control-sm">
                            <option value="">All Districts</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @if (!auth()->user()->pngo_id)
                    <div>
                        <label class="form-label">PNGO</label>
                        <select name="pngo_id" class="form-control form-control-sm">
                            <option value="">All PNGO</option>
                            @foreach ($pngos as $pngo)
                                <option value="{{ $pngo->id }}" {{ (string) ($filters['pngo_id'] ?? '') === (string) $pngo->id ? 'selected' : '' }}>{{ $pngo->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div>
                    <label class="form-label">From Date</label>
                    <input type="date" name="from_date" class="form-control form-control-sm" value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div>
                    <label class="form-label">To Date</label>
                    <input type="date" name="to_date" class="form-control form-control-sm" value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
                    <a href="{{ route('lsid-register.report') }}" class="btn btn-light btn-sm w-100 mt-1">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="lsid-report-panel">
        <div class="lsid-report-header">
            <h2>Report Result</h2>
            @can('Generate LSID Report')
                <button class="btn btn-success btn-sm" id="printButton">
                    <i class="fas fa-print"></i> Print PDF
                </button>
            @endcan
        </div>
        <div class="lsid-report-body table-responsive">
            <div id="reportDiv">
                @if(!empty($appliedFilters))
                    <div style="margin-bottom: 12px; padding: 10px 12px; border: 1px solid #d8e6de; background: #f6fbf8; border-radius: 6px;">
                        <strong>Applied Filters:</strong>
                        @foreach($appliedFilters as $label => $value)
                            <span class="lsid-filter-chip">{{ $label }}: {{ $value }}</span>
                        @endforeach
                    </div>
                @endif

                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>District</th>
                            <th>PNGO</th>
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
                                <td>{{ optional($register->service_date)->format('Y-m-d') }}</td>
                                <td>{{ $register->district->name ?? '-' }}</td>
                                <td>{{ $register->pngo->name ?? '-' }}</td>
                                <td>{{ $register->receiver_name }}</td>
                                <td>{{ $register->mobile_number ?: '-' }}</td>
                                <td>{{ $sexOptions[$register->sex] ?? $register->sex }}</td>
                                <td>
                                    @foreach (($register->other_information ?? []) as $item)
                                        <span class="lsid-tag">{{ $otherInformationOptions[$item] ?? $item }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (($register->receiver_types ?? []) as $type)
                                        <span class="lsid-tag">{{ $receiverTypeOptions[$type] ?? $type }}</span>
                                    @endforeach
                                    @if ($register->receiver_type_other)
                                        <span class="lsid-tag">{{ $register->receiver_type_other }}</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach (($register->interventions_taken ?? []) as $intervention)
                                        <span class="lsid-tag">{{ $interventionOptions[$intervention] ?? $intervention }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach (($register->service_types ?? []) as $type)
                                        <span class="lsid-tag">{{ $serviceTypeOptions[$type] ?? $type }}</span>
                                    @endforeach
                                    @if ($register->service_type_other)
                                        <span class="lsid-tag">{{ $register->service_type_other }}</span>
                                    @endif
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
    </div>
</section>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
