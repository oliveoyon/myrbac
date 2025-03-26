@extends('dashboard.layouts.admin-layout')

@section('title', 'PNGO Summery')

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
        border-top: 16px solid #3498db;
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
                <div class="card-header text-bg-dark d-flex justify-content-between align-items-center">
                        <h6 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            PNGO Wise Breakdown
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
                            <div class="col-md-12">
                                <div class="chart-container">
                                    <canvas id="pngoBarChart"></canvas>
                                </div>
                                <!-- Summary Table -->
                                <div class="mt-4 table-responsive">
                                <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>PNGO</th>
                                                <th>Male</th>
                                                <th>Female</th>
                                                <th>Transgender</th>
                                                <th>Under 18</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pngoWise as $row)
                                                <tr>
                                                    <td>{{ $row['pngo_name'] }}</td>
                                                    <td>{{ $row['male'] }}</td>
                                                    <td>{{ $row['female'] }}</td>
                                                    <td>{{ $row['transgender'] }}</td>
                                                    <td>{{ $row['under_18'] }}</td>
                                                    <td>{{ $row['total'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
        var data = $('#reportDiv').html(); // Get the HTML content for the report

        // Capture the chart image
        var chartCanvas = document.getElementById('pngoBarChart'); // Your chart canvas id
        var chartImage = chartCanvas.toDataURL('image/png'); // Convert canvas to image data URL

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/mne/generate-pdf-chart',  // Your route to handle the POST request
            method: 'POST',
            data: {
                pdf_data: data,  // The HTML content for the report
                chart_image: chartImage,  // The image data URL for the chart
                title: 'PNGO Wise Summery',
                orientation: 'P',
                fname: 'PNGO Wise Summery.pdf',
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for security
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
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">PNGO Wise Summery Report</h5>';
                    modalContent += 
                        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                    modalContent += '</div>';
                    modalContent += '<div class="modal-body">';
                    modalContent += 
                        '<div id="pdfLoaderOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); display: flex; justify-content: center; align-items: center;">';
                    modalContent += '<img src="/path/to/loader.gif" alt="Loader">';
                    modalContent += '</div>';
                    modalContent += 
                        '<iframe id="pdfIframe" src="' + response.pdf_url + '" style="width: 100%; height: 80vh; display: none;"></iframe>';
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


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const pngoData = @json($pngoWise); // Passing PHP array to JavaScript

        if (!pngoData || pngoData.length === 0) {
            console.warn("No data available for the chart.");
            return;
        }

        // Extract labels and data
        const pngoNames = pngoData.map(item => item.pngo_name);
        const maleData = pngoData.map(item => item.male);
        const femaleData = pngoData.map(item => item.female);
        const transgenderData = pngoData.map(item => item.transgender);
        const under18Data = pngoData.map(item => item.under_18);

        const ctx = document.getElementById("pngoBarChart").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: pngoNames,
                datasets: [
                    {
                        label: "Male",
                        data: maleData,
                        backgroundColor: "#007bff",
                        barThickness: 40,
                    },
                    {
                        label: "Female",
                        data: femaleData,
                        backgroundColor: "#ff66b2",
                        barThickness: 40,
                    },
                    {
                        label: "Transgender",
                        data: transgenderData,
                        backgroundColor: "#ff9900",
                        barThickness: 40,
                    },
                    {
                        label: "Under 18",
                        data: under18Data,
                        backgroundColor: "#ffc107",
                        barThickness: 40,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: "top",
                    },
                    tooltip: {
                        enabled: true
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        grid: {
                            color: "rgba(0, 0, 0, 0.1)"
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
