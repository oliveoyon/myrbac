@extends('dashboard.layouts.admin-layout')

@section('title', 'Download Excel')

@push('styles')
<style>
    .export-workspace {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .export-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .export-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 15px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .export-panel-header h1 {
        margin: 0;
        color: #1f2937;
        font-size: 18px;
        font-weight: 800;
    }

    .export-panel-header small {
        color: #64748b;
    }

    .export-panel-body {
        padding: 15px 16px;
    }

    .export-filter-grid {
        display: grid;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
        align-items: end;
    }

    .export-filter-grid label {
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .export-actions {
        display: flex;
        gap: 8px;
    }

    .export-count-card {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border: 1px solid #d8e5df;
        border-radius: 8px;
        background: #f7fbf8;
        color: #285d49;
        font-weight: 800;
    }

    .export-count-card i {
        color: #2f7d62;
    }

    .export-note {
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    .export-loader {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        align-items: center;
        justify-content: center;
        background: rgba(248, 250, 252, .82);
        backdrop-filter: blur(2px);
    }

    .export-loader-box {
        width: min(360px, calc(100vw - 32px));
        padding: 22px;
        border: 1px solid #d8e5df;
        border-radius: 8px;
        background: #fff;
        text-align: center;
        box-shadow: 0 18px 45px rgba(16, 24, 40, .14);
    }

    .export-loader-spinner {
        width: 44px;
        height: 44px;
        margin: 0 auto 12px;
        border: 4px solid #e8f5ee;
        border-top-color: #c30f08;
        border-radius: 999px;
        animation: exportSpin .8s linear infinite;
    }

    @keyframes exportSpin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 992px) {
        .export-filter-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .export-panel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .export-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .export-actions,
        .export-actions .btn {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .export-filter-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<section class="export-workspace">
    <div class="export-panel">
        <div class="export-panel-header">
            <div>
                <h1>Download Central ID Excel</h1>
                <small>Export data using the same header mapping as the bulk upload template.</small>
            </div>
            <div class="export-count-card">
                <i class="fas fa-table"></i>
                <span>{{ number_format($estimatedCount) }} matching case{{ $estimatedCount == 1 ? '' : 's' }}</span>
            </div>
        </div>
        <div class="export-panel-body">
            <form method="GET" action="{{ route('formal.cases.export') }}" class="export-filter-grid" id="formalCaseExportForm">
                <input type="hidden" name="download" value="1">

                <div>
                    <label for="district_id">District</label>
                    <select name="district_id" id="district_id" class="form-control form-control-sm" data-export-district>
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" {{ (string) ($filters['district_id'] ?? '') === (string) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pngo_id">PNGO</label>
                    <select name="pngo_id" id="pngo_id" class="form-control form-control-sm" data-export-pngo data-selected-pngo="{{ $filters['pngo_id'] ?? '' }}">
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
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control form-control-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ (string) ($filters['status'] ?? '') === '1' ? 'selected' : '' }}>Submitted</option>
                        <option value="2" {{ (string) ($filters['status'] ?? '') === '2' ? 'selected' : '' }}>DPO Verified</option>
                        <option value="3" {{ (string) ($filters['status'] ?? '') === '3' ? 'selected' : '' }}>M&EO Verified</option>
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

                <div class="export-actions">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-download"></i> Export Excel
                    </button>
                    <a href="{{ route('formal.cases.export') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            <p class="export-note mt-3 mb-0">
                The export includes a Cases sheet and a Field Guide sheet. Applying filters reduces file size and export time.
            </p>
        </div>
    </div>
</section>

<div class="export-loader" id="exportLoader">
    <div class="export-loader-box">
        <div class="export-loader-spinner"></div>
        <strong>Preparing Excel file</strong>
        <div class="text-muted small mt-1">Large exports may take a little time. Please keep this page open.</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const formalCaseExportPngos = @json($pngos->map(fn ($pngo) => ['id' => $pngo->id, 'name' => $pngo->name, 'district_id' => $pngo->district_id])->values());

    function syncFormalCaseExportPngos() {
        const districtSelect = document.querySelector('[data-export-district]');
        const pngoSelect = document.querySelector('[data-export-pngo]');

        if (!districtSelect || !pngoSelect) {
            return;
        }

        const districtId = districtSelect.value;
        const selectedPngo = pngoSelect.getAttribute('data-selected-pngo') || pngoSelect.value;
        const availablePngos = districtId
            ? formalCaseExportPngos.filter((pngo) => String(pngo.district_id) === String(districtId))
            : formalCaseExportPngos;

        pngoSelect.innerHTML = '<option value="">All PNGOs</option>';

        availablePngos.forEach((pngo) => {
            const option = document.createElement('option');
            option.value = pngo.id;
            option.textContent = pngo.name;
            option.selected = String(selectedPngo) === String(pngo.id);
            pngoSelect.appendChild(option);
        });
    }

    document.querySelector('[data-export-district]')?.addEventListener('change', function () {
        document.querySelector('[data-export-pngo]')?.setAttribute('data-selected-pngo', '');
        syncFormalCaseExportPngos();
    });

    syncFormalCaseExportPngos();

    document.getElementById('formalCaseExportForm')?.addEventListener('submit', function () {
        const loader = document.getElementById('exportLoader');
        if (loader) {
            loader.style.display = 'flex';
        }

        setTimeout(function () {
            if (loader) {
                loader.style.display = 'none';
            }
        }, 30000);
    });
</script>
@endpush
