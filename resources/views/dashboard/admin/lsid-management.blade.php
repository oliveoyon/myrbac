@extends('dashboard.layouts.admin-layout')

@section('title', 'LSID Management')

@push('styles')
<style>
    .lsid-manage-page {
        display: grid;
        gap: 16px;
    }

    .lsid-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
    }

    .lsid-panel-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: center;
        padding: 15px 18px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .lsid-panel-header h1,
    .lsid-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 18px;
        font-weight: 800;
    }

    .lsid-panel-header p {
        margin: 3px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .lsid-panel-body {
        padding: 16px 18px;
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

    .lsid-empty-state {
        padding: 18px;
        border: 1px dashed #bfd8cb;
        border-radius: 8px;
        background: #f8fcfa;
        color: #385448;
        text-align: center;
        font-weight: 650;
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

    .lsid-action-buttons {
        display: inline-flex;
        gap: 6px;
        white-space: nowrap;
    }

    .lsid-check-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
    }

    .lsid-check-card {
        display: flex;
        gap: 8px;
        padding: 8px 9px;
        border: 1px solid #dde7e1;
        border-radius: 6px;
        background: #fff;
        font-size: 13px;
    }

    .lsid-check-card.is-selected {
        border-color: #93bba7;
        background: #f1faf5;
    }

    .lsid-dependent-field {
        display: none;
        margin-top: 10px;
        padding: 10px;
        border: 1px dashed #bfd8cb;
        border-radius: 8px;
    }

    .lsid-dependent-field.is-visible {
        display: block;
    }

    @media (max-width: 992px) {
        .lsid-filter-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .lsid-manage-page {
            gap: 12px;
        }

        .lsid-panel {
            border-radius: 7px;
        }

        .lsid-panel-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 12px 14px;
        }

        .lsid-panel-header h1,
        .lsid-panel-header h2 {
            font-size: 16px;
            line-height: 1.3;
        }

        .lsid-panel-header p {
            font-size: 12px;
            line-height: 1.45;
        }

        .lsid-panel-header .btn {
            width: 100%;
        }

        .lsid-panel-body {
            padding: 12px;
        }

        .lsid-filter-grid,
        .lsid-check-grid {
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

        .lsid-empty-state {
            padding: 16px 12px;
            font-size: 13px;
        }

        .lsid-panel-body.table-responsive {
            overflow: visible;
        }

        #lsidManagementTable {
            min-width: 0;
            border-collapse: separate;
            border-spacing: 0 7px;
            font-size: 13px;
        }

        #lsidManagementTable thead {
            display: none;
        }

        #lsidManagementTable,
        #lsidManagementTable tbody,
        #lsidManagementTable tr,
        #lsidManagementTable td {
            display: block;
            width: 100%;
        }

        #lsidManagementTable tr {
            padding: 8px 10px;
            border: 1px solid #e5e7eb;
            border-left: 3px solid #78a891;
            border-radius: 7px;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
        }

        #lsidManagementTable tbody td {
            display: grid;
            grid-template-columns: 82px minmax(0, 1fr);
            align-items: start;
            gap: 8px;
            padding: 4px 0;
            border: 0;
            text-align: left;
            overflow-wrap: anywhere;
        }

        #lsidManagementTable tbody td::before {
            content: attr(data-label);
            color: #6b7280;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .lsid-tag {
            margin-bottom: 3px;
            font-size: 11px;
        }

        .lsid-action-buttons {
            gap: 5px;
        }

        .lsid-action-buttons .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 30px;
            padding: 0;
        }

        .pagination {
            justify-content: center;
            flex-wrap: wrap;
            gap: 4px;
        }
    }

    @media (max-width: 380px) {
        #lsidManagementTable tbody td {
            grid-template-columns: 74px minmax(0, 1fr);
        }
    }
</style>
@endpush

@section('content')
<section class="lsid-manage-page">
    <div class="lsid-panel">
        <div class="lsid-panel-header">
            <div>
                <h1>LSID Register Management</h1>
                <p>Filter, review, update, or delete information desk entries within your permitted district/PNGO scope.</p>
            </div>
            @can('Create LSID Register')
                <a href="{{ route('lsid-register.index') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> New Entry
                </a>
            @endcan
        </div>
        <div class="lsid-panel-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Please check the form.</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="GET" action="{{ route('lsid-register.manage') }}" class="lsid-filter-grid">
                @if (!auth()->user()->district_id)
                    <div>
                        <label class="form-label">District</label>
                        <select name="district_id" class="form-control form-control-sm" data-lsid-district-select>
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
                        <a href="{{ route('lsid-register.manage') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="lsid-panel">
        <div class="lsid-panel-header">
            <h2>Register Entries</h2>
            <span class="badge bg-success">{{ $registers->total() }} Total</span>
        </div>
        <div class="lsid-panel-body table-responsive">
            @if (!$managementRequested)
                <div class="lsid-empty-state">Please apply a filter to load LSID register entries.</div>
            @else
            <table class="table table-striped table-hover table-sm align-middle" id="lsidManagementTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>District</th>
                        <th>PNGO</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Sex</th>
                        <th>Intervention</th>
                        <th>Service Provided</th>
                        <th style="width: 110px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($registers as $register)
                        <tr>
                            <td data-label="Date">{{ optional($register->service_date)->format('j M, Y') }}</td>
                            <td data-label="District">{{ $register->district->name ?? '-' }}</td>
                            <td data-label="PNGO">{{ $register->pngo->name ?? '-' }}</td>
                            <td data-label="Name">{{ $register->receiver_name }}</td>
                            <td data-label="Mobile">{{ $register->mobile_number ?: '-' }}</td>
                            <td data-label="Sex">{{ $sexOptions[$register->sex] ?? $register->sex }}</td>
                            <td data-label="Intervention">
                                @foreach (($register->interventions_taken ?? []) as $intervention)
                                    <span class="lsid-tag">{{ $interventionOptions[$intervention] ?? $intervention }}</span>
                                @endforeach
                            </td>
                            <td data-label="Service">
                                @foreach (($register->service_types ?? []) as $type)
                                    <span class="lsid-tag">{{ $serviceTypeOptions[$type] ?? $type }}</span>
                                @endforeach
                                @if ($register->service_type_other)
                                    <span class="lsid-tag">{{ $register->service_type_other }}</span>
                                @endif
                            </td>
                            <td data-label="Action">
                                <div class="lsid-action-buttons">
                                    @can('Edit LSID Register')
                                        <a href="{{ route('lsid-register.edit', $register) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endcan
                                    @can('Delete LSID Register')
                                        <form action="{{ route('lsid-register.destroy', $register) }}" method="POST" onsubmit="return confirm('Delete this LSID register entry?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">No LSID register entries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $registers->links() }}
            @endif
        </div>
    </div>

</section>
@endsection

@push('scripts')
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
            var root = select.closest('form') || document;
            var pngoSelect = root.querySelector('[data-lsid-pngo-select]');
            if (pngoSelect) {
                pngoSelect.setAttribute('data-selected-pngo', '');
            }
            syncLsidPngoDropdown(root);
        });
    });

    document.querySelectorAll('form').forEach(function(form) {
        syncLsidPngoDropdown(form);
    });

</script>
@endpush
