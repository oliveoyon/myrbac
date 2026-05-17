@extends('dashboard.layouts.admin-layout')

@section('title', 'User Management')



@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .modal-body {
        overflow-y: auto;
        max-height: 90vh;
        /* Keeps content scrollable within the full-screen modal */
    }

    .category-box {
        margin-bottom: 14px;
        background-color: #ffffff;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        overflow: hidden;
    }

    .category-header {
        margin: 0;
        padding: 12px 14px;
        border-bottom: 1px solid #f0d2cf;
        background: #fff7f6;
        color: #c30f08;
        font-size: 15px;
        font-weight: 800;
    }

    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 8px;
        padding: 12px;
    }

    .permission-item {
        min-height: 38px;
        padding: 8px 10px;
        background-color: #fff;
        border: 1px solid #d8dee6;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
        border-radius: 6px;
    }

    .permission-item:hover {
        border-color: #c30f08;
        background: #fff7f6;
    }

    #permissionsViewModal .modal-body {
        background: #f8fafc;
    }

    .permission-view-shell {
        display: grid;
        gap: 14px;
    }

    .permission-view-summary {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border: 1px solid #e1e5ea;
        border-left: 4px solid #c30f08;
        border-radius: 8px;
        background: #fff;
    }

    .permission-view-summary h6 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 800;
    }

    .permission-view-summary p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 13px;
    }

    .permission-view-count {
        padding: 4px 10px;
        border: 1px solid #f0d2cf;
        border-radius: 999px;
        background: #fff7f6;
        color: #9d0c06;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .permission-view-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 12px;
    }

    #permissionsViewModal .category-box {
        margin-bottom: 0;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    #permissionsViewModal .category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: #fff !important;
    }

    .category-header-count {
        padding: 3px 8px;
        border: 1px solid #f0d2cf;
        border-radius: 999px;
        background: #fff;
        color: #7f1d1d;
        font-size: 11px;
        font-weight: 800;
        white-space: nowrap;
    }

    #permissionsViewModal .permissions-grid {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 10px;
    }

    #permissionsViewModal .permission-item {
        display: flex;
        align-items: center;
        gap: 8px;
        min-height: 34px;
        padding: 7px 9px;
        font-weight: 600;
    }

    #permissionsViewModal .permission-item i {
        color: #c30f08;
        font-size: 11px;
    }

    .permission-view-empty {
        padding: 16px;
        border: 1px dashed #d8dee6;
        border-radius: 8px;
        background: #fff;
        color: #6b7280;
        text-align: center;
        font-weight: 700;
    }

    #userPermissionsModal .modal-body {
        background: #f8fafc;
    }

    .permission-edit-shell {
        display: grid;
        gap: 12px;
    }

    #userPermissionsModal .category-box {
        margin-bottom: 0;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    #userPermissionsModal .category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: #fff !important;
    }

    #userPermissionsModal .permissions-grid {
        grid-template-columns: 1fr;
        gap: 6px;
        padding: 10px;
    }

    #userPermissionsModal .permission-item {
        min-height: 34px;
        padding: 0;
    }

    #userPermissionsModal .permission-item label {
        display: flex;
        align-items: center;
        gap: 7px;
        width: 100%;
        min-height: 30px;
        margin: 0;
        padding: 6px 8px;
        color: #334155;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    #userPermissionsModal .permission-item input[type="checkbox"] {
        width: 13px;
        height: 13px;
        margin: 0;
        accent-color: #c30f08;
        flex: 0 0 auto;
    }

    #userPermissionsModal .permission-item:has(input[type="checkbox"]:checked) {
        border-color: #f1b7b3;
        background: #fffafa;
    }

    #userPermissionsModal .permission-item:has(input[type="checkbox"]:checked) label {
        color: #7f1d1d;
        font-weight: 700;
    }

    #user-table .management-actions {
        flex-wrap: nowrap;
        gap: 4px;
    }

    #user-table .management-actions .btn {
        min-width: auto;
        padding: 4px 7px;
        border-radius: 6px;
        font-size: 12px;
        line-height: 1.2;
        white-space: nowrap;
        box-shadow: none;
    }

    #user-table .management-actions .btn i {
        margin-right: 2px;
        font-size: 11px;
    }

    #user-table .management-actions .btn-info {
        background: #eef6ff;
        border-color: #bfdbfe;
        color: #1d4ed8;
    }

    #user-table .management-actions .btn-success {
        background: #f0fdf4;
        border-color: #bbf7d0;
        color: #166534;
    }

    #user-table .management-actions .btn-warning {
        background: #fff7f6;
        border-color: #f0d2cf;
        color: #9d0c06;
    }

    #user-table .management-actions .btn:hover {
        transform: none;
        filter: brightness(0.98);
    }

    .user-filter-panel {
        margin-bottom: 16px;
        padding: 16px;
        border: 1px solid #e3e8ef;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .user-filter-panel .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 800;
        margin-bottom: 6px;
    }

    .user-filter-panel .form-control,
    .user-filter-panel .form-select {
        border-color: #d8dee6;
        font-size: 13px;
        min-height: 38px;
    }

    .user-filter-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        align-items: end;
        height: 100%;
    }

    .user-filter-actions .btn {
        min-height: 38px;
        padding: 7px 13px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        box-shadow: none;
    }

    .user-filter-empty {
        padding: 28px 18px;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #64748b;
        text-align: center;
        font-weight: 700;
    }

    .user-filter-empty i {
        display: block;
        margin-bottom: 8px;
        color: #94a3b8;
        font-size: 24px;
    }

    .user-pagination-wrap {
        padding: 12px 0 0;
    }

    .multi-scope-panel {
        display: none;
        padding: 12px;
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .multi-scope-panel.is-visible {
        display: block;
    }

    .multi-scope-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 8px;
        max-height: 220px;
        overflow-y: auto;
    }

    .multi-scope-item {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        padding: 8px 10px;
        border: 1px solid #d8dee6;
        border-radius: 6px;
        background: #fff;
        color: #334155;
        font-size: 12px;
        font-weight: 700;
    }

    .multi-scope-item input {
        margin-top: 2px;
        accent-color: #c30f08;
    }

    .multi-scope-note {
        margin: 0 0 10px;
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    /* Custom Styling for the Edit Permissions Form */
    .edit-permission-header {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    /* Ensuring the grid remains neat on smaller screens */
    @media (max-width: 768px) {
        .permissions-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }

        .permission-view-summary {
            flex-direction: column;
        }

        .permission-view-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Classic Checkbox Style */
    .classic-checkbox {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        /* Space between checkbox and label */
        vertical-align: middle;
        /* Align checkbox with the text */
        flex-shrink: 0;
        /* Prevent checkbox from shrinking */
    }

    /* Label Style for Classic Checkbox */
    .classic-checkbox-label {
        font-size: 14px;
        /* Text size */
        vertical-align: middle;
        /* Align label text with checkbox */
    }

    /* Category Title */
    .classic-category-title {
        padding: 12px 14px;
        border: 1px solid #f0d2cf;
        border-radius: 8px 8px 0 0;
        background: #fff7f6;
        color: #c30f08 !important;
        font-size: 15px;
        font-weight: 800;
        margin-bottom: 0;
    }

    /* Grid Layout for Permission Items */
    .classic-row {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 8px;
        padding: 12px;
        border: 1px solid #e0e6ed;
        border-top: 0;
        border-radius: 0 0 8px 8px;
        margin-bottom: 20px;
    }

    /* Permission Items */
    .form-check {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        min-height: 38px;
        padding: 8px 10px;
        border: 1px solid #d8dee6;
        border-radius: 6px;
        background: #fff;
    }

    /* Column Control for Grid Layout */
    .col-md-4 {
        display: flex;
        justify-content: flex-start;
        width: 100%;
        /* Ensure items fit in the grid */
        align-items: center;
        /* Vertically align content in the column */
    }

    /* Make sure the checkboxes and labels have the same height */
    .form-check input[type="checkbox"] {
        height: 16px;
        width: 16px;
    }

    /* Optional: Adjust the label size */
    .form-check label {
        font-size: 14px;
        padding-left: 5px;
        /* Space between checkbox and label */
    }

    /* Make sure the items have consistent width */
    .classic-row .col-md-4 {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        text-align: left;
        width: 100%;
        flex: 1 1 auto;
        padding: 5px;
    }

    @media (max-width: 576px) {
        #addUserModal .modal-dialog,
        #editUserModal .modal-dialog {
            align-items: flex-start;
            margin: 10px;
        }

        #addUserModal .modal-content,
        #editUserModal .modal-content {
            max-height: calc(100dvh - 20px);
            border-radius: 8px;
            overflow: hidden;
        }

        #addUserModal .modal-header,
        #editUserModal .modal-header {
            padding: 12px 14px;
        }

        #addUserModal .modal-title,
        #editUserModal .modal-title {
            font-size: 16px;
            font-weight: 800;
        }

        #addUserModal .modal-body,
        #editUserModal .modal-body {
            max-height: calc(100dvh - 108px);
            padding: 14px;
        }

        #addUserModal .row,
        #editUserModal .row {
            row-gap: 0;
        }

        #addUserModal .mb-3,
        #editUserModal .mb-3 {
            margin-bottom: 12px !important;
        }

        #addUserModal .form-label,
        #editUserModal .form-label {
            margin-bottom: 5px;
            font-size: 12px;
            font-weight: 800;
        }

        #addUserModal .form-control,
        #editUserModal .form-control,
        #addUserModal .select2-container .select2-selection--multiple,
        #editUserModal .select2-container .select2-selection--multiple {
            min-height: 38px;
            font-size: 13px;
        }

        #addUserModal .modal-footer,
        #editUserModal .modal-footer,
        #permissionsViewModal .modal-footer,
        #userPermissionsModal .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            padding: 10px 14px 14px;
        }

        #permissionsViewModal .modal-footer {
            grid-template-columns: 1fr;
        }

        #addUserModal .modal-footer .btn,
        #editUserModal .modal-footer .btn,
        #permissionsViewModal .modal-footer .btn,
        #userPermissionsModal .modal-footer .btn {
            width: 100%;
            margin: 0;
            min-height: 38px;
        }

        .multi-scope-panel {
            padding: 10px;
        }

        .multi-scope-grid {
            grid-template-columns: 1fr;
            max-height: 180px;
        }

        .multi-scope-item {
            padding: 7px 9px;
            font-size: 12px;
        }

        #permissionsViewModal .modal-body,
        #userPermissionsModal .modal-body {
            padding: 12px;
        }

        #permissionsViewModal .permission-view-summary,
        #userPermissionsModal .permission-view-summary {
            padding: 12px;
        }

        #permissionsViewModal .permission-view-count,
        #userPermissionsModal .permission-view-count {
            align-self: flex-start;
        }
    }
