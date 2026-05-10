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
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
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
        grid-template-columns: repeat(2, minmax(0, 1fr));
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
        .lsid-filter-grid,
        .lsid-check-grid {
            grid-template-columns: 1fr;
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
                    <a href="{{ route('lsid-register.manage') }}" class="btn btn-light btn-sm w-100 mt-1">Reset</a>
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
            <table class="table table-striped table-hover table-sm align-middle">
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
                            <td>{{ optional($register->service_date)->format('Y-m-d') }}</td>
                            <td>{{ $register->district->name ?? '-' }}</td>
                            <td>{{ $register->pngo->name ?? '-' }}</td>
                            <td>{{ $register->receiver_name }}</td>
                            <td>{{ $register->mobile_number ?: '-' }}</td>
                            <td>{{ $sexOptions[$register->sex] ?? $register->sex }}</td>
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
                            <td>
                                <div class="lsid-action-buttons">
                                    @can('Edit LSID Register')
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editLsid{{ $register->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
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
        </div>
    </div>

    @foreach ($registers as $register)
        <div class="modal fade" id="editLsid{{ $register->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <form action="{{ route('lsid-register.update', $register) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit LSID Entry</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                @if (!auth()->user()->district_id)
                                    <div class="col-md-6">
                                        <label class="form-label">District</label>
                                        <select name="district_id" class="form-control">
                                            <option value="">Select District</option>
                                            @foreach ($districts as $district)
                                                <option value="{{ $district->id }}" {{ (int) $register->district_id === (int) $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if (!auth()->user()->pngo_id)
                                    <div class="col-md-6">
                                        <label class="form-label">PNGO</label>
                                        <select name="pngo_id" class="form-control">
                                            <option value="">Select PNGO</option>
                                            @foreach ($pngos as $pngo)
                                                <option value="{{ $pngo->id }}" {{ (int) $register->pngo_id === (int) $pngo->id ? 'selected' : '' }}>{{ $pngo->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-4">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="service_date" class="form-control" value="{{ optional($register->service_date)->format('Y-m-d') }}" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="receiver_name" class="form-control" value="{{ $register->receiver_name }}" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile_number" class="form-control" value="{{ $register->mobile_number }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Sex</label>
                                    <div class="lsid-check-grid">
                                        @foreach ($sexOptions as $value => $label)
                                            <label class="lsid-check-card">
                                                <input type="radio" name="sex" value="{{ $value }}" {{ $register->sex === $value ? 'checked' : '' }} required>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Other Information</label>
                                    <div class="lsid-check-grid">
                                        @foreach ($otherInformationOptions as $value => $label)
                                            <label class="lsid-check-card">
                                                <input type="checkbox" name="other_information[]" value="{{ $value }}" {{ in_array($value, $register->other_information ?? [], true) ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Intervention Taken <span class="text-danger">*</span></label>
                                    <div class="lsid-check-grid">
                                        @foreach ($interventionOptions as $value => $label)
                                            <label class="lsid-check-card">
                                                <input type="checkbox" name="interventions_taken[]" value="{{ $value }}" {{ in_array($value, $register->interventions_taken ?? [], true) ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Type of Information/Service Receiver <span class="text-danger">*</span></label>
                                    <div class="lsid-check-grid">
                                        @foreach ($receiverTypeOptions as $value => $label)
                                            <label class="lsid-check-card">
                                                <input type="checkbox" name="receiver_types[]" value="{{ $value }}" {{ in_array($value, $register->receiver_types ?? [], true) ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="lsid-dependent-field" data-dependent-field="receiver-type-other">
                                        <label class="form-label">Other People, please specify</label>
                                        <input type="text" name="receiver_type_other" class="form-control" value="{{ $register->receiver_type_other }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Type of Information/Service Provided <span class="text-danger">*</span></label>
                                    <div class="lsid-check-grid">
                                        @foreach ($serviceTypeOptions as $value => $label)
                                            <label class="lsid-check-card">
                                                <input type="checkbox" name="service_types[]" value="{{ $value }}" {{ in_array($value, $register->service_types ?? [], true) ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <div class="lsid-dependent-field" data-dependent-field="service-type-other">
                                        <label class="form-label">Other, please specify</label>
                                        <input type="text" name="service_type_other" class="form-control" value="{{ $register->service_type_other }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</section>
@endsection

@push('scripts')
<script>
    function syncLsidManagementDependentFields(root) {
        root.querySelectorAll('.lsid-check-card input').forEach(function(input) {
            input.closest('.lsid-check-card').classList.toggle('is-selected', input.checked);
        });

        root.querySelectorAll('[data-dependent-field="receiver-type-other"]').forEach(function(field) {
            var other = field.closest('.modal-body').querySelector('input[name="receiver_types[]"][value="Other People"]');
            field.classList.toggle('is-visible', other && other.checked);
            field.querySelector('input').disabled = !(other && other.checked);
        });

        root.querySelectorAll('[data-dependent-field="service-type-other"]').forEach(function(field) {
            var other = field.closest('.modal-body').querySelector('input[name="service_types[]"][value="Other"]');
            field.classList.toggle('is-visible', other && other.checked);
            field.querySelector('input').disabled = !(other && other.checked);
        });
    }

    document.querySelectorAll('.modal-body').forEach(function(modalBody) {
        syncLsidManagementDependentFields(modalBody);
        modalBody.addEventListener('change', function(event) {
            if (event.target.matches('.lsid-check-card input')) {
                syncLsidManagementDependentFields(modalBody);
            }
        });
    });
</script>
@endpush
