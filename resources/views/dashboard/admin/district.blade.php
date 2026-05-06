@extends('dashboard.layouts.admin-layout')

@section('title', 'District Management')

@push('styles')
    <style>
        .district-page {
            display: grid;
            gap: 16px;
        }

        .district-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 20px;
            border: 1px solid #e1e5ea;
            border-left: 4px solid #c30f08;
            border-radius: 8px;
            background: #ffffff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
        }

        .district-header h1 {
            margin: 0;
            color: #1f2937;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .district-header p {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 14px;
        }

        .district-card {
            border: 1px solid #e0e6ed;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
        }

        .district-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 16px;
            border-bottom: 1px solid #f0d2cf;
            background: #fff7f6;
            color: #c30f08;
        }

        .district-card-header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            letter-spacing: 0;
        }

        .district-count {
            padding: 4px 10px;
            border: 1px solid #f0d2cf;
            border-radius: 999px;
            background: #fff;
            color: #7f1d1d;
            font-size: 12px;
            font-weight: 700;
            white-space: nowrap;
        }

        .district-table-wrap {
            padding: 14px;
        }

        #districtsTable {
            margin: 0;
        }

        #districtsTable thead th {
            background: #f8fafc;
            color: #374151;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            white-space: nowrap;
        }

        #districtsTable tbody td {
            vertical-align: middle;
        }

        .district-name-cell {
            color: #1f2937;
            font-weight: 700;
        }

        .district-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .district-actions .btn {
            min-width: 78px;
        }

        .district-modal-note {
            margin: 0 0 14px;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            background: #f9fafb;
            color: #6b7280;
            font-size: 13px;
        }

        @media (max-width: 768px) {
            .district-header,
            .district-card-header {
                align-items: stretch;
                flex-direction: column;
            }

            .district-header h1 {
                font-size: 19px;
            }

            .district-header .btn,
            .district-actions,
            .district-actions .btn {
                width: 100%;
            }

            .district-table-wrap {
                padding: 10px;
            }

            #districtsTable {
                min-width: 520px;
            }
        }
    </style>
@endpush


@section('content')
    <section class="district-page">
        <div class="district-header">
            <div>
                <h1>District Management</h1>
                <p>Create and maintain the district list used across users, cases, filters, and reports.</p>
            </div>
            <button class="btn btn-success" id="createDistrictBtn">
                <i class="fas fa-plus-square"></i>
                Add New District
            </button>
        </div>

        <div class="district-card">
            <div class="district-card-header">
                <h2><i class="fas fa-map-marker-alt me-2"></i>District List</h2>
                <span class="district-count">{{ $districts->count() }} District{{ $districts->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="district-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm" id="districtsTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($districts as $district)
                            <tr id="district-{{ $district->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="district-name district-name-cell">{{ $district->name }}</td>
                                <td>
                                    <div class="district-actions">
                                        <button class="btn btn-warning btn-sm editDistrictBtn" data-id="{{ $district->id }}"
                                            data-name="{{ $district->name }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deleteDistrictBtn"
                                            data-id="{{ $district->id }}">
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

        <!-- Fullscreen Modal for Create/Edit District -->
        <div class="modal fade" id="districtModal" tabindex="-1" aria-labelledby="districtModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="districtModalLabel">Add New District</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="district-modal-note">Enter the district name exactly as it should appear in forms and reports.</p>
                        <form id="districtForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="districtName" class="form-label">District Name</label>
                                <input type="text" class="form-control" id="districtName" name="name" placeholder="Example: Khulna">
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
            document.getElementById('districtForm').setAttribute('action', '{{ route('districts.add') }}');
            document.getElementById('districtForm').setAttribute('method', 'POST');
            document.getElementById('districtModalLabel').textContent = 'Add New District';
            var districtModal = new bootstrap.Modal(document.getElementById('districtModal'));
            districtModal.show();
        });

        document.querySelectorAll('.editDistrictBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var districtId = this.getAttribute('data-id');
                var districtName = this.getAttribute('data-name');

                document.getElementById('districtName').value = districtName;
                document.getElementById('districtModalLabel').textContent = 'Edit District';
                document.getElementById('districtForm').setAttribute('action',
                    '{{ route('districts.update', ':districtId') }}'.replace(':districtId', districtId));

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
                    text: 'You will not be able to recover this district!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('districts.delete', ':districtId') }}'.replace(
                                ':districtId', districtId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('district-' + districtId).remove();

                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The district has been deleted.',
                                        icon: 'success',
                                        position: 'top-end',
                                        toast: true,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });
                                } else {
                                    Swal.fire('Error!',
                                        'There was an error deleting the district.', 'error'
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
                            text: method === 'POST' ? 'District added successfully.' :
                                'District updated successfully.',
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
                                let districtRow = document.getElementById('district-' + data.id);
                                if (districtRow) {
                                    districtRow.querySelector('.district-name').textContent = formData
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
