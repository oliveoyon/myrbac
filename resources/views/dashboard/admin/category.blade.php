@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')

@push('styles')
    <style>
        @media (max-width: 576px) {
            .category-management-page .management-card {
                overflow: visible;
            }

            .category-management-page .management-table-wrap {
                overflow: visible;
            }

            #categoriesTable {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 7px;
                font-size: 13px;
            }

            #categoriesTable thead {
                display: none;
            }

            #categoriesTable,
            #categoriesTable tbody,
            #categoriesTable tr,
            #categoriesTable td {
                display: block;
                width: 100%;
            }

            #categoriesTable tr {
                padding: 8px 10px;
                border: 1px solid #e5e7eb;
                border-left: 3px solid #c30f08;
                border-radius: 7px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            }

            #categoriesTable tbody td {
                display: grid;
                grid-template-columns: 64px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                padding: 4px 0;
                border: 0;
                text-align: left;
            }

            #categoriesTable tbody td::before {
                content: attr(data-label);
                color: #6b7280;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
            }

            #categoriesTable .management-name-cell {
                color: #111827;
                font-weight: 700;
            }

            #categoriesTable .management-actions {
                display: flex;
                flex-wrap: nowrap;
                justify-content: flex-start;
                gap: 5px;
                width: auto;
            }

            #categoriesTable .management-actions .btn {
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

            .category-management-page .modal-dialog {
                margin: 10px;
            }

            .category-management-page .modal-body {
                padding: 14px;
            }

            .category-management-page .custombtn {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                text-align: initial !important;
            }

            .category-management-page .custombtn .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 380px) {
            #categoriesTable tbody td {
                grid-template-columns: 58px minmax(0, 1fr);
            }

            #categoriesTable .management-actions .btn {
                padding: 5px 7px;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="management-page category-management-page">
        <div class="management-header">
            <div>
                <h1>Category Management</h1>
                <p>Create and maintain permission categories for cleaner role and permission organization.</p>
            </div>
            <button class="btn btn-success" id="createDistrictBtn">
                <i class="fas fa-plus-square"></i>
                Add New Category
            </button>
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-tags me-2"></i>Category List</h2>
                <span class="management-count">{{ $categories->count() }} Categor{{ $categories->count() === 1 ? 'y' : 'ies' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm management-table" id="categoriesTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr id="category-{{ $category->id }}">
                                <td data-label="#">{{ $loop->iteration }}</td>
                                <td data-label="Name" class="category-name management-name-cell">{{ $category->name }}</td>
                                <td data-label="Actions">
                                    <div class="management-actions">
                                        <button class="btn btn-warning btn-sm editDistrictBtn" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteDistrictBtn"
                                            data-id="{{ $category->id }}">
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

        <!-- Fullscreen Modal for Create/Edit Category -->
        <div class="modal fade" id="districtModal" tabindex="-1" aria-labelledby="districtModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="districtModalLabel">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="management-modal-note">Use a short, clear category name for grouping permissions.</p>
                        <form id="districtForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="districtName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="districtName" name="name" placeholder="Example: Reports">
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
        document.getElementById('createDistrictBtn').addEventListener('click', function() {
            document.getElementById('districtForm').reset();
            document.getElementById('districtForm').setAttribute('action', '{{ route('categories.add') }}');
            document.getElementById('districtForm').setAttribute('method', 'POST');
            document.getElementById('districtModalLabel').textContent = 'Add New Category';
            var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
            districtModal.show();
        });

        document.querySelectorAll('.editDistrictBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var districtId = this.getAttribute('data-id');
                var districtName = this.getAttribute('data-name');

                document.getElementById('districtName').value = districtName;
                document.getElementById('districtModalLabel').textContent = 'Edit Category';
                document.getElementById('districtForm').setAttribute('action',
                    '{{ route('categories.update', ':districtId') }}'.replace(':districtId', districtId));

                document.getElementById('districtForm').setAttribute('method', 'POST');

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'PUT';
                document.getElementById('districtForm').appendChild(input);

                var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
                districtModal.show();
            });
        });

        document.querySelectorAll('.deleteDistrictBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var districtId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this category!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('categories.delete', ':districtId') }}'.replace(
                                ':districtId', districtId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('category-' + districtId).remove();

                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The category has been deleted.',
                                        icon: 'success',
                                        position: 'top-end',
                                        toast: true,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an error deleting the category.', 'error'
                                    );
                                }
                            });
                    }
                });
            });
        });

        document.getElementById('districtForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var submitButton = document.querySelector('#submitBtn');
            submitButton.disabled = true;

            var action = this.getAttribute('action');
            var method = this.getAttribute('method');
            var formData = new FormData(this);
            var districtModalElement = document.getElementById('districtModal');
            var districtModal = bootstrap.Modal.getInstance(districtModalElement);

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
                        if (districtModal) {
                            districtModal.hide(); // Hide modal first
                        }

                        // Show Swal message for at least 2 seconds
                        let swalInstance = Swal.fire({
                            title: 'Success!',
                            text: method === 'POST' ? 'Category added successfully.' :
                                'Category updated successfully.',
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
                                let districtRow = document.getElementById('category-' + data.id);
                                if (districtRow) {
                                    districtRow.querySelector('.category-name').textContent = formData
                                        .get('district_name');
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
