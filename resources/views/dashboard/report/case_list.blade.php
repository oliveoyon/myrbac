@extends('dashboard.layouts.admin-layout')
@section('title', 'Case List')
@push('styles')
<style>
    .form-check-input.verify-toggle {
        width: 2em;
        height: 1.1em;
        border: 1px solid #6c757d;
        /* thinner border */
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.15);
        transition: all 0.2s ease-in-out;
        outline: none;
    }


    a::after {
        content: none !important;
    }

    td a {
        display: inline-block;
        margin-right: 5px;
        /* Adjust as needed */
    }

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')


<section>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <form action="{{ route('case_list1') }}" method="POST" autocomplete="off" id="get-case-list">
                    @csrf
                    <div class="card">
                        <div class="card-header text-bg-secondary d-flex justify-content-between align-items-center">
                            <h6 class="card-title">Case Details Filter: District, PNGO, and Date Range</h6>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#caseCardBody">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="caseCardBody">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <!-- Institute Filter -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm institute" name="institute"
                                                id="institute">
                                                <option value="">All Institute</option>
                                                <option value="Court">Court</option>
                                                <option value="Prison">Prison</option>
                                                <option value="Police Station">Police Station</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- District Filter -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm district_id" name="district_id"
                                                id="district_id">
                                                @php
                                                $userDistrictId = auth()->user()->district_id;
                                                @endphp

                                                @if ($userDistrictId)
                                                @foreach ($districts as $district)
                                                @if ($district->id == $userDistrictId)
                                                <option value="{{ $district->id }}" selected>
                                                    {{ $district->name }}
                                                </option>
                                                @endif
                                                @endforeach
                                                @else
                                                <option value="">All Districts</option>
                                                @foreach ($districts as $district)
                                                <option value="{{ $district->id }}">{{ $district->name }}
                                                </option>
                                                @endforeach
                                                @endif

                                            </select>
                                        </div>
                                    </div>

                                    <!-- PNGO Filter -->
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm pngo_id" name="pngo_id"
                                                id="pngo_id">
                                                @php
                                                $userPngoId = auth()->user()->pngo_id;
                                                @endphp

                                                @if ($userPngoId)
                                                @foreach ($pngos as $pngo)
                                                @if ($pngo->id == $userPngoId)
                                                <option value="{{ $pngo->id }}" selected>
                                                    {{ $pngo->name }}
                                                </option>
                                                @endif
                                                @endforeach
                                                @else
                                                <option value="">All PNGO</option>
                                                @foreach ($pngos as $pngo)
                                                <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <!-- From Date Filter -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="date" class="form-control form-control-sm" name="from_date"
                                                id="from_date" placeholder="From Date">
                                        </div>
                                    </div>

                                    <!-- To Date Filter -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="date" class="form-control form-control-sm" name="to_date"
                                                id="to_date" placeholder="To Date">
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm status" name="status"
                                                id="status">
                                                <option value="">Status</option>
                                                <option value="1">Pending</option>
                                                <option value="2">Verified by DPO</option>
                                                <option value="3">Verified by MNEO</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit"
                                                class="btn btn-primary btn-sm btn-block">Submit</button>
                                        </div>
                                    </div>
                                </div> <!-- row -->
                            </div>


                        </div> <!-- collapse -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="contents d-none mt-3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline">
                    <div class="card-header text-bg-dark d-flex justify-content-between align-items-center">
                        <h6 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Case List
                        </h6>
                        <button class="btn btn-success btn-sm" id="printButton">
                            <i class="fas fa-print mr-1"></i> Print Report
                        </button>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="alert alert-danger" id="errorAlert" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>
                        <div id="reportDiv">
                            <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">#</th>
                                        <th>Central ID</th>
                                        <th>Institute</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Date of Interview</th>
                                        <th>District</th>
                                        <th>PNGO</th>
                                        <th>Status</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="result-table-body">
                                    <!-- Rows will be dynamically populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- card-body -->
                </div> <!-- card -->
            </div>
        </div>
    </div>
</section>

<!-- PNGO Details Modal -->
<div class="modal fade" id="pngoModal" tabindex="-1" role="dialog" aria-labelledby="pngoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pngoModalLabel">PNGO Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="pngoDetails">
                    <!-- Dynamic content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>








@endsection

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>