</style>
@endpush


@section('content')
<section class="management-page">
    <div class="management-header">
        <div>
            <h1>User Management</h1>
            <p>Create users, assign organization scope, and manage role or direct permissions.</p>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-plus-square"></i>
            Add User
        </button>
    </div>

    <div class="management-card">
        <div class="management-card-header">
            <h2><i class="fas fa-user me-2"></i>User List</h2>
            <span class="management-count">{{ $filterRequested ? $users->total() : 0 }} User{{ ($filterRequested ? $users->total() : 0) === 1 ? '' : 's' }}</span>
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="user-filter-panel">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label" for="filter_name">User Name</label>
                    <input type="text" class="form-control" id="filter_name" name="name"
                        value="{{ $filters['name'] ?? '' }}" placeholder="Search username">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_full_name">Full Name</label>
                    <input type="text" class="form-control" id="filter_full_name" name="full_name"
                        value="{{ $filters['full_name'] ?? '' }}" placeholder="Search full name">
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_district_id">District</label>
                    <select class="form-select" id="filter_district_id" name="district_id">
                        <option value="">All Districts</option>
                        @foreach ($districts as $district)
                        <option value="{{ $district->id }}" {{ (string)($filters['district_id'] ?? '') === (string)$district->id ? 'selected' : '' }}>
                            {{ $district->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_pngo_id">PNGO</label>
                    <select class="form-select" id="filter_pngo_id" name="pngo_id">
                        <option value="">All PNGOs</option>
                        @foreach ($pngos as $pngo)
                        <option value="{{ $pngo->id }}" data-district-id="{{ $pngo->district_id }}" {{ (string)($filters['pngo_id'] ?? '') === (string)$pngo->id ? 'selected' : '' }}>
                            {{ $pngo->name }}{{ $pngo->district ? ' - ' . $pngo->district->name : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_role_name">Role</label>
                    <select class="form-select" id="filter_role_name" name="role_name">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ ($filters['role_name'] ?? '') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    <div class="user-filter-actions">
                        <a href="{{ route('users.index') }}" class="btn btn-light border">
                            <i class="fas fa-undo-alt"></i>
                            Reset
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                            Search Users
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <div class="management-table-wrap table-responsive">
            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                <ul id="errorList">
                    <!-- Error messages will be inserted here dynamically -->
                </ul>
            </div>

            @if (! $filterRequested)
            <div class="user-filter-empty">
                <i class="fas fa-filter"></i>
                Use one or more filters to load users.
            </div>
            @elseif ($users->isEmpty())
            <div class="user-filter-empty">
                <i class="fas fa-search"></i>
                No users matched the selected filters.
            </div>
            @else
            <table class="table table-bordered table-striped table-hover table-sm management-table" id="user-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">#</th>
                        <th>Full Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>District</th>
                        <th>PNGO</th>
                        <th>Assigned Scopes</th>
                        <th>Status</th>
                        <th style="width: 260px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $users->firstItem() + $loop->index }}</td>
                        <td>{{ $user->full_name ?: '-' }}</td>
                        <td class="management-name-cell">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->district ? $user->district->name : 'No District' }}</td>
                        <td>{{ $user->pngo ? $user->pngo->name : 'No PNGO' }}</td>
                        <td>
                            @if ($user->pngoScopes->isNotEmpty())
                                {{ $user->pngoScopes->map(fn ($scope) => optional($scope->pngo)->name . ' - ' . optional($scope->district)->name)->implode(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $user->status == 1 ? 'bg-success' : 'bg-secondary' }}">
                                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                            </span>
                        </td>

                        <td>
                            <div class="management-actions">
                                <button type="button" class="btn btn-info btn-sm" data-id="{{ $user->id }}"
                                    id="editUserBtn">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </button>

                                <button type="button" class="btn btn-success btn-sm view-permissions"
                                    data-toggle="modal" data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <i class="fas fa-eye"></i>
                                    View
                                </button>
                                <button class="btn btn-warning btn-sm edit-user-permissions"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    <i class="fas fa-pencil-alt"></i>
                                    Permissions
                                </button>
                            </div>
                        </td>

                    </tr>
                    @endforeach

                </tbody>
            </table>
            <div class="user-pagination-wrap">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>
        <!-- Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('addUser') }}" method="POST" autocomplete="off" id="add-user-form">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label class="form-label required" for="full_name">Full Name</label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter full name">
                                    <span class="text-danger error-text full_name_error"></span>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label required" for="name">User Name</label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
                                    <span class="text-danger error-text name_error"></span>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label required" for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                                    <span class="text-danger error-text email_error"></span>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label scoped-location-label" for="district_id">District</label>
                                    <select class="form-control user-district-select" name="district_id" id="district_id">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text district_id_error"></span>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label scoped-location-label" for="pngo_id">PNGO</label>
                                    <select class="form-control user-pngo-select" name="pngo_id" id="pngo_id">
                                        <option value="">Select District First</option>
                                        @foreach ($pngos as $pngo)
                                        <option value="{{ $pngo->id }}" data-district-id="{{ $pngo->district_id }}">{{ $pngo->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text pngo_id_error"></span>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label required" for="role_name">Roles</label>
                                    <select class="form-control" name="role_name[]" id="role_name" multiple>
                                        <option value="">Select Role (Multiple)</option>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text role_name_error"></span>
                                </div>

                                <div class="mb-3 col-12 multi-scope-panel">
                                    <label class="form-label required">District-PNGO Scopes</label>
                                    <p class="multi-scope-note">Required only for M&EO and PNGO Focal users. Select every district-PNGO pair this user can monitor.</p>
                                    <div class="multi-scope-grid">
                                        @foreach ($pngos as $pngo)
                                        <label class="multi-scope-item">
                                            <input type="checkbox" name="scoped_pngos[]" value="{{ $pngo->id }}">
                                            <span>{{ $pngo->name }}{{ $pngo->district ? ' - ' . $pngo->district->name : '' }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    <span class="text-danger error-text scoped_pngos_error"></span>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label required" for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade editUser" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="{{ route('updateUserDetails') }}" method="post" autocomplete="off" id="update-user-form">
                            @csrf
                            <input type="hidden" name="uid">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="full_name">Full Name</label>
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Enter full name">
                                        <span class="text-danger error-text full_name_error"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="name">User Name</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter name">
                                        <span class="text-danger error-text name_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="email">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
                                        <span class="text-danger error-text email_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label scoped-location-label" for="district_id">District</label>
                                        <select class="form-control user-district-select" name="district_id" id="district_id">
                                            <option value="">Select District</option>
                                            @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text district_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="role_name">Roles</label>
                                        <select class="form-control" name="role_name[]" id="role_name1" multiple>
                                            <option value="">Select Role (Multiple)</option>
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text role_name_error"></span>
                                    </div>
                                </div>

                                <div class="col-12 multi-scope-panel">
                                    <label class="form-label required">District-PNGO Scopes</label>
                                    <p class="multi-scope-note">Required only for M&EO and PNGO Focal users. Select every district-PNGO pair this user can monitor.</p>
                                    <div class="multi-scope-grid">
                                        @foreach ($pngos as $pngo)
                                        <label class="multi-scope-item">
                                            <input type="checkbox" name="scoped_pngos[]" value="{{ $pngo->id }}">
                                            <span>{{ $pngo->name }}{{ $pngo->district ? ' - ' . $pngo->district->name : '' }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                    <span class="text-danger error-text scoped_pngos_error"></span>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label scoped-location-label" for="pngo_id">PNGO</label>
                                        <select class="form-control user-pngo-select" name="pngo_id" id="pngo_id">
                                            <option value="">Select District First</option>
                                            @foreach ($pngos as $pngo)
                                            <option value="{{ $pngo->id }}" data-district-id="{{ $pngo->district_id }}">{{ $pngo->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger error-text pngo_id_error"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required" for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="1">Active</option>
                                            <option value="2">Active - Must Change Password</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                        <span class="text-danger error-text status_error"></span>
                                    </div>
                                </div>
                                @can('Change User Password')
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Reset Password</label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="Leave blank to keep current password">
                                        <small class="text-muted">If changed, user must change password after login.</small>
                                        <span class="text-danger error-text password_error"></span>
                                    </div>
                                </div>
                                @endcan
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

</section>






<div class="modal fade" id="permissionsViewModal" tabindex="-1" role="dialog"
    aria-labelledby="permissionsViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsViewModalLabel">Role Permissions</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be loaded here (view permissions) -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- User Permissions Edit Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1" aria-labelledby="userPermissionsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userPermissionsModalLabel">Edit User Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="editUserPermissionsContent">
                    <!-- Dynamic Content Loaded via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="saveUserPermissions" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>





@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        const scopedLocationRoles = ['Paralegal', 'DPO'];
        const multiScopeRoles = ['M&EO', 'PNGO Focal'];

        function selectedRolesRequireLocation(form) {
            const selectedRoles = form.find('select[name="role_name[]"]').val() || [];
            return selectedRoles.some(role => scopedLocationRoles.includes(role));
        }

        function selectedRolesRequireMultiScope(form) {
            const selectedRoles = form.find('select[name="role_name[]"]').val() || [];
            return selectedRoles.some(role => multiScopeRoles.includes(role));
        }

        function syncLocationRequirement(form) {
            const isRequired = selectedRolesRequireLocation(form);
            const multiScopeRequired = selectedRolesRequireMultiScope(form);
            const singleScopeDisabled = multiScopeRequired && !isRequired;

            form.find('.scoped-location-label').toggleClass('required', isRequired);
            form.find('select[name="district_id"], select[name="pngo_id"]')
                .prop('required', isRequired)
                .prop('disabled', singleScopeDisabled);
            form.find('.multi-scope-panel').toggleClass('is-visible', multiScopeRequired);
            form.find('input[name="scoped_pngos[]"]').prop('required', false);

            if (singleScopeDisabled) {
                form.find('select[name="district_id"], select[name="pngo_id"]').val('');
                syncUserPngoOptions(form);
            }

            if (!multiScopeRequired) {
                form.find('input[name="scoped_pngos[]"]').prop('checked', false);
            }
        }

        function syncUserPngoOptions(form, selectedPngoId = null) {
            const districtId = String(form.find('select[name="district_id"]').val() || '');
            const pngoSelect = form.find('select[name="pngo_id"]');
            const currentValue = selectedPngoId !== null ? String(selectedPngoId || '') : String(pngoSelect.val() || '');
            let hasSelectedOption = false;

            pngoSelect.find('option').each(function() {
                const option = $(this);
                const optionDistrictId = String(option.data('district-id') || '');
                const isPlaceholder = option.val() === '';
                const isAllowed = isPlaceholder || (districtId && optionDistrictId === districtId);

                option.prop('disabled', !isAllowed);
                option.prop('hidden', !isAllowed);

                if (!isPlaceholder && isAllowed && String(option.val()) === currentValue) {
                    hasSelectedOption = true;
                }
            });

            pngoSelect.find('option[value=""]').text(districtId ? 'Select PNGO' : 'Select District First');
            pngoSelect.val(hasSelectedOption ? currentValue : '');
        }

        function syncFilterPngoOptions() {
            const districtId = String($('#filter_district_id').val() || '');
            const pngoSelect = $('#filter_pngo_id');
            const currentValue = String(pngoSelect.val() || '');
            let hasSelectedOption = currentValue === '';

            pngoSelect.find('option').each(function() {
                const option = $(this);
                const optionDistrictId = String(option.data('district-id') || '');
                const isPlaceholder = option.val() === '';
                const isAllowed = isPlaceholder || !districtId || optionDistrictId === districtId;

                option.prop('disabled', !isAllowed);
                option.prop('hidden', !isAllowed);

                if (!isPlaceholder && isAllowed && String(option.val()) === currentValue) {
                    hasSelectedOption = true;
                }
            });

            pngoSelect.find('option[value=""]').text(districtId ? 'All PNGOs in selected district' : 'All PNGOs');
            if (!hasSelectedOption) {
                pngoSelect.val('');
            }
        }

        syncFilterPngoOptions();
        $('#filter_district_id').on('change', syncFilterPngoOptions);

        $('.user-district-select').on('change', function() {
            syncUserPngoOptions($(this).closest('form'));
        });

        $('#role_name, #role_name1').on('change', function() {
            const form = $(this).closest('form');
            syncLocationRequirement(form);
        });

        $('#addUserModal').on('shown.bs.modal', function() {
            syncUserPngoOptions($('#add-user-form'));
            syncLocationRequirement($('#add-user-form'));
        });

        // Clear error messages on modal close
        $('#addUserModal').on('hidden.bs.modal', function() {
            $('#add-user-form').find('span.error-text').text('');
            $('#add-user-form').find('input[name="scoped_pngos[]"]').prop('checked', false);
            syncUserPngoOptions($('#add-user-form'));
            syncLocationRequirement($('#add-user-form'));
        });

        // When the 'View Permissions' button is clicked
        $('.view-permissions').on('click', function() {
            var userId = $(this).data('id'); // Get the user ID from the button's data attribute
            var userName = $(this).data('name');
            $.ajax({
                url: '/mne/users/' + userId +
                    '/permissions', // Make the AJAX request to fetch user permissions
                method: 'GET',
                success: function(response) {
                    var permissionsList =
                        ''; // Initialize an empty string for the permissions
                    var totalPermissions = 0;
                    var categoryCount = Object.keys(response.permissions || {}).length;

                    // Loop through the grouped permissions returned in the response
                    $.each(response.permissions, function(category, permissions) {
                        totalPermissions += permissions.length;
                        permissionsList +=
                            '<div class="category-box">'; // Start of category box
                        permissionsList += '<div class="category-header"><span>' +
                            category + '</span><span class="category-header-count">' + permissions.length +
                            '</span></div>'; // Category name header
                        permissionsList +=
                            '<div class="permissions-grid">'; // Start of permission grid

                        permissions.forEach(function(permission) {
                            permissionsList +=
                                '<div class="permission-item"><i class="fas fa-check-circle"></i><span>' + permission
                                .name + '</span></div>'; // Display permission name
                        });

                        permissionsList +=
                            '</div></div>'; // Close permissions grid and category box
                    });

                    if (!totalPermissions) {
                        permissionsList = '<div class="permission-view-empty">No direct permissions assigned to this user.</div>';
                    } else {
                        permissionsList = '<div class="permission-view-grid">' + permissionsList + '</div>';
                    }

                    permissionsList =
                        '<div class="permission-view-shell">' +
                            '<div class="permission-view-summary">' +
                                '<div>' +
                                    '<h6>' + userName + '</h6>' +
                                    '<p>' + categoryCount + ' categories with assigned permissions</p>' +
                                '</div>' +
                                '<span class="permission-view-count">' + totalPermissions + ' Permissions</span>' +
                            '</div>' +
                            permissionsList +
                        '</div>';

                    // Inject the generated HTML into the modal body
                    $('#permissionsViewModal .modal-body').html(permissionsList);

                    // Show the modal
                    $('#permissionsViewModal').modal('show');
                },
                error: function() {
                    alert('Error loading permissions.');
                }
            });
        });


        $('#add-user-form').on('submit', function(e) {
            e.preventDefault();

            // Disable the submit button to prevent double-clicking
            $(this).find(':submit').prop('disabled', true);

            // Show the loader overlay (if any)
            $('#loader-overlay').show();

            var form = this;

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    // Clear previous error messages
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.code == 0) {
                        // Handle validation errors
                        $.each(data.error, function(prefix, val) {
                            // Find the error span by class name and set the error text
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });

                        // Focus on the first error field
                        var firstErrorField = $(form).find('span.error-text').first().prev(
                            'input, select');
                        if (firstErrorField.length) {
                            firstErrorField.focus();
                        }
                    } else {
                        // Handle success response
                        var redirectUrl = data.redirect;
                        $('#addUserModal').modal('hide');
                        $('#addUserModal').find('form')[0].reset();

                        // Customize Swal design for success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.msg,
                            showConfirmButton: false,
                            timer: 1500,
                            background: '#eaf9e7', // Light green background
                            color: '#2e8b57', // Text color
                            confirmButtonColor: '#4CAF50' // Button color
                        });

                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle unexpected errors with toastr
                    toastr.error('Something went wrong! Please try again.');
                    console.log(xhr.responseText); // For debugging
                },
                complete: function() {
                    // Enable the submit button and hide the loader overlay
                    $(form).find(':submit').prop('disabled', false);
                    $('#loader-overlay').hide();
                }
            });
        });

        $(document).on('click', '#editUserBtn', function() {
            var user_id = $(this).data('id');
            $('.editUser').find('form')[0].reset();
            $('.editUser').find('span.error-text').text('');

            $.post("{{ route('getUserDetails') }}", {
                user_id: user_id
            }, function(data) {
                const modal = $('.editUser');

                modal.find('input[name="uid"]').val(data.details.id);
                modal.find('input[name="full_name"]').val(data.details.full_name);
                modal.find('input[name="name"]').val(data.details.name);
                modal.find('input[name="email"]').val(data.details.email);
                modal.find('select[name="district_id"]').val(data.details.district_id);
                syncUserPngoOptions(modal.find('form'), data.details.pngo_id);
                modal.find('select[name="status"]').val(data.details.status);

                // ✅ Set Select2 roles
                let roleSelect = modal.find('select[name="role_name[]"]');
                roleSelect.val(data.details.role_name).trigger('change');
                modal.find('input[name="scoped_pngos[]"]').prop('checked', false);
                (data.details.scoped_pngo_ids || []).forEach(function(pngoId) {
                    modal.find('input[name="scoped_pngos[]"][value="' + pngoId + '"]').prop('checked', true);
                });
                syncLocationRequirement(modal.find('form'));

                modal.modal('show');
            }, 'json');
        });



        // Update Class RECORD
        $('#update-user-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;

            // Disable the submit button to prevent double-clicking
            $(form).find(':submit').prop('disabled', true);

            // Show the loader overlay
            $('#loader-overlay').show();

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.code == 0) {
                        // Show errors if any
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        // Hide modal and reset form
                        $('.editUser').modal('hide');
                        $('.editUser').find('form')[0].reset();

                        // Success message using SweetAlert2
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.msg,
                            timer: 2000, // Adjust the duration as needed
                            showConfirmButton: false,
                        });

                        // Redirect after a delay (if provided)
                        var redirectUrl = data.redirect;
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 2000); // Adjust the delay as needed (in milliseconds)
                    }
                },
                complete: function() {
                    // Enable the submit button and hide the loader overlay
                    $(form).find(':submit').prop('disabled', false);
                    $('#loader-overlay').hide();
                },
                error: function(xhr, status, error) {
                    // Show error notification using SweetAlert2 if the AJAX request fails
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong. Please try again.',
                        showConfirmButton: true,
                    });

                    // Optionally, log the error to the console
                    console.error('Error:', status, error);
                }
            });
        });


        $('.edit-user-permissions').on('click', function() {
            var userId = $(this).data('id');
            var userName = $(this).data('name');

            $('#userPermissionsModalLabel').text('Edit Permissions for ' + userName);

            $.ajax({
                url: '/mne/users/' + userId + '/edit-permissions',
                method: 'GET',
                success: function(response) {
                    if (response && response.user && response.allPermissions) {
                        var editForm = '<form id="editUserPermissionsForm" class="permission-view-shell">';
                        var permissionGroups = '';
                        var totalPermissions = 0;
                        var categoryCount = 0;

                        editForm += '<input type="hidden" name="user_id" value="' + userId + '">';

                        var groupedPermissions = {};

                        // Group permissions by category
                        $.each(response.allPermissions, function(index, permission) {
                            var category = permission.category || 'Uncategorized';
                            if (!groupedPermissions[category]) {
                                groupedPermissions[category] = [];
                            }
                            groupedPermissions[category].push(permission);
                        });

                        // Render grouped permissions
                        $.each(groupedPermissions, function(category, permissions) {
                            totalPermissions += permissions.length;
                            categoryCount++;

                            permissionGroups +=
                                '<div class="category-box">';
                            permissionGroups +=
                                '<div class="category-header"><span>' +
                                category + '</span><span class="category-header-count">' + permissions.length +
                                '</span></div>';
                            permissionGroups +=
                                '<div class="permissions-grid">';

                            $.each(permissions, function(index, permission) {
                                var checked = response.userPermissions[
                                        category] &&
                                    response.userPermissions[category].some(
                                        p => p.id === permission.id) ?
                                    'checked' : '';

                                permissionGroups += `
                            <div class="permission-item">
                                <label for="permission-${permission.id}">
                                    <input class="form-check-input classic-checkbox" type="checkbox" name="permissions[]" value="${permission.id}" ${checked} id="permission-${permission.id}">
                                    <span>${permission.name}</span>
                                </label>
                            </div>`;
                            });

                            permissionGroups +=
                                '</div></div>';
                        });

                        if (totalPermissions) {
                            editForm +=
                                '<div class="permission-view-summary">' +
                                    '<div>' +
                                        '<h6>' + userName + '</h6>' +
                                        '<p>' + categoryCount + ' categories available for assignment</p>' +
                                    '</div>' +
                                    '<span class="permission-view-count">' + totalPermissions + ' Permissions</span>' +
                                '</div>' +
                                '<div class="permission-view-grid">' + permissionGroups + '</div>';
                        } else {
                            editForm += '<div class="permission-view-empty">No permissions available to assign.</div>';
                        }

                        editForm += '</form>';
                        $('#editUserPermissionsContent').html(editForm);
                        $('#userPermissionsModal').modal('show');
                    } else {
                        alert('Error loading permissions.');
                    }
                },
                error: function() {
                    alert('Error fetching user permissions.');
                }
            });
        });


        // Save Permissions
        $('#saveUserPermissions').on('click', function() {
            var formData = $('#editUserPermissionsForm').serialize();

            $.ajax({
                url: '/mne/users/' + $('input[name="user_id"]').val() + '/update-permissions',
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    Swal.fire({
                        title: 'Permissions Updated!',
                        text: 'User permissions have been successfully updated.',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#userPermissionsModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert('Error updating permissions: ' + error);
                }
            });
        });




    });
</script>

<script>
    $(document).ready(function() {
        $('#role_name1').select2({
            dropdownParent: $('#editUserModal'),
            placeholder: "Select Role(s)",
            width: '100%'
        });

        $('#role_name').select2({
            placeholder: "Select Role(s)",
            width: '100%',
            dropdownParent: $('#addUserModal') // Adjust modal ID if necessary
        });

    });
</script>

@endpush
