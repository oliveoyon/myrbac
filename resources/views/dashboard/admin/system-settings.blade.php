@extends('dashboard.layouts.admin-layout')

@section('title', 'System Settings')

@push('styles')
<style>
    .settings-shell {
        display: grid;
        gap: 16px;
    }

    .settings-hero {
        padding: 18px 20px;
        border: 1px solid #dbe7df;
        border-radius: 8px;
        background: #f8fcfa;
    }

    .settings-hero h1 {
        margin: 0 0 4px;
        color: #17202a;
        font-size: 22px;
        font-weight: 800;
    }

    .settings-hero p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .settings-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 8px 18px rgba(16, 24, 40, .05);
        overflow: hidden;
    }

    .settings-card-header {
        padding: 13px 16px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8faf9;
    }

    .settings-card-header h2 {
        margin: 0;
        color: #1f2937;
        font-size: 16px;
        font-weight: 800;
    }

    .settings-card-body {
        padding: 16px;
    }

    .settings-field {
        margin-bottom: 14px;
    }

    .settings-field:last-child {
        margin-bottom: 0;
    }

    .settings-field label {
        color: #1f2937;
        font-size: 13px;
        font-weight: 800;
    }

    .settings-field small {
        display: block;
        margin-top: 4px;
        color: #64748b;
    }

    .settings-actions {
        position: sticky;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 12px 0 0;
        background: #fff;
    }

    @media (max-width: 576px) {
        .settings-hero {
            padding: 15px;
        }

        .settings-hero h1 {
            font-size: 20px;
        }

        .settings-card-body {
            padding: 13px;
        }

        .settings-actions .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="settings-shell">
    <div class="settings-hero">
        <h1>System Settings</h1>
        <p>Manage selected operational rules without changing server configuration files.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('system-settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            @foreach (collect($definitions)->groupBy('group', true) as $groupName => $groupDefinitions)
                <div class="col-lg-6">
                    <div class="settings-card h-100">
                        <div class="settings-card-header">
                            <h2>{{ $groupName }}</h2>
                        </div>
                        <div class="settings-card-body">
                            @foreach ($groupDefinitions as $key => $definition)
                                <div class="settings-field">
                                    <label for="{{ $key }}">{{ $definition['label'] }}</label>

                                    @if (($definition['type'] ?? 'string') === 'integer')
                                        <input
                                            type="number"
                                            class="form-control @error($key) is-invalid @enderror"
                                            id="{{ $key }}"
                                            name="{{ $key }}"
                                            min="{{ $definition['min'] ?? 0 }}"
                                            max="{{ $definition['max'] ?? 999 }}"
                                            value="{{ old($key, $values[$key] ?? $definition['default'] ?? '') }}"
                                            required
                                        >
                                    @elseif ($key === 'report_header_subtitle')
                                        <textarea
                                            class="form-control @error($key) is-invalid @enderror"
                                            id="{{ $key }}"
                                            name="{{ $key }}"
                                            rows="3"
                                        >{{ old($key, $values[$key] ?? $definition['default'] ?? '') }}</textarea>
                                    @else
                                        <input
                                            type="text"
                                            class="form-control @error($key) is-invalid @enderror"
                                            id="{{ $key }}"
                                            name="{{ $key }}"
                                            value="{{ old($key, $values[$key] ?? $definition['default'] ?? '') }}"
                                        >
                                    @endif

                                    @error($key)
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small>{{ $definition['description'] }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @can('Update System Settings')
            <div class="settings-actions">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Save Settings
                </button>
            </div>
        @endcan
    </form>
</section>
@endsection
