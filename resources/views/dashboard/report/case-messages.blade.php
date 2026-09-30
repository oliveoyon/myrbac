@extends('dashboard.layouts.admin-layout')

@section('title', 'Case Messages')

@push('styles')
    <style>
        .case-message-page {
            display: grid;
            gap: 14px;
        }

        .case-message-panel {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(16, 24, 40, .05);
        }

        .case-message-panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: #202832;
            color: #fff;
        }

        .case-message-panel-header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 800;
        }

        .case-message-table {
            margin: 0;
            font-size: 13px;
        }

        .case-message-table th {
            white-space: nowrap;
            background: #f8fafc;
            color: #1f2937;
        }

        .case-message-empty {
            padding: 22px;
            color: #64748b;
            text-align: center;
            border: 1px dashed #d7dee3;
            border-radius: 8px;
            background: #fbfcfd;
        }

        .case-message-pagination {
            width: 100%;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #e5e7eb;
        }

        .case-message-pagination nav,
        .case-message-pagination nav > div {
            width: 100%;
        }

        .case-message-pagination nav .d-sm-flex {
            gap: 24px;
        }

        .case-message-pagination nav p {
            margin: 0;
            color: #64748b;
        }

        .case-message-pagination .pagination {
            flex-wrap: wrap;
            justify-content: center;
            gap: 4px;
            margin-bottom: 0;
        }

        .case-message-pagination .page-link {
            display: inline-flex;
            min-width: 32px;
            min-height: 32px;
            align-items: center;
            justify-content: center;
            padding: 4px 9px;
            border-radius: 5px;
            font-size: 13px;
            line-height: 1.2;
        }

        .case-message-pagination svg {
            width: 14px;
            height: 14px;
        }

        @media (max-width: 576px) {
            .case-message-pagination {
                margin-top: 14px;
                padding-top: 12px;
            }

            .case-message-pagination nav > div {
                justify-content: center !important;
            }

            .case-message-pagination .page-link {
                min-width: 30px;
                min-height: 30px;
                padding: 3px 7px;
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <section class="case-message-page">
        <div class="case-message-panel">
            <div class="case-message-panel-header">
                <div>
                    <h1>Case Messages</h1>
                </div>
                <a href="{{ route('case_list') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-table"></i> Case List
                </a>
            </div>
            <div class="table-responsive p-3">
                @if ($threads->count())
                    <table class="table table-bordered table-striped table-hover table-sm case-message-table">
                        <thead>
                            <tr>
                                <th>Central ID</th>
                                <th>Name</th>
                                <th>District</th>
                                <th>PNGO</th>
                                <th>Last Message</th>
                                <th>Status</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($threads as $thread)
                                @php
                                    $lastMessage = $thread->latestMessage;
                                @endphp
                                <tr>
                                    <td>{{ $thread->formalCase->central_id ?? '-' }}</td>
                                    <td>{{ $thread->formalCase->full_name ?? '-' }}</td>
                                    <td>{{ $thread->formalCase->district->name ?? '-' }}</td>
                                    <td>{{ $thread->formalCase->pngo->name ?? '-' }}</td>
                                    <td>
                                        @if ($lastMessage)
                                            <strong>{{ $lastMessage->sender?->full_name ?: $lastMessage->sender?->name ?: 'Unknown' }}</strong>
                                            to
                                            <strong>{{ $lastMessage->receiver?->full_name ?: $lastMessage->receiver?->name ?: 'Unknown' }}</strong>
                                            <div class="text-muted">
                                                {{ \Illuminate\Support\Str::limit($lastMessage->message, 110) }}</div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $thread->status === 'resolved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($thread->status) }}
                                        </span>
                                    </td>
                                    <td>{{ optional($thread->updated_at)->format('j M, Y g:i A') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="case-message-pagination">
                        {{ $threads->links('pagination::bootstrap-5') }}
                    </div>
                @else
                    <div class="case-message-empty">No case messages found.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
