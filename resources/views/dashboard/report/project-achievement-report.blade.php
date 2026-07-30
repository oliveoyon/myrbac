@extends('dashboard.layouts.admin-layout')

@section('title', 'Project Achievement Report')

@push('styles')
<style>
    .achievement-report {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .achievement-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .achievement-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .achievement-panel-header h1,
    .achievement-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .achievement-panel-header small {
        color: #64748b;
    }

    .achievement-panel-body {
        padding: 14px 16px;
    }

    .achievement-filter {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
        align-items: end;
    }

    .achievement-filter label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .achievement-filter .form-control:disabled {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .achievement-filter-actions {
        display: flex;
        gap: 8px;
    }

    .achievement-hint {
        margin-top: 10px;
        color: #64748b;
        font-size: 12px;
    }

    .applied-filter {
        display: inline-flex;
        margin: 0 6px 6px 0;
        padding: 4px 8px;
        border-radius: 999px;
        background: #eef7f1;
        color: #285d49;
        font-size: 12px;
        font-weight: 700;
    }

    .achievement-table {
        margin: 0;
        font-size: 13px;
    }

    .achievement-table th {
        white-space: nowrap;
        color: #374151;
        background: #f8fafc;
    }

    .achievement-table td {
        vertical-align: middle;
    }

    .achievement-serial {
        width: 52px;
        text-align: center;
        font-weight: 800;
    }

    .achievement-count {
        width: 120px;
        text-align: center;
        font-weight: 850;
        color: #166534;
    }

    .achievement-unit {
        width: 70px;
        text-align: center;
    }

    .achievement-total-row td {
        background: #f1f5f9 !important;
        color: #111827;
        font-weight: 800;
    }

    @media (max-width: 992px) {
        .achievement-filter {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .achievement-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .achievement-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .achievement-filter-actions,
        .achievement-filter-actions .btn {
            width: 100%;
        }

        .achievement-table {
            font-size: 12px;
        }
    }

    @media (max-width: 480px) {
        .achievement-filter {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="achievement-report">
    <div class="achievement-panel">
        <div class="achievement-panel-header">
            <div>
                <h1>Project Achievement Endorsement Report</h1>
                <small>Monthly achievement counts using intervention, LSID, and DLAO referral logic.</small>
            </div>
            <button type="button" class="btn btn-success btn-sm" id="printAchievementButton">
                <i class="fas fa-print"></i> Print PDF
            </button>
        </div>
        <div class="achievement-panel-body">
            <form method="GET" action="{{ route('project.achievement.report') }}" class="achievement-filter">
                <div>
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control form-control-sm" data-achievement-district>
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pngo_id">PNGO</label>
                    <select name="pngo_id" id="pngo_id" class="form-control form-control-sm" data-achievement-pngo data-selected-pngo="{{ $filters['pngo_id'] ?? '' }}">
                        <option value="">All PNGOs</option>
                    </select>
                </div>
                <div>
                    <label for="month">Month</label>
                    <select name="month" id="month" class="form-control form-control-sm" data-achievement-month>
                        <option value="">Custom Date Range</option>
                        @foreach ($monthOptions as $option)
                            <option value="{{ $option['value'] }}" {{ (string) ($filters['month'] ?? '') === $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{ empty($filters['month']) ? ($filters['from_date'] ?? '') : '' }}" data-achievement-date>
                </div>
                <div>
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{ empty($filters['month']) ? ($filters['to_date'] ?? '') : '' }}" data-achievement-date>
                </div>
                <div class="achievement-filter-actions">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('project.achievement.report') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            <div class="achievement-hint">Use Month for regular monthly reporting. Start typing a custom date range to disable the month shortcut.</div>

            @if (!empty($appliedFilters))
                <div class="mt-3">
                    @foreach ($appliedFilters as $label => $value)
                        <span class="applied-filter">{{ $label }}: {{ $value }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="achievement-panel">
        <div class="achievement-panel-header">
            <div>
                <h2>Report Result</h2>
                <small>Formal case rows use status greater than 1; LSID uses service date.</small>
            </div>
        </div>
        <div class="achievement-panel-body table-responsive">
            <table class="table table-bordered table-striped table-sm achievement-table">
                <thead>
                    <tr>
                        <th>ক্রমিক</th>
                        <th>কার্যক্রম সমূহ</th>
                        <th>সংখ্যা</th>
                        <th>একক</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                        <tr>
                            <td class="achievement-serial">{{ $row['serial'] }}</td>
                            <td>{{ $row['activity'] }}</td>
                            <td class="achievement-count">{{ $row['count'] }}</td>
                            <td class="achievement-unit">{{ $row['unit'] }}</td>
                        </tr>
                    @endforeach
                    <tr class="achievement-total-row">
                        <td colspan="2">Total</td>
                        <td class="achievement-count">{{ $rows->sum('count') }}</td>
                        <td class="achievement-unit">জন</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal fade modal-fullscreen" id="achievementPdfModal" tabindex="-1" aria-labelledby="achievementPdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="achievementPdfModalLabel">Project Achievement Endorsement Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe name="achievementPdfFrame" id="achievementPdfFrame" style="width: 100%; height: 86vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const achievementPngos = @json($pngos->map(fn ($pngo) => ['id' => $pngo->id, 'name' => $pngo->name, 'district_id' => $pngo->district_id])->values());

    function syncAchievementPngoDropdown() {
        const districtSelect = document.querySelector('[data-achievement-district]');
        const pngoSelect = document.querySelector('[data-achievement-pngo]');

        if (!districtSelect || !pngoSelect) {
            return;
        }

        const districtId = districtSelect.value;
        const selectedPngo = pngoSelect.getAttribute('data-selected-pngo') || pngoSelect.value;
        const available = districtId
            ? achievementPngos.filter((pngo) => String(pngo.district_id) === String(districtId))
            : achievementPngos;

        pngoSelect.innerHTML = '<option value="">All PNGOs</option>';
        available.forEach((pngo) => {
            const option = document.createElement('option');
            option.value = pngo.id;
            option.textContent = pngo.name;
            option.selected = String(selectedPngo) === String(pngo.id);
            pngoSelect.appendChild(option);
        });
    }

    function syncAchievementDateMode() {
        const month = document.querySelector('[data-achievement-month]');
        const dates = document.querySelectorAll('[data-achievement-date]');

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

    document.querySelector('[data-achievement-district]')?.addEventListener('change', function () {
        document.querySelector('[data-achievement-pngo]')?.setAttribute('data-selected-pngo', '');
        syncAchievementPngoDropdown();
    });

    document.querySelector('[data-achievement-month]')?.addEventListener('change', syncAchievementDateMode);
    document.querySelectorAll('[data-achievement-date]').forEach((input) => {
        input.addEventListener('input', syncAchievementDateMode);
        input.addEventListener('change', syncAchievementDateMode);
    });

    syncAchievementPngoDropdown();
    syncAchievementDateMode();

    document.getElementById('printAchievementButton')?.addEventListener('click', function(event) {
        event.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('achievementPdfModal'));
        modal.show();
        document.getElementById('achievementPdfFrame').src = '{{ route('project.achievement.report.print', [], false) }}' + window.location.search;
    });
</script>
@endpush
