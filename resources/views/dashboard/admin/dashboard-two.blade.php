@extends('dashboard.layouts.admin-layout')

@section('title', 'M&E Dashboard')

@push('styles')
<style>
    .monitor-dashboard {
        display: grid;
        gap: 16px;
        color: #17202a;
        min-width: 0;
        max-width: 100%;
        overflow-x: hidden;
    }

    .monitor-hero {
        display: grid;
        grid-template-columns: minmax(0, 1fr) auto;
        gap: 16px;
        align-items: center;
        padding: 20px 22px;
        border: 1px solid #d8e5df;
        border-radius: 8px;
        background: linear-gradient(135deg, #ffffff 0%, #f7fbf8 48%, #f7f8ff 100%);
        box-shadow: 0 8px 24px rgba(16, 24, 40, .06);
        position: relative;
        overflow: hidden;
    }

    .monitor-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: linear-gradient(180deg, #c30f08, #2f7d62, #4f7dd9);
    }

    .monitor-hero > * {
        position: relative;
        z-index: 1;
    }

    .monitor-kicker {
        display: inline-flex;
        margin-bottom: 7px;
        padding: 3px 8px;
        border-radius: 999px;
        background: #fff3f2;
        color: #9d0c06;
        font-size: 12px;
        font-weight: 800;
    }

    .monitor-hero h1 {
        margin: 0 0 5px;
        color: #111827;
        font-size: 24px;
        font-weight: 850;
    }

    .monitor-hero p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .monitor-date {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 7px;
        background: #eef7f1;
        color: #285d49;
        font-weight: 800;
        white-space: nowrap;
    }

    .monitor-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
    }

    .monitor-kpi {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        min-height: 104px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
        position: relative;
    }

    .monitor-kpi::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 4px;
        background: var(--accent, #2f7d62);
    }

    .monitor-kpi span {
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .monitor-kpi strong {
        display: block;
        margin-top: 7px;
        color: #111827;
        font-size: 30px;
        line-height: 1;
        font-weight: 850;
    }

    .monitor-kpi-icon {
        display: inline-flex;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        color: var(--accent-dark, #17643a);
        background: var(--accent-soft, #e8f5ee);
        flex: 0 0 auto;
    }

    .monitor-kpi.formal { --accent: #2f7d62; --accent-soft: #e8f5ee; --accent-dark: #17643a; }
    .monitor-kpi.lsid { --accent: #2aa6a1; --accent-soft: #e8f8f7; --accent-dark: #14726e; }
    .monitor-kpi.child { --accent: #d9a441; --accent-soft: #fff7e5; --accent-dark: #9a6e19; }
    .monitor-kpi.disability { --accent: #6f7bc8; --accent-soft: #f0f2ff; --accent-dark: #48549f; }
    .monitor-kpi.todo { --accent: #c30f08; --accent-soft: #fff3f2; --accent-dark: #9d0c06; }

    .monitor-grid {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 14px;
    }

    .monitor-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
        min-width: 0;
    }

    .monitor-panel.span-4 { grid-column: span 4; }
    .monitor-panel.span-5 { grid-column: span 5; }
    .monitor-panel.span-6 { grid-column: span 6; }
    .monitor-panel.span-7 { grid-column: span 7; }
    .monitor-panel.span-8 { grid-column: span 8; }
    .monitor-panel.span-12 { grid-column: span 12; }

    .monitor-panel-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .monitor-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 850;
    }

    .monitor-panel-header small {
        color: #64748b;
        font-size: 12px;
    }

    .monitor-panel-body {
        padding: 14px 16px;
        min-width: 0;
    }

    .monitor-chart {
        height: 300px;
        min-width: 0;
    }

    .monitor-chart.compact {
        height: 255px;
    }

    .monitor-table {
        margin: 0;
        font-size: 13px;
    }

    .monitor-table th {
        color: #374151;
        background: #f8fafc;
        white-space: nowrap;
    }

    .monitor-table td {
        vertical-align: middle;
    }

    .monitor-rank {
        display: inline-flex;
        width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        margin-right: 6px;
        border-radius: 999px;
        background: #fff3f2;
        color: #9d0c06;
        font-size: 12px;
        font-weight: 850;
    }

    .monitor-note {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
    }

    .monitor-insight-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 10px;
    }

    .monitor-insight {
        padding: 11px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .monitor-insight span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .monitor-insight strong {
        display: block;
        margin-top: 4px;
        color: #111827;
        font-size: 18px;
        font-weight: 850;
    }

    @media (max-width: 1200px) {
        .monitor-panel.span-4,
        .monitor-panel.span-5,
        .monitor-panel.span-6,
        .monitor-panel.span-7,
        .monitor-panel.span-8 {
            grid-column: span 6;
        }
    }

    @media (max-width: 768px) {
        .monitor-hero {
            grid-template-columns: 1fr;
            padding: 17px 16px;
        }

        .monitor-date {
            justify-content: center;
            width: 100%;
        }

        .monitor-grid {
            grid-template-columns: 1fr;
        }

        .monitor-panel,
        .monitor-panel.span-4,
        .monitor-panel.span-5,
        .monitor-panel.span-6,
        .monitor-panel.span-7,
        .monitor-panel.span-8,
        .monitor-panel.span-12 {
            grid-column: 1;
        }

        .monitor-chart,
        .monitor-chart.compact {
            height: 260px;
        }
    }

    @media (max-width: 480px) {
        .monitor-hero h1 {
            font-size: 22px;
        }

        .monitor-kpi strong {
            font-size: 26px;
        }

        .monitor-panel-header {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<section class="monitor-dashboard">
    <div class="monitor-hero">
        <div>
            <span class="monitor-kicker">Dashboard 2 | Review Draft</span>
            <h1>Monitoring & Evaluation Dashboard</h1>
            <p>Programme performance by institution, district, partner, service type, and verification status.</p>
        </div>
        <span class="monitor-date">{{ now()->format('j M, Y') }}</span>
    </div>

    <div class="monitor-kpi-grid">
        <div class="monitor-kpi formal">
            <div>
                <span>Verified Caseload</span>
                <strong>{{ number_format($dashboardData['kpis']['formal_total']) }}</strong>
            </div>
            <div class="monitor-kpi-icon"><i class="fas fa-scale-balanced"></i></div>
        </div>
        <div class="monitor-kpi lsid">
            <div>
                <span>LSID Register</span>
                <strong>{{ number_format($dashboardData['kpis']['lsid_total']) }}</strong>
            </div>
            <div class="monitor-kpi-icon"><i class="fas fa-circle-info"></i></div>
        </div>
        <div class="monitor-kpi child">
            <div>
                <span>Under 18</span>
                <strong>{{ number_format($dashboardData['kpis']['under_18']) }}</strong>
            </div>
            <div class="monitor-kpi-icon"><i class="fas fa-child"></i></div>
        </div>
        <div class="monitor-kpi disability">
            <div>
                <span>Disability</span>
                <strong>{{ number_format($dashboardData['kpis']['disability']) }}</strong>
            </div>
            <div class="monitor-kpi-icon"><i class="fas fa-universal-access"></i></div>
        </div>
        <div class="monitor-kpi todo">
            <div>
                <span>Today Follow-up</span>
                <strong>{{ number_format($dashboardData['kpis']['today_tasks']) }}</strong>
            </div>
            <div class="monitor-kpi-icon"><i class="fas fa-list-check"></i></div>
        </div>
    </div>

    <div class="monitor-grid">
        <div class="monitor-panel span-8">
            <div class="monitor-panel-header">
                <div>
                    <h2>Monthly Service Trend</h2>
                    <small>Formal intervention and LSID service volume</small>
                </div>
                <span class="monitor-note"><i class="fas fa-chart-line"></i> Monthly</span>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Institution Coverage</h2>
                    <small>Court, Police Station, and Prison caseload</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="institutionChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Sex Disaggregation</h2>
                    <small>Verified formal intervention beneficiaries</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="sexChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Verification Status</h2>
                    <small>Submitted, DPO verified, and M&EO verified records</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>District Caseload</h2>
                    <small>Highest verified intervention volume</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="districtChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-12">
            <div class="monitor-panel-header">
                <div>
                    <h2>M&E Summary Indicators</h2>
                    <small>Quick programme indicators for review meetings</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-insight-grid">
                    <div class="monitor-insight">
                        <span>Formal to LSID Ratio</span>
                        <strong>{{ number_format($dashboardData['kpis']['formal_total']) }} : {{ number_format($dashboardData['kpis']['lsid_total']) }}</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Child Coverage</span>
                        <strong>{{ $dashboardData['kpis']['formal_total'] ? number_format(($dashboardData['kpis']['under_18'] / $dashboardData['kpis']['formal_total']) * 100, 1) : 0 }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Disability Coverage</span>
                        <strong>{{ $dashboardData['kpis']['formal_total'] ? number_format(($dashboardData['kpis']['disability'] / $dashboardData['kpis']['formal_total']) * 100, 1) : 0 }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Current Month Formal</span>
                        <strong>{{ number_format(collect($dashboardData['trend']['formal'])->last() ?? 0) }}</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Verification Coverage</span>
                        <strong>{{ number_format($dashboardData['kpis']['verification_rate'], 1) }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>M&EO Completion</span>
                        <strong>{{ number_format($dashboardData['kpis']['mneo_completion_rate'], 1) }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Data Quality Score</span>
                        <strong>{{ number_format($dashboardData['kpis']['data_quality_score'], 1) }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Pending Verification</span>
                        <strong>{{ number_format($dashboardData['kpis']['pending_dpo'] + $dashboardData['kpis']['pending_mneo']) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-12">
            <div class="monitor-panel-header">
                <div>
                    <h2>Data Quality & Verification Review</h2>
                    <small>Operational indicators for follow-up, validation, and data cleaning</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-insight-grid">
                    <div class="monitor-insight">
                        <span>Completeness Score</span>
                        <strong>{{ number_format($dataQuality['score'], 1) }}%</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>DPO Pending</span>
                        <strong>{{ number_format($dashboardData['kpis']['pending_dpo']) }}</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>M&EO Pending</span>
                        <strong>{{ number_format($dashboardData['kpis']['pending_mneo']) }}</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Overdue Follow-up</span>
                        <strong>{{ number_format($followUpWorkload['overdue']) }}</strong>
                    </div>
                    <div class="monitor-insight">
                        <span>Duplicate Phone Groups</span>
                        <strong>{{ number_format($duplicateRisk['phone_groups']) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Completeness Score</h2>
                    <small>Core fields filled among verified intervention records</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="qualityScoreChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Missing Field Pattern</h2>
                    <small>Highest missing core fields requiring review</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="missingFieldsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Follow-up Workload</h2>
                    <small>Open tasks based on due dates</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="followupWorkloadChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-8">
            <div class="monitor-panel-header">
                <div>
                    <h2>Pending Verification by District</h2>
                    <small>DPO pending and M&EO pending workload</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="pendingVerificationChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Duplicate Risk</h2>
                    <small>Records grouped by repeated mobile number</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="duplicateRiskChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-7">
            <div class="monitor-panel-header">
                <div>
                    <h2>Institution Trend</h2>
                    <small>Monthly caseload by Court, Police Station, and Prison</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="institutionTrendChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-5">
            <div class="monitor-panel-header">
                <div>
                    <h2>Service Volume Mix</h2>
                    <small>Formal intervention and LSID register volume</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="serviceBalanceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Age Disaggregation</h2>
                    <small>Verified formal intervention beneficiaries</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="ageChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Education Profile</h2>
                    <small>Recorded education levels among verified interventions</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="educationChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Income Profile</h2>
                    <small>Monthly income bands among verified interventions</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>Disability Coverage</h2>
                    <small>Disability status among verified formal interventions</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="disabilityChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-4">
            <div class="monitor-panel-header">
                <div>
                    <h2>LSID Service Categories</h2>
                    <small>Highest recorded information and service categories</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart compact">
                    <canvas id="lsidServiceChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-12">
            <div class="monitor-panel-header">
                <div>
                    <h2>Intervention Action Volume</h2>
                    <small>Action counts can overlap when one case receives multiple services</small>
                </div>
            </div>
            <div class="monitor-panel-body">
                <div class="monitor-chart">
                    <canvas id="interventionSignalChart"></canvas>
                </div>
            </div>
        </div>

        <div class="monitor-panel span-6">
            <div class="monitor-panel-header">
                <div>
                    <h2>District Performance Table</h2>
                    <small>Verified intervention volume within permitted scope</small>
                </div>
            </div>
            <div class="monitor-panel-body table-responsive">
                <table class="table table-bordered table-striped table-sm monitor-table">
                    <thead>
                        <tr>
                            <th>District</th>
                            <th>Total</th>
                            <th>Under 18</th>
                            <th>Disability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topDistricts as $row)
                            <tr>
                                <td><span class="monitor-rank">{{ $loop->iteration }}</span>{{ $row['district_name'] }}</td>
                                <td><strong>{{ number_format($row['total']) }}</strong></td>
                                <td>{{ number_format($row['under_18']) }}</td>
                                <td>{{ number_format($row['disability']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="monitor-panel span-6">
            <div class="monitor-panel-header">
                <div>
                    <h2>PNGO Performance Table</h2>
                    <small>Same-named PNGOs are merged in this view</small>
                </div>
            </div>
            <div class="monitor-panel-body table-responsive">
                <table class="table table-bordered table-striped table-sm monitor-table">
                    <thead>
                        <tr>
                            <th>PNGO</th>
                            <th>Total</th>
                            <th>Female</th>
                            <th>Disability</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topPngos as $row)
                            <tr>
                                <td><span class="monitor-rank">{{ $loop->iteration }}</span>{{ $row['pngo_name'] }}</td>
                                <td><strong>{{ number_format($row['total']) }}</strong></td>
                                <td>{{ number_format($row['female']) }}</td>
                                <td>{{ number_format($row['disability']) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No data available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const monitorData = @json($dashboardData);

    const monitorPalette = {
        green: '#2f7d62',
        teal: '#2aa6a1',
        red: '#c30f08',
        blue: '#4f7dd9',
        gold: '#d9a441',
        mauve: '#b86fa0',
        slate: '#7c8fb0',
        violet: '#6f7bc8',
    };

    function makeChart(canvasId, config) {
        const canvas = document.getElementById(canvasId);
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        new Chart(canvas.getContext('2d'), config);
    }

    makeChart('trendChart', {
        type: 'line',
        data: {
            labels: monitorData.trend.labels,
            datasets: [
                {
                    label: 'Formal Intervention',
                    data: monitorData.trend.formal,
                    borderColor: monitorPalette.green,
                    backgroundColor: 'rgba(47, 125, 98, .12)',
                    tension: .35,
                    fill: true,
                    pointRadius: 3,
                },
                {
                    label: 'LSID Register',
                    data: monitorData.trend.lsid,
                    borderColor: monitorPalette.red,
                    backgroundColor: 'rgba(195, 15, 8, .08)',
                    tension: .35,
                    fill: true,
                    pointRadius: 3,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('institutionChart', {
        type: 'doughnut',
        data: {
            labels: monitorData.institution.labels,
            datasets: [{
                data: monitorData.institution.values,
                backgroundColor: [monitorPalette.green, monitorPalette.blue, monitorPalette.gold],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '66%',
            plugins: { legend: { position: 'bottom' } },
        },
    });

    makeChart('sexChart', {
        type: 'pie',
        data: {
            labels: monitorData.sex.labels,
            datasets: [{
                data: monitorData.sex.values,
                backgroundColor: [monitorPalette.blue, monitorPalette.mauve, monitorPalette.slate],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
        },
    });

    makeChart('statusChart', {
        type: 'bar',
        data: {
            labels: monitorData.status.labels,
            datasets: [{
                label: 'Cases',
                data: monitorData.status.values,
                backgroundColor: [monitorPalette.gold, monitorPalette.green, monitorPalette.violet],
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('districtChart', {
        type: 'bar',
        data: {
            labels: monitorData.districts.labels,
            datasets: [{
                label: 'Verified Caseload',
                data: monitorData.districts.values,
                backgroundColor: monitorPalette.green,
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } },
            },
        },
    });

    makeChart('qualityScoreChart', {
        type: 'doughnut',
        data: {
            labels: ['Complete', 'Needs Review'],
            datasets: [{
                data: [monitorData.data_quality.score, Math.max(100 - monitorData.data_quality.score, 0)],
                backgroundColor: [monitorPalette.green, '#dbe3ea'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: { legend: { position: 'bottom' } },
        },
    });

    makeChart('missingFieldsChart', {
        type: 'bar',
        data: {
            labels: monitorData.data_quality.labels,
            datasets: [{
                label: 'Missing Records',
                data: monitorData.data_quality.values,
                backgroundColor: monitorPalette.red,
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } },
            },
        },
    });

    makeChart('followupWorkloadChart', {
        type: 'bar',
        data: {
            labels: monitorData.followups.labels,
            datasets: [{
                label: 'Open Follow-up',
                data: monitorData.followups.values,
                backgroundColor: [monitorPalette.red, monitorPalette.gold, monitorPalette.green],
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('pendingVerificationChart', {
        type: 'bar',
        data: {
            labels: monitorData.pending_verification.labels,
            datasets: [
                {
                    label: 'DPO Pending',
                    data: monitorData.pending_verification.submitted,
                    backgroundColor: monitorPalette.gold,
                    borderRadius: 5,
                },
                {
                    label: 'M&EO Pending',
                    data: monitorData.pending_verification.dpo_verified,
                    backgroundColor: monitorPalette.violet,
                    borderRadius: 5,
                },
            ],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
                y: { stacked: true, grid: { display: false } },
            },
        },
    });

    makeChart('duplicateRiskChart', {
        type: 'polarArea',
        data: {
            labels: monitorData.duplicate_risk.labels,
            datasets: [{
                data: monitorData.duplicate_risk.values,
                backgroundColor: ['rgba(195, 15, 8, .78)', 'rgba(217, 164, 65, .78)'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                r: { ticks: { precision: 0 } },
            },
        },
    });

    makeChart('institutionTrendChart', {
        type: 'bar',
        data: {
            labels: monitorData.trend.labels,
            datasets: [
                {
                    label: 'Court',
                    data: monitorData.trend.court,
                    backgroundColor: monitorPalette.green,
                    borderRadius: 5,
                },
                {
                    label: 'Police Station',
                    data: monitorData.trend.police_station,
                    backgroundColor: monitorPalette.blue,
                    borderRadius: 5,
                },
                {
                    label: 'Prison',
                    data: monitorData.trend.prison,
                    backgroundColor: monitorPalette.gold,
                    borderRadius: 5,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('serviceBalanceChart', {
        type: 'polarArea',
        data: {
            labels: monitorData.service_balance.labels,
            datasets: [{
                data: monitorData.service_balance.values,
                backgroundColor: ['rgba(47, 125, 98, .82)', 'rgba(42, 166, 161, .82)'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: {
                r: { ticks: { precision: 0 } },
            },
        },
    });

    makeChart('ageChart', {
        type: 'bar',
        data: {
            labels: monitorData.age.labels,
            datasets: [{
                label: 'Cases',
                data: monitorData.age.values,
                backgroundColor: [monitorPalette.gold, monitorPalette.teal, monitorPalette.green, monitorPalette.blue, monitorPalette.violet, monitorPalette.slate],
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('educationChart', {
        type: 'bar',
        data: {
            labels: monitorData.education.labels,
            datasets: [{
                label: 'Cases',
                data: monitorData.education.values,
                backgroundColor: monitorPalette.teal,
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } },
            },
        },
    });

    makeChart('incomeChart', {
        type: 'bar',
        data: {
            labels: monitorData.income.labels,
            datasets: [{
                label: 'Cases',
                data: monitorData.income.values,
                backgroundColor: [monitorPalette.slate, monitorPalette.gold, monitorPalette.green, monitorPalette.blue, monitorPalette.violet],
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 } },
            },
        },
    });

    makeChart('disabilityChart', {
        type: 'doughnut',
        data: {
            labels: monitorData.disability_share.labels,
            datasets: [{
                data: monitorData.disability_share.values,
                backgroundColor: [monitorPalette.violet, '#dbe3ea'],
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: { legend: { position: 'bottom' } },
        },
    });

    makeChart('lsidServiceChart', {
        type: 'bar',
        data: {
            labels: monitorData.lsid_services.labels,
            datasets: [{
                label: 'Services',
                data: monitorData.lsid_services.values,
                backgroundColor: monitorPalette.teal,
                borderRadius: 6,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } },
            },
        },
    });

    makeChart('interventionSignalChart', {
        type: 'bar',
        data: {
            labels: monitorData.intervention_signals.labels,
            datasets: [{
                label: 'Action Count',
                data: monitorData.intervention_signals.values,
                backgroundColor: [
                    monitorPalette.green,
                    monitorPalette.teal,
                    monitorPalette.blue,
                    monitorPalette.gold,
                    monitorPalette.mauve,
                    monitorPalette.violet,
                    monitorPalette.slate,
                    monitorPalette.red,
                ],
                borderRadius: 7,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 } },
                y: { grid: { display: false } },
            },
        },
    });
</script>
@endpush
