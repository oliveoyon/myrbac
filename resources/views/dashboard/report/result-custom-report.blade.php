@extends('dashboard.layouts.admin-layout')

@section('title', 'Category Management')

@push('styles')
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        #loader-overlay {
   display: none;
   position: fixed;
   top: 0;
   left: 0;
   width: 100%;
   height: 100%;
   background: rgba(255, 255, 255, 0.7);
   z-index: 9999;
}

#loader {
   border: 16px solid #f3f3f3;
   border-top: 16px solidrgb(164, 213, 246);
   border-radius: 50%;
   width: 80px;
   height: 80px;
   margin: 15% auto;
   animation: spin 1s linear infinite;
}

.modal.modal-fullscreen .modal-dialog {
    width: 100vw;
    height: 100vh;
    margin: 0;
    padding: 0;
    max-width: none;
  }

  .modal.modal-fullscreen .modal-content {
    height: auto;
    height: 100vh;
    border-radius: 0;
    border: none;
  }

  .modal.modal-fullscreen .modal-body {
    overflow-y: auto;
  }
    </style>
@endpush

@section('content')

<!-- Content Wrapper. Contains page content -->
<section>
    <!-- Main content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline">
                    <header class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title d-flex align-items-center mb-0">
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Custom Report
                        </h5>
                        <button class="btn btn-success btn-sm" id="printButton">
                            <i class="fas fa-plus-square me-2"></i> Print Report
                        </button>
                    </header>
                    <div class="card-body table-responsive">
                        <div class="alert alert-danger" id="errorAlert" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>
                        <div id="reportDiv">
                            <table class="table table-bordered table-striped table-hover table-sm" id="class-table">
                            <thead>
                                <tr>
                                    <th style="padding: 8px 12px; text-align: left;">Interventions</th>
                                    <th style="padding: 8px 12px; text-align: right;">Male</th>
                                    <th style="padding: 8px 12px; text-align: right;">Female</th>
                                    <th style="padding: 8px 12px; text-align: right;">Transgender</th>
                                    <th style="padding: 8px 12px; text-align: right;">Under 18</th>
                                    <th style="padding: 8px 12px; text-align: right;">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($results as $result)
                                    <tr>
                                        <td style="padding: 8px 12px;">{{ $flattenedFields[$result->field] ?? 'N/A' }}</td>
                                        <td style="padding: 8px 12px; text-align: right;">{{ $result->adult_males }}</td>
                                        <td style="padding: 8px 12px; text-align: right;">{{ $result->adult_females }}</td>
                                        <td style="padding: 8px 12px; text-align: right;">{{ $result->adult_transgenders }}</td>
                                        <td style="padding: 8px 12px; text-align: right;">{{ $result->under_18 }}</td>
                                        <td style="padding: 8px 12px; text-align: right;">{{ $result->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>



@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
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
                title: 'Result of Interventions',
                orientation: 'P',
                fname: 'Result of Interventions',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.pdf_url && isValidUrl(response.pdf_url)) {
                    // Create a modal element using Bootstrap 5 modal structure
                    var modalContent =
                        '<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">';
                    modalContent +=
                        '<div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">';
                    modalContent += '<div class="modal-content">';
                    modalContent += '<div class="modal-header">';
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">Result of Interventions</h5>';
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

                    // Append modal to the body
                    $('body').append(modalContent);

                    // Show the modal using Bootstrap 5 JavaScript API
                    var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
                    modal.show();

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
</script>
@endpush
