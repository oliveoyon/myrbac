@extends('dashboard.layouts.admin-layout')

@section('title', 'Roles and Permissions')

@push('styles')
    <style>
        .modal-body {
            overflow-y: auto;
            max-height: 90vh;
            /* Keeps content scrollable within the full-screen modal */
        }

        .category-box {
            margin-bottom: 14px;
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            background-color: #ffffff;
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
            border: 1px solid #d8dee6;
            border-radius: 6px;
            background-color: #fff;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
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
    </style>
@endpush

@section('content')
    <section class="management-page">
        <div class="management-header">
            <div>
                <h1>Roles and Permissions</h1>
                <p>Review each role and adjust the permissions assigned to it.</p>
            </div>
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-users-cog me-2"></i>Role Permission Matrix</h2>
                <span class="management-count">{{ $roles->count() }} Role{{ $roles->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                    <table class="table table-bordered table-striped table-hover table-sm management-table">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th style="width: 190px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                                <tr>
                                    <td class="management-name-cell">{{ $role->name }}</td>
                                    <td>
                                        <div class="management-actions">
                                            <button class="btn btn-info btn-sm view-permissions" data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}"
                                                data-toggle="modal" data-target="#permissionsViewModal">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </button>

                                            <button class="btn btn-warning btn-sm edit-permissions" data-id="{{ $role->id }}"
                                                data-name="{{ $role->name }}" data-toggle="modal" data-target="#permissionsEditModal">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </section>

    <!-- View Permissions Modal -->
    <div class="modal fade" id="permissionsViewModal" tabindex="-1" role="dialog"
        aria-labelledby="permissionsViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionsViewModalLabel">Role Permissions</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Dynamic content will be loaded here (view permissions) -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Permissions Modal -->
    <div class="modal fade" id="permissionsEditModal" tabindex="-1" aria-labelledby="permissionsEditModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Edit Role Permissions</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editPermissionsContent">
                        <!-- Permissions form will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="savePermissions">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // When View button is clicked
            $('.view-permissions').on('click', function() {
                var roleId = $(this).data('id'); // Get role ID
                var roleName = $(this).data('name');
                $.ajax({
                    url: '/mne/role/' + roleId + '/permissions', // Fetch permissions for viewing
                    method: 'GET',
                    success: function(response) {
                        var permissionsList = '';
                        var totalPermissions = 0;
                        var categoryCount = Object.keys(response.permissions || {}).length;

                        // Loop through grouped permissions
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
                                    .name + '</span></div>';
                            });

                            permissionsList +=
                                '</div></div>'; // Close permissions grid and category box
                        });

                        if (!totalPermissions) {
                            permissionsList = '<div class="permission-view-empty">No permissions assigned to this role.</div>';
                        } else {
                            permissionsList = '<div class="permission-view-grid">' + permissionsList + '</div>';
                        }

                        permissionsList =
                            '<div class="permission-view-shell">' +
                                '<div class="permission-view-summary">' +
                                    '<div>' +
                                        '<h6>' + roleName + '</h6>' +
                                        '<p>' + categoryCount + ' categories with assigned permissions</p>' +
                                    '</div>' +
                                    '<span class="permission-view-count">' + totalPermissions + ' Permissions</span>' +
                                '</div>' +
                                permissionsList +
                            '</div>';

                        // Display in modal
                        $('#permissionsViewModal .modal-body').html(permissionsList);
                    },
                    error: function() {
                        alert('Error loading permissions.');
                    }
                });
            });

            $('.edit-permissions').on('click', function() {
                var roleId = $(this).data('id');
                var roleName = $(this).data('name');

                $('#modalTitle').text('Edit Permissions for ' + roleName);

                $.ajax({
                    url: '/mne/role/' + roleId + '/edit-permissions',
                    method: 'GET',
                    success: function(response) {
                        if (response && response.role && response.allPermissions) {
                            var editForm = '<form id="editPermissionsForm">';
                            editForm += '<input type="hidden" name="role_id" value="' + roleId +
                                '">';

                            // Group permissions by category
                            var groupedPermissions = {};

                            // Iterate over all permissions
                            $.each(response.allPermissions, function(index, permission) {
                                var category = permission.category || 'Uncategorized';
                                if (!groupedPermissions[category]) {
                                    groupedPermissions[category] = [];
                                }
                                groupedPermissions[category].push(permission);
                            });

                            // Render permissions grouped by category
                            $.each(groupedPermissions, function(category, permissions) {
                                editForm += '<div class="mb-4">';
                                editForm +=
                                    '<h5 class="text-danger classic-category-title">' +
                                    category + '</h5>';
                                editForm +=
                                    '<div class="classic-row">'; // Apply the grid layout

                                $.each(permissions, function(index, permission) {
                                    var checked = response.rolePermissions[
                                            category] &&
                                        response.rolePermissions[category].some(
                                            p => p.id === permission.id) ?
                                        'checked' : '';

                                    editForm += `
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input classic-checkbox" type="checkbox" name="permissions[]" value="${permission.id}" ${checked} id="permission-${permission.id}">
                                <label for="permission-${permission.id}" class="classic-checkbox-label">${permission.name}</label>
                            </div>
                        </div>`;
                                });

                                editForm +=
                                    '</div></div>'; // Close the row and category div
                            });

                            editForm += '</form>';
                            $('#editPermissionsContent').html(editForm);
                            $('#permissionsEditModal').modal('show');
                        } else {
                            alert('Error loading permissions.');
                        }
                    },
                    error: function() {
                        alert('Error fetching permissions.');
                    }
                });
            });


            // Save permissions
            $('#savePermissions').on('click', function() {
                var formData = $('#editPermissionsForm').serialize();

                $.ajax({
                    url: '/mne/role/update-permissions/' + $('input[name="role_id"]').val(),
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // Show SweetAlert after success
                        Swal.fire({
                            title: 'Permissions Updated!',
                            text: 'The permissions have been successfully updated.',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#permissionsEditModal').modal(
                                'hide'); // Hide the modal after saving
                            location
                                .reload(); // Optionally, reload the page to reflect changes
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert('Error updating permissions: ' + error);
                    }
                });
            });

            // To handle the modal closing properly after saving
            $('#permissionsEditModal').on('hidden.bs.modal', function() {
                // Reset the content of the modal to avoid stale data on reopening
                $('.modal-backdrop').remove();
                $('#editPermissionsContent').html('');
            });
        });
    </script>
@endpush
