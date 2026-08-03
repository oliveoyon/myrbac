@extends('dashboard.layouts.admin-layout')

@section('title', 'District Institution Report')

@push('styles')
<style>
    .district-institution-report {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .di-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .di-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .di-panel-header h1,
    .di-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .di-panel-header small {
        color: #64748b;
    }

    .di-panel-body {
        padding: 14px 16px;
    }

    .di-filter {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
        align-items: end;
    }

    .di-filter label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .di-filter-actions {
        display: flex;
        gap: 8px;
    }

    .di-filter .form-control:disabled {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .di-filter-hint {
        margin-top: 10px;
        color: #64748b;
        font-size: 12px;
    }

    .di-table {
        margin: 0;
        font-size: 13px;
    }

    .di-table th {
        white-space: nowrap;
        color: #374151;
        background: #f8fafc;
        vertical-align: middle;
    }

    .di-table td {
        vertical-align: middle;
    }

    .di-table td:not(:first-child),
    .di-table th:not(:first-child) {
        text-align: center;
    }

    .di-total-row td {
        background: #f1f5f9 !important;
        color: #111827;
        font-weight: 800;
    }

    .di-empty {
        padding: 18px;
        color: #64748b;
        text-align: center;
        border: 1px dashed #d7dee3;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .di-filter-chip {
        display: inline-flex;
        margin: 0 6px 6px 0;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eef7f1;
        color: #285d49;
        font-size: 12px;
        font-weight: 700;
    }

    @media (max-width: 992px) {
        .di-filter {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .di-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .di-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .di-filter-actions,
        .di-filter-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .di-filter {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="district-institution-report">
    <div class="di-panel">
        <div class="di-panel-header">
            <div>
                <h1>District and Institution Wise Report</h1>
                <small>Official intervention count by district across Court, Police Station, and Prison.</small>
            </div>
            @if ($rows->count())
                <button type="button" class="btn btn-success btn-sm" id="printButton">
                    <i class="fas fa-print"></i> Print PDF
                </button>
            @endif
        </div>
        <div class="di-panel-body">
            <form method="GET" action="{{ route('district.institution.report') }}" class="di-filter">
                <div>
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control form-control-sm" data-di-district>
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pngo_id">PNGO</label>
                    <select name="pngo_id" id="pngo_id" class="form-control form-control-sm" data-di-pngo data-selected-pngo="{{ $filters['pngo_id'] ?? '' }}">
                        <option value="">All PNGOs</option>
                    </select>
                </div>
                <div>
                    <label for="month">Month</label>
                    <select name="month" id="month" class="form-control form-control-sm" data-di-month>
                        <option value="">Custom Date Range</option>
                        @foreach ($monthOptions as $option)
                            <option value="{{ $option['value'] }}" {{ (string) ($filters['month'] ?? '') === $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{ empty($filters['month']) ? ($filters['from_date'] ?? '') : '' }}" data-di-date>
                </div>
                <div>
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{ empty($filters['month']) ? ($filters['to_date'] ?? '') : '' }}" data-di-date>
                </div>
                <div class="di-filter-actions">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('district.institution.report') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            <div class="di-filter-hint">Use Month for monthly reporting. Start typing a custom date range to disable the month shortcut.</div>

            @if (!empty($appliedFilters))
                <div class="mt-3">
                    @foreach ($appliedFilters as $label => $value)
                        <span class="di-filter-chip">{{ $label }}: {{ $value }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="di-panel">
        <div class="di-panel-header">
            <div>
                <h2>Report Result</h2>
                <small>Total counts are based on the official intervention date logic and verified status greater than 1.</small>
            </div>
        </div>
        <div class="di-panel-body">
            @if ($rows->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm di-table">
                        <thead>
                            <tr>
                                <th>District</th>
                                <th>Court</th>
                                <th>Police Station</th>
                                <th>Prison</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ $row['district_name'] }}</td>
                                    <td>{{ $row['court'] }}</td>
                                    <td>{{ $row['police_station'] }}</td>
                                    <td>{{ $row['prison'] }}</td>
                                    <td><strong>{{ $row['total'] }}</strong></td>
                                </tr>
                            @endforeach
                            <tr class="di-total-row">
                                <td>Total</td>
                                <td>{{ $rows->sum('court') }}</td>
                                <td>{{ $rows->sum('police_station') }}</td>
                                <td>{{ $rows->sum('prison') }}</td>
                                <td><strong>{{ $rows->sum('total') }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="di-empty">No district institution data found for the selected filters.</div>
            @endif
        </div>
    </div>
</section>

<div class="modal fade modal-fullscreen" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">District and Institution Wise Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe name="pdfFrame" id="pdfFrame" style="width: 100%; height: 86vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const districtInstitutionPngos = @json($pngos->map(fn ($pngo) => ['id' => $pngo->id, 'name' => $pngo->name, 'district_id' => $pngo->district_id])->values());

    function syncDistrictInstitutionPngoDropdown() {
        const districtSelect = document.querySelector('[data-di-district]');
        const pngoSelect = document.querySelector('[data-di-pngo]');

        if (!districtSelect || !pngoSelect) {
            return;
        }

        const districtId = districtSelect.value;
        const selectedPngo = pngoSelect.getAttribute('data-selected-pngo') || pngoSelect.value;
        const available = districtId
            ? districtInstitutionPngos.filter((pngo) => String(pngo.district_id) === String(districtId))
            : districtInstitutionPngos;

        pngoSelect.innerHTML = '<option value="">All PNGOs</option>';
        available.forEach((pngo) => {
            const option = document.createElement('option');
            option.value = pngo.id;
            option.textContent = pngo.name;
            option.selected = String(selectedPngo) === String(pngo.id);
            pngoSelect.appendChild(option);
        });
    }

    document.querySelector('[data-di-district]')?.addEventListener('change', function () {
        document.querySelector('[data-di-pngo]')?.setAttribute('data-selected-pngo', '');
        syncDistrictInstitutionPngoDropdown();
    });

    function syncDistrictInstitutionDateMode() {
        const month = document.querySelector('[data-di-month]');
        const dates = document.querySelectorAll('[data-di-date]');

        if (!month) {
            return;
        }

        const hasCustomDate = Array.from(dates).some((input) => input.value);

        if (month.value) {
            dates.forEach((input) => {
                input.value = '';
                input.disabled = true;
            });
            month.disabled = false;
            return;
        }

        dates.forEach((input) => {
            input.disabled = false;
        });
        month.disabled = hasCustomDate;
    }

    document.querySelector('[data-di-month]')?.addEventListener('change', syncDistrictInstitutionDateMode);
    document.querySelectorAll('[data-di-date]').forEach((input) => {
        input.addEventListener('input', syncDistrictInstitutionDateMode);
        input.addEventListener('change', syncDistrictInstitutionDateMode);
    });

    syncDistrictInstitutionPngoDropdown();
    syncDistrictInstitutionDateMode();

    document.getElementById('printButton')?.addEventListener('click', function(event) {
        event.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
        document.getElementById('pdfFrame').src = '{{ route('district.institution.report.print', [], false) }}' + window.location.search;
    });
</script>
@endpush
