@extends('dashboard.layouts.admin-layout')

@section('title', 'Dashboard')

@push('styles')
<style>
    .smart-dashboard {
        display: grid;
        gap: 16px;
        color: #17202a;
    }

    .dash-hero {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        padding: 20px 22px;
        border: 1px solid #d8e5df;
        border-radius: 8px;
        background: linear-gradient(135deg, #f7fbf8 0%, #ffffff 52%, #f6f8ff 100%);
        box-shadow: 0 8px 24px rgba(16, 24, 40, .06);
        position: relative;
        overflow: hidden;
    }

    .dash-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 5px;
        background: linear-gradient(180deg, #2f7d62, #6b8fd6, #d9a441);
    }

    .dash-hero > * {
        position: relative;
        z-index: 1;
    }

    .dash-hero h1 {
        margin: 0 0 5px;
        color: #111827;
        font-size: 24px;
        font-weight: 800;
    }

    .dash-hero p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .dash-kicker {
        display: inline-flex;
        margin-bottom: 7px;
        padding: 3px 8px;
        border-radius: 999px;
        background: #e8f5ee;
        color: #17643a;
        font-size: 12px;
        font-weight: 800;
    }

    .dash-date-pill {
        display: inline-flex;
        align-items: center;
        padding: 7px 11px;
        border-radius: 6px;
        background: #eef7f1;
        color: #285d49;
        font-weight: 700;
        white-space: nowrap;
    }

    .dash-quick-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 14px;
    }

    .dash-quick-actions .btn {
        border-radius: 7px;
        font-weight: 700;
    }

    .dash-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
    }

    .dash-stat-card {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        min-height: 104px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        position: relative;
        overflow: hidden;
    }

    .dash-stat-card::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 4px;
        background: var(--accent, #78a891);
    }

    .dash-stat-card span {
        color: #64748b;
        font-size: 13px;
        font-weight: 700;
    }

    .dash-stat-card strong {
        display: block;
        margin-top: 6px;
        color: #111827;
        font-size: 30px;
        line-height: 1;
        font-weight: 850;
    }

    .dash-stat-icon {
        display: inline-flex;
        width: 38px;
        height: 38px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: var(--accent-soft, #f1f5f9);
        color: var(--accent-dark, #356854);
        flex: 0 0 auto;
    }

    .dash-stat-card.total {
        --accent: #2f7d62;
        --accent-soft: #e8f5ee;
        --accent-dark: #17643a;
    }

    .dash-stat-card.male {
        --accent: #4f7dd9;
        --accent-soft: #edf3ff;
        --accent-dark: #315cae;
    }

    .dash-stat-card.female {
        --accent: #b86fa0;
        --accent-soft: #fbf0f7;
        --accent-dark: #8b4c77;
    }

    .dash-stat-card.child {
        --accent: #d9a441;
        --accent-soft: #fff7e5;
        --accent-dark: #9a6e19;
    }

    .dash-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .dash-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .dash-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 800;
    }

    .dash-panel-header small {
        color: #64748b;
    }

    .dash-panel-body {
        padding: 14px 16px;
    }

    .dash-calendar-row {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 8px;
    }

    .dash-day {
        display: block;
        min-height: 76px;
        padding: 9px;
        border: 1px solid #dde7e1;
        border-radius: 8px;
        color: #1f2937;
        text-decoration: none;
        background: #fff;
        transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease;
    }

    .dash-day:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 16px rgba(16, 24, 40, .06);
    }

    .dash-day.has-task {
        background: #f1faf5;
        border-color: #a9c9b9;
    }

    .dash-day small {
        display: block;
        color: #64748b;
        font-weight: 800;
    }

    .dash-day-count {
        display: inline-flex;
        margin-top: 8px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #e8f5ee;
        color: #17643a;
        font-size: 12px;
        font-weight: 800;
    }

    .dash-table {
        margin: 0;
        font-size: 13px;
    }

    .dash-table th {
        white-space: nowrap;
        color: #374151;
        background: #f8fafc;
    }

    .dash-table td {
        vertical-align: middle;
    }

    .dash-empty {
        padding: 16px;
        color: #64748b;
        text-align: center;
        border: 1px dashed #d7dee3;
        border-radius: 8px;
        background: #fbfcfd;
    }

    .dash-rank {
        display: inline-flex;
        width: 22px;
        height: 22px;
        align-items: center;
        justify-content: center;
        border-radius: 999px;
        background: #eef2ff;
        color: #315cae;
        font-size: 12px;
        font-weight: 800;
    }

    @media (max-width: 992px) {
        .dash-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .dash-calendar-row {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }

    @media (max-width: 576px) {
        .dash-hero {
            align-items: flex-start;
            flex-direction: column;
        }

        .dash-stat-grid,
        .dash-calendar-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
@php
    $totalCases = $districtWise->sum('total');
    $totalMale = $districtWise->sum('male');
    $totalFemale = $districtWise->sum('female');
    $totalUnder18 = $districtWise->sum('under_18');
    $topDistricts = $districtWise->sortByDesc('total')->take(6);
    $topPngos = $pngoWise->sortByDesc('total')->take(6);
@endphp

<section class="smart-dashboard">
    <div class="dash-hero">
        <div>
            <span class="dash-kicker">A2J4W Monitoring</span>
            <h1>Dashboard</h1>
            <p>A quick operational view of cases, ToDos, and partner activity.</p>
            <div class="dash-quick-actions">
                @can('View Formal Cases Form')
                    <a href="{{ route('form.index') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> New Case</a>
                @endcan
                @can('View ToDo List')
                    <a href="{{ route('todos.index') }}" class="btn btn-outline-success btn-sm"><i class="fas fa-list-check"></i> ToDo</a>
                @endcan
                @can('View Case List Report')
                    <a href="{{ route('case_list') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-table"></i> Case List</a>
                @endcan
            </div>
        </div>
        <span class="dash-date-pill">{{ now()->format('j M, Y') }}</span>
    </div>

    <div class="dash-stat-grid">
        <div class="dash-stat-card total">
            <div>
                <span>Total Cases</span>
                <strong>{{ $totalCases }}</strong>
            </div>
            <div class="dash-stat-icon"><i class="fas fa-briefcase"></i></div>
        </div>
        <div class="dash-stat-card male">
            <div>
                <span>Male</span>
                <strong>{{ $totalMale }}</strong>
            </div>
            <div class="dash-stat-icon"><i class="fas fa-male"></i></div>
        </div>
        <div class="dash-stat-card female">
            <div>
                <span>Female</span>
                <strong>{{ $totalFemale }}</strong>
            </div>
            <div class="dash-stat-icon"><i class="fas fa-female"></i></div>
        </div>
        <div class="dash-stat-card child">
            <div>
                <span>Under 18</span>
                <strong>{{ $totalUnder18 }}</strong>
            </div>
            <div class="dash-stat-icon"><i class="fas fa-child"></i></div>
        </div>
    </div>

    @can('View ToDo List')
    <div class="dash-panel">
        <div class="dash-panel-header">
            <div>
                <h2>ToDo Calendar</h2>
                <small>Personal and follow-up tasks needing action</small>
            </div>
            <a href="{{ route('todos.index') }}" class="btn btn-success btn-sm">Open ToDo</a>
        </div>
        <div class="dash-panel-body">
            <div class="dash-calendar-row">
                @foreach ($todoSummary['upcoming'] as $day)
                    <a href="{{ route('todos.index', ['task_date' => $day['date']]) }}" class="dash-day {{ $day['total'] ? 'has-task' : '' }}">
                        <small>{{ $day['day'] }}</small>
                        <strong>{{ $day['label'] }}</strong>
                        <br>
                        <span class="dash-day-count">{{ $day['total'] }} task{{ $day['total'] === 1 ? '' : 's' }}</span>
                    </a>
                @endforeach
            </div>
            <div class="mt-2 text-muted">
                Today: <strong>{{ $todoSummary['today_total'] }}</strong> active task{{ $todoSummary['today_total'] === 1 ? '' : 's' }}.
            </div>
        </div>
    </div>
    @endcan

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <h2>District Summary</h2>
                        <small>Top districts by total cases</small>
                    </div>
                    <a href="{{ route('district.summery') }}" class="btn btn-outline-secondary btn-sm">Details</a>
                </div>
                <div class="dash-panel-body table-responsive">
                    @if ($topDistricts->count())
                        <table class="table table-bordered table-striped table-sm dash-table">
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
                                @foreach ($topDistricts as $row)
                                    <tr>
                                        <td><span class="dash-rank">{{ $loop->iteration }}</span> {{ $row['district_name'] }}</td>
                                        <td>{{ $row['male'] }}</td>
                                        <td>{{ $row['female'] }}</td>
                                        <td>{{ $row['transgender'] }}</td>
                                        <td>{{ $row['under_18'] }}</td>
                                        <td><strong>{{ $row['total'] }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="dash-empty">No district data available yet.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dash-panel h-100">
                <div class="dash-panel-header">
                    <div>
                        <h2>PNGO Summary</h2>
                        <small>Top PNGOs by total cases</small>
                    </div>
                    <a href="{{ route('pngo.summery') }}" class="btn btn-outline-secondary btn-sm">Details</a>
                </div>
                <div class="dash-panel-body table-responsive">
                    @if ($topPngos->count())
                        <table class="table table-bordered table-striped table-sm dash-table">
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
                                @foreach ($topPngos as $row)
                                    <tr>
                                        <td><span class="dash-rank">{{ $loop->iteration }}</span> {{ $row['pngo_name'] }}</td>
                                        <td>{{ $row['male'] }}</td>
                                        <td>{{ $row['female'] }}</td>
                                        <td>{{ $row['transgender'] }}</td>
                                        <td>{{ $row['under_18'] }}</td>
                                        <td><strong>{{ $row['total'] }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="dash-empty">No PNGO data available yet.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
