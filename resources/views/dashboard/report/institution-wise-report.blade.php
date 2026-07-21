@extends('dashboard.layouts.admin-layout')

@section('title', 'Institution Wise Report')

@push('styles')
<style>
    .institution-report {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .institution-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .institution-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .institution-panel-header h1,
    .institution-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .institution-panel-header small {
        color: #64748b;
    }

    .institution-panel-body {
        padding: 14px 16px;
    }

    .institution-filter {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
        align-items: end;
    }

    .institution-filter label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .institution-filter-actions {
        display: flex;
        gap: 8px;
    }

    .institution-chart {
        height: 310px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
    }

    #institutionChart {
        width: 100% !important;
        height: 100% !important;
    }

    .institution-table {
        margin: 0;
        font-size: 13px;
    }

    .institution-table th {
        white-space: nowrap;
        color: #374151;
        background: #f8fafc;
    }

    .institution-empty {
        padding: 18px;
        color: #64748b;
        text-align: center;
        border: 1px dashed #d7dee3;
        border-radius: 8px;
        background: #fbfcfd;
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

    @media (max-width: 992px) {
        .institution-filter {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .institution-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .institution-filter {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .institution-filter-actions,
        .institution-filter-actions .btn {
            width: 100%;
        }

        .institution-chart {
            height: 260px;
        }
    }

    @media (max-width: 480px) {
        .institution-filter {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="institution-report">
    <div class="institution-panel">
        <div class="institution-panel-header">
            <div>
                <h1>Institution Wise Report</h1>
                <small>Summary by Court, Police Station, and Prison</small>
            </div>
            @if ($rows->count())
                <button type="button" class="btn btn-success btn-sm" id="printButton">
                    <i class="fas fa-print"></i> Print PDF
                </button>
            @endif
        </div>
        <div class="institution-panel-body">
            <form method="GET" action="{{ route('institution.wise.report') }}" class="institution-filter">
                <div>
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control form-control-sm" data-institution-district>
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="pngo_id">PNGO</label>
                    <select name="pngo_id" id="pngo_id" class="form-control form-control-sm" data-institution-pngo data-selected-pngo="{{ $filters['pngo_id'] ?? '' }}">
                        <option value="">All PNGOs</option>
                    </select>
                </div>
                <div>
                    <label for="institute">Institution</label>
                    <select name="institute" id="institute" class="form-control form-control-sm">
                        <option value="">All Institutions</option>
                        @foreach ($institutionOptions as $option)
                            <option value="{{ $option }}" {{ (string) ($filters['institute'] ?? '') === $option ? 'selected' : '' }}>{{ $option }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control form-control-sm" value="{{ $filters['from_date'] ?? '' }}">
                </div>
                <div>
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control form-control-sm" value="{{ $filters['to_date'] ?? '' }}">
                </div>
                <div class="institution-filter-actions">
                    <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ route('institution.wise.report') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            @if (!empty($appliedFilters))
                <div class="mt-3">
                    @foreach ($appliedFilters as $label => $value)
                        <span class="applied-filter">{{ $label }}: {{ $value }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="institution-panel">
        <div class="institution-panel-header">
            <div>
                <h2>Report Result</h2>
                <small>Male + Female + Transgender equals total; Under 18 and Disability Yes are subset indicators.</small>
            </div>
        </div>
        <div class="institution-panel-body">
            @if ($rows->count())
                <div class="institution-chart mb-3">
                    <canvas id="institutionChart"></canvas>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-sm institution-table">
                        <thead>
                            <tr>
                                <th>Institution</th>
                                <th>Male</th>
                                <th>Female</th>
                                <th>Transgender</th>
                                <th>Under 18</th>
                                <th>Disability Yes</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rows as $row)
                                <tr>
                                    <td>{{ $row['institution_name'] }}</td>
                                    <td>{{ $row['male'] }}</td>
                                    <td>{{ $row['female'] }}</td>
                                    <td>{{ $row['transgender'] }}</td>
                                    <td>{{ $row['under_18'] }}</td>
                                    <td>{{ $row['disability'] }}</td>
                                    <td><strong>{{ $row['total'] }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="institution-empty">No institution data found for the selected filters.</div>
            @endif
        </div>
    </div>
</section>

<div class="modal fade modal-fullscreen" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Institution Wise Report</h5>
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
    const institutionPngos = @json($pngos->map(fn ($pngo) => ['id' => $pngo->id, 'name' => $pngo->name, 'district_id' => $pngo->district_id])->values());

    function syncInstitutionPngoDropdown() {
        const districtSelect = document.querySelector('[data-institution-district]');
        const pngoSelect = document.querySelector('[data-institution-pngo]');

        if (!districtSelect || !pngoSelect) {
            return;
        }

        const districtId = districtSelect.value;
        const selectedPngo = pngoSelect.getAttribute('data-selected-pngo') || pngoSelect.value;
        const available = districtId
            ? institutionPngos.filter((pngo) => String(pngo.district_id) === String(districtId))
            : institutionPngos;

        pngoSelect.innerHTML = '<option value="">All PNGOs</option>';
        available.forEach((pngo) => {
            const option = document.createElement('option');
            option.value = pngo.id;
            option.textContent = pngo.name;
            option.selected = String(selectedPngo) === String(pngo.id);
            pngoSelect.appendChild(option);
        });
    }

    document.querySelector('[data-institution-district]')?.addEventListener('change', function () {
        document.querySelector('[data-institution-pngo]')?.setAttribute('data-selected-pngo', '');
        syncInstitutionPngoDropdown();
    });

    syncInstitutionPngoDropdown();

    document.getElementById('printButton')?.addEventListener('click', function(event) {
        event.preventDefault();
        const modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
        document.getElementById('pdfFrame').src = '{{ route('institution.wise.report.print', [], false) }}' + window.location.search;
    });

    const institutionRows = @json($rows);

    if (institutionRows.length && document.getElementById('institutionChart')) {
        new Chart(document.getElementById('institutionChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: institutionRows.map((row) => row.institution_name),
                datasets: [
                    { label: 'Male', data: institutionRows.map((row) => row.male), backgroundColor: '#4f7dd9' },
                    { label: 'Female', data: institutionRows.map((row) => row.female), backgroundColor: '#b86fa0' },
                    { label: 'Transgender', data: institutionRows.map((row) => row.transgender), backgroundColor: '#7c8fb0' },
                    { label: 'Under 18', data: institutionRows.map((row) => row.under_18), backgroundColor: '#d9a441' },
                    { label: 'Disability Yes', data: institutionRows.map((row) => row.disability), backgroundColor: '#6f7bc8' },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true },
                },
            },
        });
    }
</script>
@endpush