<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $(document).ready(function() {
        $('#get-case-list').submit(function(e) {
            e.preventDefault();

            // Disable the submit button to prevent double-clicking
            $(this).find(':submit').prop('disabled', true);

            // Show the loader overlay
            $('#loader-overlay').show();

            var form = this;

            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(response) {
                    console.log(response); // Debugging

                    var tableBody = $('#result-table-body');
                    tableBody.empty(); // Clear existing rows

                    if (response && response.cases && Array.isArray(response.cases) &&
                        response.cases.length > 0) {
                        let serialNumber = 1;
                        response.cases.forEach(function(caseData) {
                            var row = `<tr>
                            <td>${serialNumber}</td>
                            <td>${caseData.central_id || 'N/A'}</td>
                            <td>${caseData.institute || 'N/A'}</td>
                            <td>${caseData.full_name || 'N/A'}</td>
                            <td>${caseData.phone_number || 'N/A'}</td>
                            <td>${caseData.legal_representation_date 
                                ? new Date(caseData.interview_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) 
                                : 'N/A'}</td>
                            <td>${caseData.district?.name || 'N/A'}</td>
                            <td>${caseData.pngo?.name || 'N/A'}</td>
                            <td>
                                ${Number(caseData.status) === 1 ? `
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-danger">Pending</span>
                                                @can('Verified by DPO')
                                                  <div class="form-check form-switch no-print">
                                                        <input class="form-check-input verify-toggle" type="checkbox" data-id="${caseData.id}">
                                                    </div>  
                                                @endcan
                                            </div>
                                        ` : 
                                Number(caseData.status) === 2 ? `
                                
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success">Verified by DPO</span>
                                         @can('Verified by MNEO')
                                            <div class="form-check form-switch no-print">
                                                <input class="form-check-input verify-toggle-dpo" type="checkbox" data-ids="${caseData.id}">
                                            </div>  
                                        @endcan
                                    </div>
                                    
                                    ` :
                                Number(caseData.status) === 3 ?
                                    '<span class="badge bg-primary">Verified by MNEO</span>' :
                                    '<span class="badge bg-secondary">N/A</span>'}
                            </td>



                            <td class="no-print">
                                <a href="javascript:void(0);" class="pngo-link" data-pngo-id="${caseData.id}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a href="javascript:void(0);" class="edit-link" data-edit-id="${caseData.id}">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="javascript:void(0);" class="file-link" data-file-id="${caseData.id}">
                                    <i class="fa fa-paperclip"></i>
                                </a>
                            </td>
                        </tr>`;

                            tableBody.append(row);
                            serialNumber++;
                        });

                        $('.contents').removeClass('d-none'); // Show results section
                        $('#errorAlert').hide(); // Hide error alert if successful
                    } else {
                        console.warn("No cases found in response:", response);
                        $('#errorAlert').show().text('No cases found.');
                    }
                },

                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    $('#errorAlert').show().text('An error occurred while fetching data.');
                },

                complete: function() {
                    // Enable the submit button and hide the loader overlay
                    $(form).find(':submit').prop('disabled', false);
                    $('#loader-overlay').hide();
                }
            });
        });
    });
</script>

