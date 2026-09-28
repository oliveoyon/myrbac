@extends('dashboard.layouts.admin-layout')

@section('title', 'Court Management')

@push('styles')
    <style>
        @media (max-width: 576px) {
            .court-management-page .management-card,
            .court-management-page .management-table-wrap {
                overflow: visible;
            }

            #courtsTable {
                min-width: 0;
                border-collapse: separate;
                border-spacing: 0 7px;
                font-size: 13px;
            }

            #courtsTable thead {
                display: none;
            }

            #courtsTable,
            #courtsTable tbody,
            #courtsTable tr,
            #courtsTable td {
                display: block;
                width: 100%;
            }

            #courtsTable tr {
                padding: 8px 10px;
                border: 1px solid #e5e7eb;
                border-left: 3px solid #c30f08;
                border-radius: 7px;
                background: #fff;
                box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
            }

            #courtsTable tbody td {
                display: grid;
                grid-template-columns: 72px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                padding: 4px 0;
                border: 0;
                text-align: left;
            }

            #courtsTable tbody td::before {
                content: attr(data-label);
                color: #6b7280;
                font-size: 11px;
                font-weight: 800;
                text-transform: uppercase;
            }

            #courtsTable .management-name-cell,
            #courtsTable .court-district {
                color: #111827;
                font-weight: 700;
            }

            #courtsTable .management-actions {
                display: flex;
                flex-wrap: nowrap;
                justify-content: flex-start;
                gap: 5px;
                width: auto;
            }

            #courtsTable .management-actions .btn {
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

            .court-management-page .modal-dialog {
                margin: 10px;
            }

            .court-management-page .modal-body {
                padding: 14px;
            }

            .court-management-page .custombtn {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                text-align: initial !important;
            }

            .court-management-page .custombtn .btn {
                width: 100%;
                margin: 0;
            }
        }

        @media (max-width: 380px) {
            #courtsTable tbody td {
                grid-template-columns: 62px minmax(0, 1fr);
            }

            #courtsTable .management-actions .btn {
                padding: 5px 7px;
                font-size: 11px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="management-page court-management-page">
        <div class="management-header">
            <div>
                <h1>Court Management</h1>
                <p>Create district-specific court records for the Case Summary form.</p>
            </div>
            @can('Add Court')
            <button class="btn btn-success" id="createCourtBtn">
                <i class="fas fa-plus-square"></i>
                Add New Court
            </button>
            @endcan
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-gavel me-2"></i>Court List</h2>
                <span class="management-count">{{ $courts->count() }} Court{{ $courts->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm management-table" id="courtsTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Court Name</th>
                            <th>District</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($courts as $court)
                            <tr id="court-{{ $court->id }}">
                                <td data-label="#">{{ $loop->iteration }}</td>
                                <td data-label="Court" class="court-name management-name-cell">{{ $court->name }}</td>
                                <td data-label="District" class="court-district">{{ $court->district->name ?? 'Not mapped' }}</td>
                                <td data-label="Actions">
                                    <div class="management-actions">
                                        @can('Edit Court')
                                        <button class="btn btn-warning btn-sm editCourtBtn"
                                            data-id="{{ $court->id }}"
                                            data-name="{{ $court->name }}"
                                            data-district-id="{{ $court->district_id }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        @endcan
                                        @can('Delete Court')
                                        <button class="btn btn-danger btn-sm deleteCourtBtn" data-id="{{ $court->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                            Delete
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="courtModal" tabindex="-1" aria-labelledby="courtModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="courtModalLabel">Add New Court</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="management-modal-note">Enter the court name and assign the district. Duplicate names are allowed across districts, but not inside the same district.</p>
                        <form id="courtForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" id="courtMethod" value="PUT" disabled>
                            <div class="mb-3">
                                <label for="courtName" class="form-label">Court Name</label>
                                <input type="text" class="form-control" id="courtName" name="name" placeholder="Example: District and Sessions Judge Court">
                            </div>
                            <div class="mb-3">
                                <label for="courtDistrict" class="form-label">District</label>
                                <select class="form-control" id="courtDistrict" name="district_id" required>
                                    <option value="">Select District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">This mapping is used for district-dependent court dropdowns.</small>
                            </div>
                            <div class="mb-0 text-end custombtn">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-primary" id="submitCourtBtn">
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
        const courtModalElement = document.getElementById('courtModal');
        const courtModal = courtModalElement ? new bootstrap.Modal(courtModalElement) : null;

        document.getElementById('createCourtBtn')?.addEventListener('click', function() {
            document.getElementById('courtForm').reset();
            document.getElementById('courtForm').setAttribute('action', '{{ route('courts.add') }}');
            document.getElementById('courtForm').setAttribute('method', 'POST');
            document.getElementById('courtMethod').disabled = true;
            document.getElementById('courtModalLabel').textContent = 'Add New Court';
            courtModal?.show();
        });

        document.querySelectorAll('.editCourtBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                const courtId = this.getAttribute('data-id');
                const courtName = this.getAttribute('data-name');
                const districtId = this.getAttribute('data-district-id') || '';

                document.getElementById('courtName').value = courtName;
                document.getElementById('courtDistrict').value = districtId;
                document.getElementById('courtModalLabel').textContent = 'Edit Court';
                document.getElementById('courtForm').setAttribute('action', '{{ route('courts.update', ':courtId') }}'.replace(':courtId', courtId));
                document.getElementById('courtForm').setAttribute('method', 'POST');
                document.getElementById('courtMethod').disabled = false;
                courtModal?.show();
            });
        });

        document.querySelectorAll('.deleteCourtBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                const courtId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this court record.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#c30f08',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        return;
                    }

                    fetch('{{ route('courts.delete', ':courtId') }}'.replace(':courtId', courtId), {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('court-' + courtId)?.remove();
                            Swal.fire({
                                title: 'Deleted!',
                                text: 'The court has been deleted.',
                                icon: 'success',
                                position: 'top-end',
                                toast: true,
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                            });
                        } else {
                            Swal.fire('Error!', data.message || 'There was an error deleting the court.', 'error');
                        }
                    });
                });
            });
        });

        document.getElementById('courtForm')?.addEventListener('submit', function(event) {
            event.preventDefault();

            const submitButton = document.getElementById('submitCourtBtn');
            submitButton.disabled = true;

            const action = this.getAttribute('action');
            const method = this.getAttribute('method');
            const formData = new FormData(this);
            const isUpdate = formData.get('_method') === 'PUT';

            fetch(action, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json().then(data => ({ status: response.status, data })))
            .then(({ status, data }) => {
                if (status === 422) {
                    const messages = Object.values(data.errors || {}).flat();
                    Swal.fire('Please check the form.', messages[0] || data.message || 'Validation failed.', 'error');
                    return;
                }

                if (data.success) {
                    courtModal?.hide();
                    Swal.fire({
                        title: 'Success!',
                        text: isUpdate ? 'Court updated successfully.' : 'Court added successfully.',
                        icon: 'success',
                        position: 'top-end',
                        toast: true,
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });

                    setTimeout(() => {
                        if (!isUpdate) {
                            location.reload();
                            return;
                        }

                        const courtRow = document.getElementById('court-' + data.court.id);
                        if (courtRow) {
                            courtRow.querySelector('.court-name').textContent = data.court.name;
                            courtRow.querySelector('.court-district').textContent = data.court.district ? data.court.district.name : 'Not mapped';
                            const editButton = courtRow.querySelector('.editCourtBtn');
                            editButton?.setAttribute('data-name', data.court.name);
                            editButton?.setAttribute('data-district-id', data.court.district_id || '');
                        }
                    }, 500);
                } else {
                    Swal.fire('Error!', data.message || 'There was an error processing your request.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error!', 'Something went wrong.', 'error');
            })
            .finally(() => {
                submitButton.disabled = false;
            });
        });
    </script>
@endpush
