@extends('dashboard.layouts.admin-layout')
@section('title', 'Act Management')
@section('content')
<section class="management-page">
    <div class="management-header">
        <div><h1>Act Management</h1><p>Manage acts and their source links.</p></div>
        @can('Add Act')<a class="btn btn-success" href="{{ route('acts.create') }}"><i class="fas fa-plus"></i> Add New Act</a>@endcan
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="management-card">
        <div class="management-card-header"><h2>Act List</h2><span class="management-count">{{ number_format($acts->total()) }} acts</span></div>
        <form method="GET" action="{{ route('acts.index') }}" class="p-3 d-flex flex-wrap gap-2">
            <label class="visually-hidden" for="act-search">Search acts</label>
            <input id="act-search" name="search" value="{{ $search }}" class="form-control" style="max-width:460px" placeholder="Search by title, act number, or year">
            <button class="btn btn-success">Search</button>
            @if($search !== '')<a class="btn btn-secondary" href="{{ route('acts.index') }}">Clear</a>@endif
        </form>
        <div class="table-responsive management-table-wrap">
            <table class="table table-striped table-hover management-table">
                <thead><tr><th>#</th><th>Act Title</th><th>Act No.</th><th>Year</th><th>Link</th>@can('Edit Act')<th>Actions</th>@endcan</tr></thead>
                <tbody>
                @forelse($acts as $act)
                    <tr><td>{{ $acts->firstItem() + $loop->index }}</td><td style="min-width:260px; white-space:normal">{{ $act->title }}</td><td>{{ $act->act_number ?? '—' }}</td><td>{{ $act->year }}</td><td><a href="{{ $act->url }}" target="_blank" rel="noopener noreferrer">View Act <i class="fas fa-external-link-alt"></i></a></td>@can('Edit Act')<td><a class="btn btn-warning btn-sm" href="{{ route('acts.edit', $act) }}">Edit</a></td>@endcan</tr>
                @empty
                    <tr><td colspan="6" class="text-center p-4">No acts found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $acts->links('pagination::bootstrap-5') }}</div>
    </div>
</section>
@endsection
