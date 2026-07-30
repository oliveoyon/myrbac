@extends('dashboard.layouts.admin-layout')

@section('title', 'Intervention Date Audit')

@push('styles')
<style>
    .audit-page {
        display: grid;
        gap: 14px;
        color: #17202a;
    }

    .audit-panel {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .audit-panel-header {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        align-items: flex-start;
        padding: 14px 16px;
        background: #f8faf9;
        border-bottom: 1px solid #e2e8f0;
    }

    .audit-panel-header h1,
    .audit-panel-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 17px;
        font-weight: 800;
    }

    .audit-panel-header small {
        color: #64748b;
    }

    .audit-panel-body {
        padding: 14px 16px;
    }

    .audit-summary {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 10px;
    }

    .audit-card {
        padding: 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
    }

    .audit-card span {
        display: block;
        color: #64748b;
        font-size: 12px;
        font-weight: 700;
    }

    .audit-card strong {
        display: block;
        margin-top: 5px;
        color: #111827;
        font-size: 24px;
        line-height: 1;
    }

    .audit-table {
        margin: 0;
        font-size: 13px;
    }

    .audit-table th {
        white-space: nowrap;
        color: #374151;
        background: #f8fafc;
    }

    .audit-table td {
        vertical-align: top;
    }

    .audit-chip {
        display: inline-flex;
        margin: 0 4px 4px 0;
        padding: 3px 7px;
        border-radius: 999px;
        background: #eef7f1;
        color: #285d49;
        font-size: 12px;
        font-weight: 700;
    }

    .audit-field-list {
        margin: 0;
        padding-left: 16px;
    }

    .audit-field-list li {
        margin-bottom: 3px;
    }

    .audit-empty {
        padding: 18px;
        color: #64748b;
        text-align: center;
        border: 1px dashed #d7dee3;
        border-radius: 8px;
        background: #fbfcfd;
    }

    @media (max-width: 768px) {
        .audit-panel-header {
            flex-direction: column;
        }

        .audit-summary {
            grid-template-columns: 1fr;
        }

        .audit-table {
            font-size: 12px;
        }
    }
</style>
@endpush

@section('content')
<section class="audit-page">
    <div class="audit-panel">
        <div class="audit-panel-header">
            <div>
                <h1>Intervention Date Audit</h1>
                <small>Cases counted by old broad activity logic but missing an approved intervention date.</small>
            </div>
            <a href="{{ route('customReport') }}" class="btn btn-outline-secondary btn-sm">Back to Intervention Report</a>
        </div>
        <div class="audit-panel-body">
            <div class="audit-summary">
                <div class="audit-card">
                    <span>Total flagged cases</span>
                    <strong>{{ $summary['total'] }}</strong>
                </div>
                <div class="audit-card">
                    <span>By institution</span>
                    <div class="mt-2">
                        @forelse ($summary['by_institute'] as $institute => $total)
                            <span class="audit-chip">{{ $institute }}: {{ $total }}</span>
                        @empty
                            <span class="text-muted">No flagged institution</span>
                        @endforelse
                    </div>
                </div>
                <div class="audit-card">
                    <span>Most common filled fields</span>
                    <div class="mt-2">
                        @forelse ($summary['top_fields'] as $field => $total)
                            <span class="audit-chip">{{ $field }}: {{ $total }}</span>
                        @empty
                            <span class="text-muted">No flagged fields</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="audit-panel">
        <div class="audit-panel-header">
            <div>
                <h2>Manual Review List</h2>
                <small>Use this list to decide whether data correction is needed or whether the case should stay outside intervention counts.</small>
            </div>
        </div>
        <div class="audit-panel-body">
            @if ($auditRows->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped audit-table">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Central ID</th>
                                <th>Institute</th>
                                <th>District</th>
                                <th>PNGO</th>
                                <th>Creator</th>
                                <th>Filled broad activity fields</th>
                                <th>Missing approved intervention date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($auditRows as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $row['central_id'] }}</strong></td>
                                    <td>{{ $row['institute'] }}</td>
                                    <td>{{ $row['district'] }}</td>
                                    <td>{{ $row['pngo'] }}</td>
                                    <td>{{ $row['creator'] }}</td>
                                    <td>
                                        <ul class="audit-field-list">
                                            @foreach ($row['filled_fields'] as $field)
                                                <li>
                                                    <strong>{{ $field['label'] }}</strong>
                                                    <span class="text-muted">({{ $field['value'] }})</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @foreach ($row['missing_intervention_date_labels'] as $label)
                                            <span class="audit-chip">{{ $label }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @can('View Edit Formal Case Form')
                                            <a href="{{ route('edit-case.get', ['central_id' => $row['central_id']]) }}" class="btn btn-sm btn-outline-success">Review</a>
                                        @else
                                            <span class="text-muted">No edit access</span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="audit-empty">No flagged cases found for your access scope.</div>
            @endif
        </div>
    </div>
</section>
@endsection
