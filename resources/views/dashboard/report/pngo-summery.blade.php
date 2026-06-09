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
            background: #5d5c5cb3;
            z-index: 9999;
        }
        
        #loader {
            border: 16px solid #f3f3f3;
            border-top: 16px solid #c30f08;
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

        #pngoBarChart {
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
                        <h6 class="card-title">
                            <i class="fas fa-chalkboard-teacher mr-1"></i>
                            PNGO Wise Breakdown
                        </h6>
                        <button type="button" class="btn btn-success btn-sm" id="printButton">
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
                                        <canvas id="pngoBarChart"></canvas>
                                    </div>
                                </div>
                        
                                <!-- Table Section -->
                                <div class="col-12">
                                    <div class="table-responsive">
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
        </div>
    </div><!-- /.container-fluid -->
</section>

<form id="pdfPostForm" method="POST" action="{{ route('generate-pdf-chart', [], false) }}" target="pdfFrame" class="d-none">
    @csrf
    <input type="hidden" name="pdf_data" id="pdf_data">
    <input type="hidden" name="chart_image" id="chart_image">
    <input type="hidden" name="title" value="PNGO Wise Summery">
    <input type="hidden" name="orientation" value="P">
    <input type="hidden" name="fname" value="PNGO Wise Summery.pdf">
    <input type="hidden" name="inline" value="1">
</form>

<div class="modal fade modal-fullscreen" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">PNGO Wise Summery Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe name="pdfFrame" id="pdfFrame" style="width: 100%; height: 86vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script>
    $('#printButton').on('click', function(event) {
        event.preventDefault();
        var chartCanvas = document.getElementById('pngoBarChart');
        $('#pdf_data').val($('#reportDiv').html());
        $('#chart_image').val(chartCanvas.toDataURL('image/png'));

        var modal = new bootstrap.Modal(document.getElementById('pdfModal'));
        modal.show();
        document.getElementById('pdfFrame').src = 'about:blank';
        document.getElementById('pdfPostForm').submit();
    });
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
