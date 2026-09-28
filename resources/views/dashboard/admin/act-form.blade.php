@extends('dashboard.layouts.admin-layout')
@section('title', $act->exists ? 'Edit Act' : 'Add Act')
@section('content')
<section class="management-page">
    <div class="management-header"><h1>{{ $act->exists ? 'Edit Act' : 'Add Act' }}</h1><a href="{{ route('acts.index') }}" class="btn btn-secondary">Back to Acts</a></div>
    <div class="management-card p-4">
        <form method="POST" action="{{ $act->exists ? route('acts.update', $act) : route('acts.store') }}">
            @csrf
            @if($act->exists) @method('PUT') @endif
            @foreach(['title' => 'Act Title', 'act_number' => 'Act Number', 'year' => 'Year', 'url' => 'Act Link'] as $field => $label)
                <div class="mb-3">
                    <label for="{{ $field }}" class="form-label">{{ $label }}{{ $field === 'act_number' ? ' (optional)' : '' }}</label>
                    <input id="{{ $field }}" name="{{ $field }}" type="{{ $field === 'year' ? 'number' : ($field === 'url' ? 'url' : 'text') }}" class="form-control @error($field) is-invalid @enderror" value="{{ old($field, $act->$field) }}" @if($field !== 'act_number') required @endif @if($field === 'year') min="1000" max="9999" @else maxlength="{{ $field === 'title' ? 1000 : ($field === 'url' ? 500 : 255) }}" @endif>
                    @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            @endforeach
            <button class="btn btn-success" type="submit">Save Act</button>
        </form>
    </div>
</section>
@endsection
