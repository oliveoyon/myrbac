@extends('dashboard.layouts.admin-layout')

@section('title', 'Case Summary Management')

@push('styles')
<style>
    .summary-management { display: grid; gap: 16px; min-width: 0; }
    .summary-management-head { display: flex; justify-content: space-between; align-items: center; gap: 14px; padding: 18px 20px; border: 1px solid #e3e7e5; border-radius: 8px; background: #fff; }
    .summary-management-head h1 { margin: 0 0 3px; font-size: 21px; font-weight: 800; color: #1f2937; }
    .summary-management-head p { margin: 0; color: #667085; font-size: 13px; }
    .summary-filter { padding: 15px; border: 1px solid #e3e7e5; border-radius: 8px; background: #f9fbfa; }
    .summary-filter-grid { display: grid; grid-template-columns: minmax(180px, 1.4fr) repeat(5, minmax(130px, 1fr)) auto; gap: 10px; align-items: end; }
    .summary-filter label { display: block; margin-bottom: 5px; color: #344054; font-size: 12px; font-weight: 700; }
    .summary-table-panel { border: 1px solid #e3e7e5; border-radius: 8px; background: #fff; overflow: hidden; }
    .summary-table { margin: 0; font-size: 13px; }
    .summary-table th { padding: 10px; color: #344054; background: #f5f7f6; white-space: nowrap; }
    .summary-table td { padding: 10px; vertical-align: middle; color: #344054; }
    .summary-id { color: #9d0c06; font-weight: 800; white-space: nowrap; }
    .summary-status { display: inline-flex; padding: 3px 8px; border-radius: 999px; font-size: 11px; font-weight: 800; white-space: nowrap; }
    .summary-status.submitted { color: #8a4b08; background: #fff3dc; }
    .summary-status.verified { color: #17643a; background: #e9f6ee; }
    .summary-actions { display: flex; flex-wrap: nowrap; gap: 5px; }
    .summary-actions .btn { padding: 5px 8px; font-size: 11px; white-space: nowrap; }
    .summary-pagination { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 12px 14px; border-top: 1px solid #eaecf0; }
    .summary-pagination .pagination { margin: 0; }
    .summary-pagination .page-link { min-width: 32px; padding: 5px 8px; font-size: 12px; text-align: center; }
    @media (max-width: 1100px) { .summary-filter-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 767px) {
        .summary-management-head { align-items: stretch; flex-direction: column; padding: 15px; }
        .summary-management-head .btn { width: 100%; }
        .summary-filter-grid { grid-template-columns: 1fr 1fr; }
        .summary-filter-grid .filter-search, .summary-filter-grid .filter-actions { grid-column: 1 / -1; }
        .summary-table-panel { border: 0; background: transparent; overflow: visible; }
        .summary-table thead { display: none; }
        .summary-table, .summary-table tbody { display: block; }
        .summary-table tr { display: grid; grid-template-columns: 1fr auto; gap: 7px 12px; margin-bottom: 9px; padding: 11px 12px; border: 1px solid #e3e7e5; border-radius: 7px; background: #fff; }
        .summary-table td { display: block; padding: 0; border: 0; }
        .summary-table td::before { content: attr(data-label); display: block; margin-bottom: 2px; color: #667085; font-size: 10px; font-weight: 700; text-transform: uppercase; }
        .summary-table td.summary-wide { grid-column: 1 / -1; }
        .summary-actions { justify-content: flex-end; }
        .summary-pagination { align-items: stretch; flex-direction: column; background: #fff; border: 1px solid #e3e7e5; border-radius: 7px; }
    }
    @media (max-width: 480px) { .summary-filter-grid { grid-template-columns: 1fr; } .summary-filter-grid > * { grid-column: 1 !important; } }
</style>
@endpush

@section('content')
<section class="summary-management">
    <div class="summary-management-head">
        <div>
            <h1>Case Summary Management</h1>
            <p>Review submitted summaries and complete DPO verification within the assigned scope.</p>
        </div>
        @can('Create Case Summary')
            <a href="{{ route('case-summaries.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> New Summary</a>
        @endcan
    </div>

    @if (session('success'))<div class="alert alert-success mb-0">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger mb-0">{{ session('error') }}</div>@endif

    <form method="GET" action="{{ route('case-summaries.index') }}" class="summary-filter">
        <div class="summary-filter-grid">
            <div class="filter-search">
                <label for="summary_search">Central ID or Case Number</label>
                <input id="summary_search" name="search" class="form-control form-control-sm" value="{{ request('search') }}">
            </div>
            <div>
                <label for="summary_district">District</label>
                <select id="summary_district" name="district_id" class="form-select form-select-sm">
                    <option value="">All districts</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected((string) request('district_id') === (string) $district->id)>{{ $district->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="summary_pngo">PNGO</label>
                <select id="summary_pngo" name="pngo_id" class="form-select form-select-sm">
                    <option value="">All PNGOs</option>
                    @foreach ($pngos as $pngo)
                        <option value="{{ $pngo->id }}" data-district="{{ $pngo->district_id }}" @selected((string) request('pngo_id') === (string) $pngo->id)>{{ $pngo->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="summary_status">Status</label>
                <select id="summary_status" name="status" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    <option value="1" @selected(request('status') === '1')>Submitted</option>
                    <option value="2" @selected(request('status') === '2')>DPO Verified</option>
                </select>
            </div>
            <div><label for="summary_from">From</label><input type="date" id="summary_from" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm"></div>
            <div><label for="summary_to">To</label><input type="date" id="summary_to" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm"></div>
            <div class="filter-actions d-flex gap-2">
                <button class="btn btn-success btn-sm" type="submit"><i class="fas fa-filter"></i> Filter</button>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('case-summaries.index') }}" title="Reset filters"><i class="fas fa-rotate-left"></i></a>
            </div>
        </div>
    </form>

    <div class="summary-table-panel">
        <div class="table-responsive">
            <table class="table table-hover summary-table">
                <thead><tr><th>Central ID</th><th>Case Number</th><th>Date</th><th>District</th><th>PNGO</th><th>Court</th><th>Type</th><th>Status</th><th>Creator</th><th>Actions</th></tr></thead>
                <tbody>
                    @forelse ($caseSummaries as $summary)
                        <tr>
                            <td data-label="Central ID"><span class="summary-id">{{ $summary->central_id }}</span></td>
                            <td data-label="Case Number">{{ $summary->case_number }}</td>
                            <td data-label="Date">{{ optional($summary->summary_date)->format('d M, Y') }}</td>
                            <td data-label="District">{{ $summary->district?->name ?? 'N/A' }}</td>
                            <td data-label="PNGO">{{ $summary->pngo?->name ?? 'N/A' }}</td>
                            <td data-label="Court" class="summary-wide">{{ $summary->court?->name ?? 'N/A' }}</td>
                            <td data-label="Type">{{ $summary->summary_type }}</td>
                            <td data-label="Status"><span class="summary-status {{ $summary->status === 2 ? 'verified' : 'submitted' }}">{{ $summary->status === 2 ? 'DPO Verified' : 'Submitted' }}</span></td>
                            <td data-label="Creator">{{ $summary->creator?->full_name ?: ($summary->creator?->name ?? 'N/A') }}</td>
                            <td data-label="Actions">
                                <div class="summary-actions">
                                    @can('View Case Summary Details')
                                        <a href="{{ route('case-summaries.show', $summary) }}" class="btn btn-outline-success" title="Review"><i class="fas fa-eye"></i> Review</a>
                                    @endcan
                                    @can('Edit Case Summary')
                                        @if ($summary->status === 1 || auth()->user()->hasAnyRole(['Admin', 'Super Admin']))
                                            <a href="{{ route('case-summaries.edit', $summary) }}" class="btn btn-outline-secondary" title="Edit"><i class="fas fa-pen"></i></a>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">No case summaries match the selected filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($caseSummaries->hasPages())
            <div class="summary-pagination">
                <small class="text-muted">Showing {{ $caseSummaries->firstItem() }}–{{ $caseSummaries->lastItem() }} of {{ $caseSummaries->total() }}</small>
                {{ $caseSummaries->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const district = document.getElementById('summary_district');
    const pngo = document.getElementById('summary_pngo');
    function filterPngos() {
        const selected = district.value;
        Array.from(pngo.options).forEach(function(option) {
            option.hidden = option.value !== '' && selected !== '' && option.dataset.district !== selected;
        });
        if (pngo.selectedOptions[0]?.hidden) pngo.value = '';
    }
    district?.addEventListener('change', filterPngos);
    filterPngos();

});
</script>
@endpush