<script>
    $('#printButton').click(function() {
        var data = $('#reportDiv').clone(); // Clone to keep original structure
        data.find('.no-print').remove(); // Remove unwanted elements

        $('#loader-overlay').show(); // Show loader

        $.ajax({
            url: '/mne/generate-pdf',
            method: 'POST',
            data: {
                pdf_data: data.html(), // Send modified HTML
                title: 'Case List',
                orientation: 'L',
                fname: 'Case List.pdf',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    $('#pdfModal').remove(); // Remove existing modal before adding a new one

                    var modalContent = `
                    <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-fullscreen">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">
                                        <img src="/path/to/loader.gif" alt="Loading...">
                                    </div>
                                    <iframe id="pdfIframe" src="${response.pdf_url}" style="width: 100%; height: 80vh; display: none;"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>`;

                    $('body').append(modalContent); // Append modal
                    $('#pdfModal').modal('show'); // Show modal

                    $('#pdfIframe').on('load', function() {
                        $('#pdfLoaderOverlay').hide(); // Hide loader when PDF loads
                        $('#pdfIframe').show();
                    });

                } else {
                    alert('Error generating PDF. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                alert('Error generating PDF. Please try again.');
            },
            complete: function() {
                $('#loader-overlay').hide(); // Hide main loader
            }
        });
    });

    // URL Validation Function
    function isValidUrl(url) {
        return /^https?:\/\/.+/.test(url);
    }






    $(document).on('click', '.pngo-link', function() {
        var pngoId = $(this).data('pngo-id'); // Get PNGO ID from data attribute

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/mne/generate-form', // Update with the correct URL to fetch the PDF URL
            method: 'POST',
            data: {
                title: 'Case List',
                orientation: 'P',
                fname: 'Case List.pdf',
                id: pngoId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    // Create the modal content dynamically with the response PDF URL
                    var modalContent =
                        '<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                    modalContent +=
                        '<div class="modal-dialog modal-dialog-centered modal-fullscreen">'; // Changed to fullscreen modal
                    modalContent += '<div class="modal-content">';
                    modalContent += '<div class="modal-header">';
                    modalContent +=
                        '<h5 class="modal-title" id="pdfModalLabel">CentralID Form</h5>';
                    modalContent +=
                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    modalContent += '</div>';
                    modalContent += '<div class="modal-body">';
                    modalContent +=
                        '<div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">';
                    modalContent += '<img src="/path/to/loader.gif" alt="Loader">';
                    modalContent += '</div>';
                    modalContent += '<iframe id="pdfIframe" src="' + response.pdf_url +
                        '" style="width: 100%; height: 80vh; display: none;"></iframe>';
                    modalContent += '</div>';
                    modalContent += '</div>';
                    modalContent += '</div>';
                    modalContent += '</div>';

                    // Append modal to the body and show it
                    $('body').append(modalContent);
                    $('#pdfModal').modal('show');

                    // Hide the loader overlay when the PDF is loaded
                    $('#pdfIframe').on('load', function() {
                        $('#pdfLoaderOverlay').hide();
                        $('#pdfIframe').show();
                    });

                    console.log('PDF URL received successfully');
                } else {
                    console.error('Invalid PDF response:', response);
                    alert('Error fetching PNGO report. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                alert('Error fetching PNGO report. Please try again.');
            },
            complete: function() {
                // Hide the loader overlay when the request is complete
                $('#loader-overlay').hide();
            }
        });
    });

    function isValidUrl(url) {
        // Check if the URL is valid based on your requirements
        return /^https?:\/\/.+/.test(url);
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get the 'from_date' and 'to_date' inputs
        const fromDateInput = document.getElementById("from_date");
        const toDateInput = document.getElementById("to_date");

        // Enable 'to_date' when 'from_date' is selected
        fromDateInput.addEventListener("change", function() {
            if (fromDateInput.value) {
                toDateInput.disabled = false;
            } else {
                toDateInput.disabled = true;
                toDateInput.value = ""; // Reset 'to_date' if 'from_date' is empty
            }
        });

        // Ensure 'to_date' is not earlier than 'from_date'
        toDateInput.addEventListener("change", function() {
            const fromDate = new Date(fromDateInput.value);
            const toDate = new Date(toDateInput.value);

            // If 'to_date' is earlier than 'from_date', set 'to_date' to 'from_date'
            if (toDate < fromDate) {
                alert("To date cannot be earlier than From date.");
                toDateInput.value = fromDateInput.value; // Set 'to_date' to 'from_date'
            }
        });
    });
</script>

<script>
    $(document).on("click", ".edit-link", function(event) {
        event.preventDefault();

        var editId = $(this).data("edit-id"); // Get the edit ID

        $.ajax({
            url: "/mne/edit-case",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                edit_id: editId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url; // Redirect to edit form
                }
            },
            error: function(xhr) {
                console.error("AJAX request failed", xhr);
            }
        });
    });

    $(document).on("click", ".file-link", function(event) {
        event.preventDefault();

        var fileId = $(this).data("file-id"); // Get the edit ID

        $.ajax({
            url: "/mne/edit-file",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                file_id: fileId
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.redirect_url; // Redirect to edit form
                }
            },
            error: function(xhr) {
                console.error("AJAX request failed", xhr);
            }
        });
    });
</script>

<script>
    $(document).on('change', '.verify-toggle', function() {
        const id = $(this).data('id');
        const checkbox = $(this); // Store reference to toggle

        if (this.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please see the form and attachment carefully before verifying.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('formal-case.verify') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verified!',
                                    text: 'Case has been verified successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Replace toggle with badge
                                const td = checkbox.closest('td');
                                td.html(
                                    '<span class="badge bg-success">Verified by DPO</span>'
                                );
                            } else {
                                Swal.fire('Error!', 'Verification failed.', 'error');
                                checkbox.prop('checked', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred during verification.',
                                'error');
                            checkbox.prop('checked', false);
                        }
                    });
                } else {
                    checkbox.prop('checked', false); // Uncheck if canceled
                }
            });
        }
    });


    $(document).on('change', '.verify-toggle-dpo', function() {
        const id = $(this).data('ids');
        const checkbox = $(this); // Store reference to toggle

        if (this.checked) {
            Swal.fire({
                title: 'Are you sure?',
                text: "Please see the form carefully before verifying.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, verify it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('formal-case.verifymneo') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Verified!',
                                    text: 'Case has been verified successfully.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                // Replace toggle with badge
                                const td = checkbox.closest('td');
                                td.html(
                                    '<span class="badge bg-primary">Verified by MNEO</span>'
                                );
                            } else {
                                Swal.fire('Error!', 'Verification failed.', 'error');
                                checkbox.prop('checked', false);
                            }
                        },
                        error: function() {
                            Swal.fire('Error!', 'An error occurred during verification.',
                                'error');
                            checkbox.prop('checked', false);
                        }
                    });
                } else {
                    checkbox.prop('checked', false); // Uncheck if canceled
                }
            });
        }
    });
</script>
@endpush