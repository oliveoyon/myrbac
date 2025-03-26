@extends('dashboard.layouts.admin-layout')
@section('title', 'Case List')
@push('styles')

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
                            <h4 class="card-title">Case Detail</h4>
                            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#caseCardBody">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <div class="collapse show" id="caseCardBody">
                            <div class="card-body">
                                <div class="row align-items-end">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm district_id" name="district_id" id="district_id">
                                                <option value="">All Districts</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control form-control-sm pngo_id" name="pngo_id" id="pngo_id">
                                                <option value="">All PNGO</option>
                                                @foreach ($pngos as $pngo)
                                                    <option value="{{ $pngo->id }}">{{ $pngo->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
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
                        <h4 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            Case List
                        </h4>
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
                                        <th>Case</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Date of Interview</th>
                                        <th>District</th>
                                        <th>PNGO</th>
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
<div class="modal fade" id="pngoModal" tabindex="-1" role="dialog" aria-labelledby="pngoModalLabel" aria-hidden="true">
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

                    if (response && response.cases && Array.isArray(response.cases)) {
                        let serialNumber = 1;
                        response.cases.forEach(function(caseData) {
                            var row = `<tr>
                                <td>${serialNumber}</td>
                                <td>${caseData.central_id || 'N/A'}</td>
                                <td>${caseData.full_name || 'N/A'}</td>
                                <td>${caseData.phone_number || 'N/A'}</td>
                                <td>${caseData.legal_representation_date ? new Date(caseData.legal_representation_date).toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' }) : 'N/A'}</td>
                                <td>${caseData.district.name || 'N/A'}</td>
                                <td><a href="javascript:void(0);" class="pngo-link" data-pngo-id="${caseData.id}">${caseData.pngo.name || 'N/A'}</a></td>
                            </tr>`;

                            tableBody.append(row);
                            serialNumber++;
                        });

                        $('.contents').removeClass('d-none'); // Show results section
                    } else {
                        console.error("Invalid response format:", response);
                        $('#errorAlert').show().text('No cases found.');
                    }
                },

                error: function(error) {
                    // Handle errors if needed
                    console.log(error);
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
        var data = $('#reportDiv').html();

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/mne/generate-pdf',
            method: 'POST',
            data: {
                pdf_data: data,
                title: 'Case List',
                orientation: 'L',
                fname: 'Case List.pdf',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    // Create a modal element
                    var modalContent =
                        '<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                    modalContent +=
                        '<div class="modal-dialog modal-dialog-centered modal-fullscreen">'; // Changed to Bootstrap 5 class
                    modalContent += '<div class="modal-content">';
                    modalContent += '<div class="modal-header">';
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">Generated Report</h5>';
                    modalContent +=
                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>'; // Updated close button
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

                    console.log('PDF generated successfully');
                } else {
                    console.error('Invalid PDF response:', response);
                    alert('Error generating PDF. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX request failed:', error);
                alert('Error generating PDF. Please try again.');
            },
            complete: function() {
                // Hide the loader overlay when the request is complete
                $('#loader-overlay').hide();
            }
        });
    });

    function isValidUrl(url) {
        // Implement a function to check if the URL is valid based on your requirements
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
                modalContent += '<h5 class="modal-title" id="pdfModalLabel">CentralID Form</h5>';
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
@endpush