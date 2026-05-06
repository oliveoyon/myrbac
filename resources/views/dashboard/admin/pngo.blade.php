@extends('dashboard.layouts.admin-layout')

@section('title', 'PNGO Management')



@section('content')
    <section class="management-page">
        <div class="management-header">
            <div>
                <h1>PNGO Management</h1>
                <p>Create and maintain partner NGO names used in users, cases, filters, and reports.</p>
            </div>
            <button class="btn btn-success" id="createPngoBtn">
                <i class="fas fa-plus-square"></i>
                Add New PNGO
            </button>
        </div>

        <div class="management-card">
            <div class="management-card-header">
                <h2><i class="fas fa-handshake me-2"></i>PNGO List</h2>
                <span class="management-count">{{ $pngos->count() }} PNGO{{ $pngos->count() === 1 ? '' : 's' }}</span>
            </div>

            <div class="management-table-wrap table-responsive">
                <table class="table table-striped table-hover table-sm management-table" id="pngosTable">
                    <thead>
                        <tr>
                            <th style="width: 70px;">#</th>
                            <th>Name</th>
                            <th style="width: 190px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pngos as $pngo)
                            <tr id="pngo-{{ $pngo->id }}">
                                <td>{{ $loop->iteration }} </td>
                                <td class="pngo-name management-name-cell">{{ $pngo->name }}</td>
                                <td>
                                    <div class="management-actions">
                                        <button class="btn btn-warning btn-sm editPngoBtn" data-id="{{ $pngo->id }}"
                                            data-name="{{ $pngo->name }}">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </button>
                                        <button class="btn btn-danger btn-sm deletePngoBtn"
                                            data-id="{{ $pngo->id }}">
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

        <!-- Fullscreen Modal for Create/Edit Pngo -->
        <div class="modal fade" id="pngoModal" tabindex="-1" aria-labelledby="pngoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="pngoModalLabel">Add New Pngo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="management-modal-note">Enter the PNGO name exactly as it should appear in forms and reports.</p>
                        <form id="pngoForm" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="pngoName" class="form-label">PNGO Name</label>
                                <input type="text" class="form-control" id="pngoName" name="name" placeholder="Example: Partner NGO">
                            </div>
                            <div class="mb-0 text-end custombtn">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-success btn-primary" id="submitBtn">
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
        document.getElementById('createPngoBtn').addEventListener('click', function() {
            document.getElementById('pngoForm').reset();
            document.getElementById('pngoForm').setAttribute('action', '{{ route('pngos.add') }}');
            document.getElementById('pngoForm').setAttribute('method', 'POST');
            document.getElementById('pngoModalLabel').textContent = 'Add New Pngo';
            var pngoModal = new bootstrap.Modal(document.getElementById('pngoModal'));
            pngoModal.show();
        });

        document.querySelectorAll('.editPngoBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var pngoId = this.getAttribute('data-id');
                var pngoName = this.getAttribute('data-name');

                document.getElementById('pngoName').value = pngoName;
                document.getElementById('pngoModalLabel').textContent = 'Edit Pngo';
                document.getElementById('pngoForm').setAttribute('action',
                    '{{ route('pngos.update', ':pngoId') }}'.replace(':pngoId', pngoId));
                document.getElementById('pngoForm').setAttribute('method', 'POST');

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_method';
                input.value = 'PUT';
                document.getElementById('pngoForm').appendChild(input);

                var pngoModal = new bootstrap.Modal(document.getElementById('pngoModal'));
                pngoModal.show();
            });
        });

        document.querySelectorAll('.deletePngoBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var pngoId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this pngo!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        
                            fetch('{{ route('pngos.delete', ':pngoId') }}'.replace(
                                ':pngoId', pngoId), {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    document.getElementById('pngo-' + pngoId).remove();

                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: 'The pngo has been deleted.',
                                        icon: 'success',
                                        position: 'top-end',
                                        toast: true,
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                    });
                                } else {
                                    Swal.fire('Error!', 'There was an error deleting the pngo.',
                                        'error');
                                }
                            });
                    }
                });
            });
        });

        document.getElementById('pngoForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var submitButton = document.querySelector('#submitBtn');
            submitButton.disabled = true;

            var action = this.getAttribute('action');
            var method = this.getAttribute('method');
            var formData = new FormData(this);
            var pngoModalElement = document.getElementById('pngoModal');
            var pngoModal = bootstrap.Modal.getInstance(pngoModalElement);

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
                        if (pngoModal) {
                            pngoModal.hide(); // Hide modal first
                        }

                        // Show Swal message for at least 2 seconds
                        let swalInstance = Swal.fire({
                            title: 'Success!',
                            text: method === 'POST' ? 'Pngo added successfully.' :
                                'Pngo updated successfully.',
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
                                let pngoRow = document.getElementById('pngo-' + data.id);
                                if (pngoRow) {
                                    pngoRow.querySelector('.pngo-name').textContent = formData.get(
                                        'pngo_name');
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
