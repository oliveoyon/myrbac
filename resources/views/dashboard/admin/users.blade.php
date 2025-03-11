@extends('dashboard.layouts.admin-layout')

@section('title', 'User Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush


@section('content')
    <section>
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col">
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus-square mr-1"></i> Add User
                    </button>
                </div>
            </div>
            

            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                <ul id="errorList">
                    <!-- Error messages will be inserted here dynamically -->
                </ul>
            </div>
            <table class="table table-bordered table-striped table-hover table-sm" id="user-table">
                <thead style="border-top: 1px solid #b4b4b4">
                    <th style="width: 10px">#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>District</th>
                    <th>PNGO</th>
                    <th>Status</th>
                    <th style="width: 40px">Action</th>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->district ? $user->district->name : 'No District' }}</td>
                            <td>{{ $user->pngo ? $user->pngo->name : 'No PNGO' }}</td>
                            <td>{{ $user->status == 1 ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-warning btn-xs" data-id="{{ $user->id }}"
                                        id="editUserBtn"><i class="fas fa-edit"></i></button>
                                    <button type="button" class="btn btn-danger btn-xs" data-id="{{ $user->id }}"
                                        id="deleteUserBtn"><i class="fas fa-trash-alt"></i></button>
                                </div>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>


            <!-- Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('addUser') }}" method="POST" autocomplete="off" id="add-user-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label required" for="name">User Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter name">
                                    <span class="text-danger error-text name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter email">
                                    <span class="text-danger error-text email_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="district_id">District</label>
                                    <select class="form-control" name="district_id" id="district_id">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text district_id_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="role_name">Roles</label>
                                    <select class="form-control" name="role_name[]" id="role_name" multiple>
                                        <option value="">Select Role (Multiple)</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text role_name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="pngo_id">PNGO</label>
                                    <select class="form-control" name="pngo_id" id="pngo_id">
                                        <option value="">Select PNGO</option>
                                        @foreach ($pngos as $pngo)
                                            <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text pngo_id_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
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


            <div class="modal fade editUser" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('updateUserDetails') }}" method="post" autocomplete="off"
                                id="update-user-form">
                                @csrf
                                <input type="hidden" name="uid">
                                <div class="mb-3">
                                    <label class="form-label required" for="name">User Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter name">
                                    <span class="text-danger error-text name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter email">
                                    <span class="text-danger error-text email_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="district_id">District</label>
                                    <select class="form-control" name="district_id" id="district_id">
                                        <option value="">Select District</option>
                                        @foreach ($districts as $district)
                                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text district_id_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="role_name">Roles</label>
                                    <select class="form-control" name="role_name[]" id="role_name" multiple>
                                        <option value="">Select Role (Multiple)</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text role_name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="pngo_id">PNGO</label>
                                    <select class="form-control" name="pngo_id" id="pngo_id">
                                        <option value="">Select PNGO</option>
                                        @foreach ($pngos as $pngo)
                                            <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-text pngo_id_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label required" for="status">Status</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    <span class="text-danger error-text status_error"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Clear error messages on modal close
            $('#addUserModal').on('hidden.bs.modal', function () {
        $('#add-user-form').find('span.error-text').text('');
    });

    $('#add-user-form').on('submit', function (e) {
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
            beforeSend: function () {
                // Clear previous error messages
                $(form).find('span.error-text').text('');
            },
            success: function (data) {
                if (data.code == 0) {
                    // Handle validation errors
                    $.each(data.error, function (prefix, val) {
                        // Find the error span by class name and set the error text
                        $(form).find('span.' + prefix + '_error').text(val[0]);
                    });

                    // Focus on the first error field
                    var firstErrorField = $(form).find('span.error-text').first().prev('input, select');
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

                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 1000);
                }
            },
            error: function (xhr, status, error) {
                // Handle unexpected errors with toastr
                toastr.error('Something went wrong! Please try again.');
                console.log(xhr.responseText); // For debugging
            },
            complete: function () {
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
                    $('.editUser').find('input[name="uid"]').val(data.details.id);
                    $('.editUser').find('input[name="name"]').val(data.details.name);
                    $('.editUser').find('input[name="email"]').val(data.details.email);
                    $('.editUser').find('select[name="district_id"]').val(data.details.district_id);
                    $('.editUser').find('select[name="role_name"]').val(data.details.role_name);
                    $('.editUser').find('select[name="pngo_id"]').val(data.details.pngo_id);
                    $('.editUser').find('select[name="status"]').val(data.details
                        .status);
                    $('.editUser').modal('show');
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




        });
    </script>
@endpush
