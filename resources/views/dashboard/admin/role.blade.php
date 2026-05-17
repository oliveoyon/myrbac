@extends('dashboard.layouts.admin-layout')

@section('title', 'Role Management')

@push('styles')
    <style>
        @media (max-width: 576px) {
            .role-management-page .management-card {
                overflow: visible;
            }

            .role-management-page .management-table-wrap {
                overflow: visible;
            }

            #rolesTable {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 7px;
                font-size: 13px;
            }

            #rolesTable thead {
                display: none;
            }

            #rolesTable,
            #rolesTable tbody,
            #rolesTable tr,
            #rolesTable td {
                display: block;
                width: 100%;
            }

            #rolesTable tr {
                padding: 8px 10px;
                border: 1px solid #e5e7eb;
                border-left: 3px solid #c30f08;
                border-radius: 7px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            }

            #rolesTable tbody td {
                display: grid;
                grid-template-columns: 64px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                padding: 4px 0;
                border: 0;
                text-align: left;
            }

            #rolesTable tbody td::before {
                content: attr(data-label);
                color: #6b7280;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
            }

            #rolesTable .management-name-cell {
                color: #111827;
                font-weight: 700;
            }

            #rolesTable .management-actions {
                display: flex;
                flex-wrap: nowrap;
                justify-content: flex-start;
                gap: 5px;
                width: auto;
            }

            #rolesTable .management-actions .btn {
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

            .role-management-page .modal-dialog {
                margin: 10px;
            }

            .role-management-page .modal-body {
                padding: 14px;
            }

            .role-management-page .custombtn {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                text-align: initial !important;
            }

            .role-management-page .custombtn .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 380px) {
            #rolesTable tbody td {
                grid-template-columns: 58px minmax(0, 1fr);
            }

            #rolesTable .management-actions .btn {
                padding: 5px 7px;
                font-size: 11px;
            }
        }
    </style>
@endpush


@section('content')
    <section class="management-page role-management-page">
        <div class="management-header">
            <div>
                <h1>Role Management</h1>
                <p>Create and maintain user roles before assigning permissions to them.</p>
            </div>
            <button class="btn btn-success" id="createRoleBtn">
                <i class="fas fa-plus-square"></i>
                Create Role
            </button>
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-user-tag me-2"></i>Role List</h2>
                <span class="management-count">{{ $roles->count() }} Role{{ $roles->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm management-table" id="rolesTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr id="role-{{ $role->id }}">
                                <td data-label="#">{{ $loop->iteration }} </td>
                                <td data-label="Name" class="role-name management-name-cell">{{ $role->name }}</td>
                                <td data-label="Actions">
                                    <div class="management-actions">
                                        <button class="btn btn-warning btn-sm editRoleBtn" data-id="{{ $role->id }}"
                                            data-name="{{ $role->name }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteRoleBtn"
                                            data-id="{{ $role->id }}">
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

        <!-- Fullscreen Modal for Create/Edit Role -->
        <div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="roleModalLabel">Add New Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="management-modal-note">Use a role name that clearly describes what the user can do.</p>
                        <form id="roleForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="roleName" class="form-label">Role Name</label>
                                <input type="text" class="form-control" id="roleName" name="name" placeholder="Example: District Project Officer">
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
        document.getElementById('createRoleBtn').addEventListener('click', function() {
            document.getElementById('roleForm').reset();
            document.getElementById('roleForm').setAttribute('action', '{{ route('roles.add') }}');
            document.getElementById('roleForm').setAttribute('method', 'POST');
            document.getElementById('roleModalLabel').textContent = 'Add New Role';
            var roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
            roleModal.show();
        });

        document.querySelectorAll('.editRoleBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var roleId = this.getAttribute('data-id');
                var roleName = this.getAttribute('data-name');

                document.getElementById('roleName').value = roleName;
                document.getElementById('roleModalLabel').textContent = 'Edit Role';
                document.getElementById('roleForm').setAttribute('action',
                    '{{ route('roles.update', ':roleId') }}'.replace(':roleId', roleId));
                document.getElementById('roleForm').setAttribute('method', 'POST');

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'PUT';
                document.getElementById('roleForm').appendChild(input);

                var roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
                roleModal.show();
            });
        });

        document.querySelectorAll('.deleteRoleBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var roleId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this role!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        fetch('{{ route('roles.delete', ':roleId') }}'.replace(
                                ':roleId', roleId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })


                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('role-' + roleId).remove();

                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The role has been deleted.',
                                        icon: 'success',
                                        position: 'top-end',
                                        toast: true,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an error deleting the role.',
                                        'error');
                                }
                            });
                    }
                });
            });
        });

        document.getElementById('roleForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var submitButton = document.querySelector('#submitBtn');
            submitButton.disabled = true;

            var action = this.getAttribute('action');
            var method = this.getAttribute('method');
            var formData = new FormData(this);
            var roleModalElement = document.getElementById('roleModal');
            var roleModal = bootstrap.Modal.getInstance(roleModalElement);

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
                        if (roleModal) {
                            roleModal.hide(); // Hide modal first
                        }

                        // Show Swal message for at least 2 seconds
                        let swalInstance = Swal.fire({
                            title: 'Success!',
                            text: method === 'POST' ? 'Role added successfully.' :
                                'Role updated successfully.',
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
                                let roleRow = document.getElementById('role-' + data.id);
                                if (roleRow) {
                                    roleRow.querySelector('.role-name').textContent = formData.get(
                                        'role_name');
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
