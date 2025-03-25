@extends('dashboard.layouts.admin-layout')
@section('title', 'Dashboard')
@section('content')
<section>
    <div class="container-fluid">
        <!-- Key Metrics Section -->
        <div class="dashboard-stats">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="inner">
                            <h3>{{ $districtWise->sum('total') }}</h3>
                            <p>Total Cases</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i> <!-- Changed icon to fa-users -->
                        </div>
                    </div>
                </div>
        
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="inner">
                            <h3>{{ $districtWise->sum('male') }}</h3>
                            <p>Male</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-male"></i> <!-- Changed icon to fa-male -->
                        </div>
                    </div>
                </div>
        
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="inner">
                            <h3>{{ $districtWise->sum('female') }}</h3>
                            <p>Female</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-female"></i> <!-- Changed icon to fa-female -->
                        </div>
                    </div>
                </div>
        
                <div class="col-md-3 mb-3">
                    <div class="stat-box">
                        <div class="inner">
                            <h3>{{ $districtWise->sum('under_18') }}</h3>
                            <p>Under 18</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-child"></i> <!-- Changed icon to fa-child -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <!-- District-wise Data -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-bg-secondary">
                        <h5>District-wise Data Overview</h5>
                    </div>
                    <div class="card-body">
                        <!-- Bar Chart -->
                        <div class="chart-container">
                            <canvas id="districtBarChart"></canvas>
                        </div>
                        <!-- Summary Table -->
                        <div class="mt-4 table-responsive">
                            <h6>District Summary</h6>
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
            

            <!-- PNGO-wise Data -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-bg-secondary">
                        <h5>PNGO-wise Contributions</h5>
                    </div>
                    <div class="card-body">
                        <!-- Doughnut Chart -->
                        <div class="chart-container">
                            <canvas id="pngoBarChart"></canvas>
                        </div>
                        <!-- Details Table -->
                        <div class="mt-4">
                            <h6>PNGO Breakdown</h6>
                            <table class="table table-bordered table-striped">
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

        <!-- Progress Section -->
        {{-- <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Overall Project Progress</h5>
                    </div>
                    <div class="card-body">
                        <div class="progress-group">
                            <span class="progress-title">Post-release Counseling</span>
                            <span class="float-right"><b>80%</b></span>
                            <div class="progress">
                                <div class="progress-bar bg-success" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="progress-group mt-3">
                            <span class="progress-title">Follow-up Cases</span>
                            <span class="float-right"><b>60%</b></span>
                            <div class="progress">
                                <div class="progress-bar bg-warning" style="width: 60%"></div>
                            </div>
                        </div>
                        <div class="progress-group mt-3">
                            <span class="progress-title">Contact with Families</span>
                            <span class="float-right"><b>90%</b></span>
                            <div class="progress">
                                <div class="progress-bar bg-primary" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Charts Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Case Distribution (Pie Chart)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Interventions Over Time (Bar Chart)</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="barChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Activities</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Case ID</th>
                                    <th>Activity</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>CASE-101</td>
                                    <td>Filed Application</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>2024-11-24</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>CASE-102</td>
                                    <td>Follow-up Meeting</td>
                                    <td><span class="badge bg-warning">Pending</span></td>
                                    <td>2024-11-23</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>CASE-103</td>
                                    <td>Family Counseling</td>
                                    <td><span class="badge bg-info">Ongoing</span></td>
                                    <td>2024-11-22</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</section>

@push('scripts')
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
@endsection