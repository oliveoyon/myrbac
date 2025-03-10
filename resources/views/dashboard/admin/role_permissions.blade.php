@extends('dashboard.layouts.admin-layout')

@section('title', 'Pngo Management')

@push('styles')
<style>
    .modal-body {
        overflow-y: auto;
        max-height: 90vh; /* Keeps content scrollable within the full-screen modal */
    }

    /* Styling for each category box */
    .category-box {
        padding: 20px;
        background-color: #ffffff;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px; /* Add space between categories */
    }

    /* Category Header Styling */
    .category-header {
        font-size: 18px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
    }

    /* Permissions Grid inside each category */
    .permissions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 15px; /* Space between permission items */
    }

    /* Individual Permission Item Styling */
    .permission-item {
        padding: 10px 15px;
        background-color: #f4f7fc;
        border-left: 5px solid #4CAF50;
        margin-bottom: 10px;
        color: #333;
        font-size: 15px;
        border-radius: 8px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .permission-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 12px rgba(0, 0, 0, 0.1);
    }

    /* Custom Styling for the Edit Permissions Form */
    .edit-permission-header {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #2c3e50;
    }

    .category-box {
        background-color: #f9fafb;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .permission-checkbox {
        margin-right: 10px;
    }

    /* Ensuring the grid remains neat on smaller screens */
    @media (max-width: 768px) {
        .permissions-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
    }
</style>
@endpush

@section('content')
<section>
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="container">
                <h2>Roles and Permissions</h2>
            
                <table class="table">
                    <thead>
                        <tr>
                            <th>Role Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                <!-- View Permissions Button -->
                                <button class="btn btn-info view-permissions" data-id="{{ $role->id }}" data-toggle="modal" data-target="#permissionsViewModal">View</button>
                                 
                                <!-- Edit Permissions Button -->
                                <button class="btn btn-warning edit-permissions" data-id="{{ $role->id }}" data-toggle="modal" data-target="#permissionsEditModal">Edit</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- View Permissions Modal -->
<div class="modal fade" id="permissionsViewModal" tabindex="-1" role="dialog" aria-labelledby="permissionsViewModalLabel" aria-hidden="true">
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
<div class="modal fade" id="permissionsEditModal" tabindex="-1" role="dialog" aria-labelledby="permissionsEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionsEditModalLabel">Edit Role Permissions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be loaded here (edit permissions) -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="saveChangesBtn" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"></script>

<script>
   $(document).ready(function () {
    // When View button is clicked
    $('.view-permissions').on('click', function () {
        var roleId = $(this).data('id'); // Get role ID
        $.ajax({
            url: '/mne/role/' + roleId + '/permissions', // Fetch permissions for viewing
            method: 'GET',
            success: function (response) {
                var permissionsList = '';

                // Loop through grouped permissions
                $.each(response.permissions, function (category, permissions) {
                    permissionsList += '<div class="category-box">'; // Start of category box
                    permissionsList += '<div class="category-header">' + category + '</div>';  // Category name header
                    permissionsList += '<div class="permissions-grid">';  // Start of permission grid
                    
                    permissions.forEach(function (permission) {
                        permissionsList += '<div class="permission-item">' + permission.name + '</div>';
                    });
                    
                    permissionsList += '</div></div>'; // Close permissions grid and category box
                });

                // Display in modal
                $('#permissionsViewModal .modal-body').html(permissionsList);
            },
            error: function () {
                alert('Error loading permissions.');
            }
        });
    });

    // When Edit button is clicked
    $('.edit-permissions').on('click', function () {
        var roleId = $(this).data('id'); // Get the role ID
        
        $.ajax({
            url: '/mne/role/' + roleId + '/edit-permissions', // Fetch permissions for editing
            method: 'GET',
            success: function (response) {
                if (response && response.role && response.allPermissions) {
                    var editForm = '<form id="editPermissionsForm">' +
                                   '<div class="edit-permission-header">Edit Permissions for Role: ' + response.role.name + '</div>' +
                                   '<input type="hidden" name="role_id" value="' + roleId + '">';

                    // Iterate over all permissions to categorize and display them
                    $.each(response.allPermissions, function (index, permission) {
                        var category = permission.category;
                        var checked = '';  // Default for unassigned permissions
                        
                        // Check if the permission is assigned to the role
                        $.each(response.rolePermissions, function (assignedCategory, assignedPermissions) {
                            if (assignedCategory === category) {
                                $.each(assignedPermissions, function (assignedIndex, assignedPermission) {
                                    if (assignedPermission.id === permission.id) {
                                        checked = 'checked';  // Mark as checked if assigned
                                    }
                                });
                            }
                        });

                        // Render the category and permissions
                        if (!editForm.includes('<div class="category-' + category + '">')) {
                            // Add the category header if it hasn't been added already
                            editForm += '<div class="category-' + category + '">' +
                                        '<h5>' + category + '</h5>' +
                                        '<div class="permissions-list">';
                        }

                        editForm += '<div class="permission-item">' +
                                    '<input type="checkbox" name="permissions[]" value="' + permission.id + '" ' + checked + ' class="permission-checkbox">' +
                                    permission.name + 
                                    '</div>';
                    });

                    // Close the permissions list for each category
                    editForm += '</div></div>';

                    editForm += '</form>';

                    // Display the dynamically generated form in the modal body
                    $('#permissionsEditModal .modal-body').html(editForm);

                    // Show the modal after loading the content
                    $('#permissionsEditModal').modal('show');
                } else {
                    alert('Invalid response data.');
                }
            },
            error: function () {
                alert('Error loading permissions.');
            }
        });
    });

    // Handle Save Changes
    $('#saveChangesBtn').on('click', function () {
        var formData = $('#editPermissionsForm').serialize(); // Get form data

        $.ajax({
            url: '/mne/role/' + $('input[name="role_id"]').val() + '/update-permissions', // Send updated permissions
            method: 'POST',
            data: formData + '&_token={{ csrf_token() }}', // Include CSRF token
            success: function () {
                Swal.fire({
                    title: 'Success',
                    text: 'Permissions updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'Okay'
                }).then(() => {
                    $('#permissionsEditModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page
                });
            },
            error: function () {
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred while saving permissions.',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            }
        });
    });
});

</script>
@endpush
