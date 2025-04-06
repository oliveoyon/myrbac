@extends('dashboard.layouts.admin-layout')

@section('title', 'District Summery')

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

        .chart-wrapper {
            width: 100%;
            max-width: 100%;
            position: relative;
            padding: 20px;
            box-sizing: border-box;
        }

        #districtBarChart {
            width: 100% !important;
            height: auto !important;
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
                        <h4 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            District Wise Summery
                        </h4>
                        <button class="btn btn-success btn-sm" id="printButton">
                            <i class="fas fa-print mr-1"></i> Print Report
                        </button>
                    </div>
                    <div class="card-body table-responsive">
                        <div class="alert alert-danger" id="errorAlert" style="display: none;">
                            <ul id="errorList"></ul>
                        </div>
                        <div id="reportDiv" class="container-fluid">
                            <div class="row">
                                <!-- Chart Section -->
                                <div class="col-12 mb-4">
                                    <div class="chart-wrapper">
                                        <canvas id="districtBarChart"></canvas>
                                    </div>
                                </div>
                        
                                <!-- Table Section -->
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>District</th>
                                                    <th>Male</th>
                                                    <th>Female</th>
                                                    <th>Transgender</th>
                                                    <th>Under 18</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($districtWise as $row)
                                                    <tr>
                                                        <td>{{ $row['district_name'] }}</td>
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
        var chartCanvas = document.getElementById('districtBarChart'); // Your chart canvas id
        var chartImage = chartCanvas.toDataURL('image/png'); // Convert canvas to image data URL

        // Show the loader overlay
        $('#loader-overlay').show();

        $.ajax({
            url: '/mne/generate-pdf-chart',  // Your route to handle the POST request
            method: 'POST',
            data: {
                pdf_data: data,  // The HTML content for the report
                chart_image: chartImage,  // The image data URL for the chart
                title: 'District Wise Summery',
                orientation: 'P',
                fname: 'District Wise Summery.pdf',
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
                    modalContent += '<h5 class="modal-title" id="pdfModalLabel">District Wise Summery Report</h5>';
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
    const districtData = @json($districtWise);  // Pass the data to JS

    // Prepare labels and datasets from the district data
    const districtNames = districtData.map(item => item.district_name);
    const maleData = districtData.map(item => item.male);
    const femaleData = districtData.map(item => item.female);
    const transgenderData = districtData.map(item => item.transgender);
    const under18Data = districtData.map(item => item.under_18);
    const totalData = districtData.map(item => item.total); // Total cases per district

    const districtBarCtx = document.getElementById('districtBarChart').getContext('2d');

    new Chart(districtBarCtx, {
        type: 'bar',
        data: {
            labels: districtNames, // District names (e.g., 'Khulna', 'Barishal')
            datasets: [
                {
                    label: 'Male',
                    data: maleData, // Male count per district
                    backgroundColor: '#007bff', // Blue color
                    borderRadius: 5, // Rounded corners
                    borderWidth: 1,
                    borderColor: '#0056b3', // Dark blue border for contrast
                },
                {
                    label: 'Female',
                    data: femaleData, // Female count per district
                    backgroundColor: '#ff66b2', // Pink color
                    borderRadius: 5, // Rounded corners
                    borderWidth: 1,
                    borderColor: '#e60073', // Dark pink border for contrast
                },
                {
                    label: 'Transgender',
                    data: transgenderData, // Transgender count per district
                    backgroundColor: '#ff9900', // Orange color
                    borderRadius: 5, // Rounded corners
                    borderWidth: 1,
                    borderColor: '#cc7a00', // Dark orange border for contrast
                },
                {
                    label: 'Under 18',
                    data: under18Data, // Under 18 count per district
                    backgroundColor: '#ffc107', // Yellow color
                    borderRadius: 5, // Rounded corners
                    borderWidth: 1,
                    borderColor: '#b88f02', // Dark yellow border for contrast
                },
                {
                    label: 'Total',
                    data: totalData, // Total count per district
                    backgroundColor: '#28a745', // Green color
                    borderWidth: 2,
                    borderColor: '#155724', // Dark green border for total
                    borderRadius: 5, // Rounded corners
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top', // Position of the legend
                    labels: {
                        boxWidth: 20, // Size of legend boxes
                        padding: 15, // Padding between legend items
                    },
                },
            },
            scales: {
                x: {
                    beginAtZero: true, // Ensure the x-axis starts at 0
                    grid: {
                        display: true, // Show gridlines on the x-axis
                        color: 'rgba(0, 0, 0, 0.1)', // Light gray gridlines
                    },
                    ticks: {
                        padding: 20, // Add padding between x-axis and bars
                    },
                },
                y: {
                    beginAtZero: true, // Ensure the y-axis starts at 0
                    grid: {
                        display: true, // Show gridlines for better visibility of values
                        color: 'rgba(0, 0, 0, 0.1)', // Light gray gridlines for Y axis
                        lineWidth: 1, // Grid lines thickness
                    },
                    ticks: {
                        padding: 15, // Add padding between y-axis and bars
                    },
                },
            },
            // Adjusting the space between each district
            categoryPercentage: 0.7,  // Keep space between categories (districts)
            barPercentage: 1,        // Set bar width to fill the available space
            responsiveAnimationDuration: 500,
            animation: {
                duration: 1000,
                easing: 'easeInOutQuart'
            },
            layout: {
                padding: {
                    left: 10,
                    right: 10,
                    top: 10,
                    bottom: 10
                }
            },
        },
    });
</script>
@endpush
