@extends('dashboard.layouts.admin-layout')

@section('title', 'Permission Management')

@push('styles')
    <style>
        @media (max-width: 576px) {
            .permission-management-page .management-card {
                overflow: visible;
            }

            .permission-management-page .management-table-wrap {
                overflow: visible;
            }

            #permissionsTable {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 7px;
                font-size: 13px;
            }

            #permissionsTable thead {
                display: none;
            }

            #permissionsTable,
            #permissionsTable tbody,
            #permissionsTable tr,
            #permissionsTable td {
                display: block;
                width: 100%;
            }

            #permissionsTable tr {
                padding: 8px 10px;
                border: 1px solid #e5e7eb;
                border-left: 3px solid #c30f08;
                border-radius: 7px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            }

            #permissionsTable tbody td {
                display: grid;
                grid-template-columns: 76px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                padding: 4px 0;
                border: 0;
                text-align: left;
            }

            #permissionsTable tbody td::before {
                content: attr(data-label);
                color: #6b7280;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
            }

            #permissionsTable .management-name-cell {
                color: #111827;
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            #permissionsTable .badge {
                justify-self: start;
                white-space: normal;
                text-align: left;
            }

            #permissionsTable .management-actions {
                display: flex;
                flex-wrap: nowrap;
                justify-content: flex-start;
                gap: 5px;
                width: auto;
            }

            #permissionsTable .management-actions .btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 4px;
                width: auto;
                min-width: 0;
                padding: 5px 8px;
                font-size: 12px;
                line-height: 1.2;
                white-space: nowrap;
            }

            .permission-management-page .modal-dialog {
                margin: 10px;
            }

            .permission-management-page .modal-body {
                padding: 14px;
            }

            .permission-management-page .custombtn {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                text-align: initial !important;
            }

            .permission-management-page .custombtn .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 380px) {
            #permissionsTable tbody td {
                grid-template-columns: 68px minmax(0, 1fr);
            }

            #permissionsTable .management-actions .btn {
                padding: 5px 7px;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="management-page permission-management-page">
        <div class="management-header">
            <div>
                <h1>Permission Management</h1>
                <p>Create and organize permissions by category before assigning them to roles.</p>
            </div>
            <button class="btn btn-success" id="createPermissionBtn">
                <i class="fas fa-plus-square"></i>
                Create Permission
            </button>
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-key me-2"></i>Permission List</h2>
                <span class="management-count">{{ $permissions->count() }} Permission{{ $permissions->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm management-table" id="permissionsTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            <tr id="permission-{{ $permission->id }}">
                                <td data-label="#">{{ $loop->iteration }}</td>
                                <td data-label="Name" class="permission-name management-name-cell">{{ $permission->name }}</td>
                                <td data-label="Category"><span class="badge bg-secondary">{{ $permission->category }}</span></td>
                                <td data-label="Actions">
                                    <div class="management-actions">
                                        <button class="btn btn-warning btn-sm editPermissionBtn" data-id="{{ $permission->id }}" data-name="{{ $permission->name }}" data-category="{{ $permission->category }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deletePermissionBtn" data-id="{{ $permission->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for Create/Edit Permission -->
        <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permissionModalLabel">Add New Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="management-modal-note">Choose a clear permission name and place it under the correct category.</p>
                        <form id="permissionForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="permissionName" class="form-label">Permission Name</label>
                                <input type="text" class="form-control" id="permissionName" name="name" required>
                            </div>

                            <!-- Category Dropdown -->
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-control" id="category" name="category" required>
                                <option value="">Select a Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->name }}">{{ $category->name }}</option>
                                @endforeach
                                    <!-- Add more categories as needed -->
                                </select>
                            </div>

                            <div class="mb-0 text-end custombtn">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i>
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.getElementById('createPermissionBtn').addEventListener('click', function() {
        document.getElementById('permissionForm').reset();
        document.getElementById('permissionForm').setAttribute('action', '{{ route('permissions.add') }}');
        document.getElementById('permissionForm').setAttribute('method', 'POST');
        document.getElementById('permissionModalLabel').textContent = 'Add New Permission';
        var permissionModal = new bootstrap.Modal(document.getElementById('permissionModal'));
        permissionModal.show();
    });

    document.querySelectorAll('.editPermissionBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            var permissionId = this.getAttribute('data-id');
            var permissionName = this.getAttribute('data-name');
            var permissionCategory = this.getAttribute('data-category');

            document.getElementById('permissionName').value = permissionName;
            document.getElementById('category').value = permissionCategory; // Set selected category
            document.getElementById('permissionModalLabel').textContent = 'Edit Permission';
            document.getElementById('permissionForm').setAttribute('action',
                '{{ route('permissions.update', ':permissionId') }}'.replace(':permissionId', permissionId));
            document.getElementById('permissionForm').setAttribute('method', 'POST');

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = '_method';
            input.value = 'PUT';
            document.getElementById('permissionForm').appendChild(input);

            var permissionModal = new bootstrap.Modal(document.getElementById('permissionModal'));
            permissionModal.show();
        });
    });

    document.querySelectorAll('.deletePermissionBtn').forEach(function(button) {
        button.addEventListener('click', function() {
            var permissionId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: 'This permission will be permanently deleted!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ route('permissions.delete', ':permissionId') }}'.replace(':permissionId', permissionId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('permission-' + permissionId).remove();

                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The permission has been deleted.',
                                icon: 'success',
                                position: 'top-end',
                                toast: true,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        } else {
                            Swal.fire('Error!', 'There was an error deleting the permission.', 'error');
                        }
                    });
                }
            });
        });
    });

    document.getElementById('permissionForm').addEventListener('submit', function(event) {
        event.preventDefault();

        var submitButton = document.querySelector('#submitBtn');
        submitButton.disabled = true;

        var action = this.getAttribute('action');
        var method = this.getAttribute('method');
        var formData = new FormData(this);
        var permissionModalElement = document.getElementById('permissionModal');
        var permissionModal = bootstrap.Modal.getInstance(permissionModalElement);

        fetch(action, {
            method: method,
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (permissionModal) {
                    permissionModal.hide(); // Hide modal first
                }

                // Show Swal message for at least 2 seconds
                let swalInstance = Swal.fire({
                    title: 'Success!',
                    text: method === 'POST' ? 'Permission added successfully.' : 'Permission updated successfully.',
                    icon: 'success',
                    position: 'top-end',
                    toast: true,
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                });

                // Update UI instantly
                setTimeout(() => {
                    if (method === 'POST') {
                        location.reload(); // Reload page after Swal message finishes
                    } else {
                        let permissionRow = document.getElementById('permission-' + data.id);
                        if (permissionRow) {
                            permissionRow.querySelector('.permission-name').textContent = formData.get('permission_name');
                        }
                    }
                }, 500); // Delay UI update slightly for smoothness

            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'There was an error processing your request.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            Swal.fire('Error!', 'Something went wrong!', 'error');
        })
        .finally(() => {
            submitButton.disabled = false;
        });
    });
</script>

@endpush
