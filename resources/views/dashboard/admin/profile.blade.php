@extends('dashboard.layouts.admin-layout')

@section('title', 'My Profile')

@push('styles')
<style>
    .profile-page {
        display: grid;
        gap: 16px;
    }

    .profile-hero {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        align-items: flex-start;
        padding: 18px 20px;
        border: 1px solid #e1e5ea;
        border-left: 4px solid #c30f08;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    .profile-identity {
        display: flex;
        gap: 14px;
        align-items: center;
        min-width: 0;
    }

    .profile-avatar {
        display: inline-flex;
        width: 52px;
        height: 52px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #fff7f6;
        color: #c30f08;
        font-size: 22px;
        flex: 0 0 auto;
    }

    .profile-hero h1 {
        margin: 0;
        color: #111827;
        font-size: 22px;
        font-weight: 800;
    }

    .profile-hero p {
        margin: 4px 0 0;
        color: #64748b;
        font-size: 13px;
    }

    .profile-status {
        display: inline-flex;
        align-items: center;
        min-height: 28px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #eef7f1;
        color: #17643a;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .profile-status.inactive {
        background: #f3f4f6;
        color: #475569;
    }

    .profile-status.warning {
        background: #fff7e5;
        color: #9a6e19;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(320px, .8fr);
        gap: 16px;
    }

    .profile-card {
        border: 1px solid #e0e6ed;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }

    .profile-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 16px;
        border-bottom: 1px solid #f0d2cf;
        background: #fff7f6;
        color: #c30f08;
    }

    .profile-card-header h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
    }

    .profile-card-body {
        padding: 16px;
    }

    .profile-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .profile-detail {
        padding: 11px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fbfcfd;
    }

    .profile-detail span {
        display: block;
        margin-bottom: 3px;
        color: #64748b;
        font-size: 12px;
        font-weight: 800;
    }

    .profile-detail strong {
        display: block;
        color: #111827;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .profile-chip-list {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    .profile-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 9px;
        border-radius: 999px;
        background: #eef7f1;
        color: #17643a;
        font-size: 12px;
        font-weight: 800;
    }

    .profile-scope-list {
        display: grid;
        gap: 8px;
        margin-top: 10px;
    }

    .profile-scope-item {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 9px 10px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fbfcfd;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .profile-password-note {
        margin: 0 0 14px;
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 7px;
        background: #fbfcfd;
        color: #64748b;
        font-size: 13px;
    }

    .profile-password-form .form-label {
        color: #475569;
        font-size: 12px;
        font-weight: 800;
    }

    .profile-password-form .form-control {
        min-height: 40px;
        border-color: #d8dee6;
        font-size: 13px;
    }

    .profile-password-form .btn {
        min-height: 40px;
        font-weight: 700;
    }

    @media (max-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .profile-page {
            gap: 12px;
        }

        .profile-hero {
            flex-direction: column;
            padding: 14px;
        }

        .profile-identity {
            align-items: flex-start;
        }

        .profile-avatar {
            width: 44px;
            height: 44px;
            font-size: 19px;
        }

        .profile-hero h1 {
            font-size: 18px;
            line-height: 1.3;
        }

        .profile-status {
            width: 100%;
            justify-content: center;
        }

        .profile-card-header {
            padding: 12px 14px;
        }

        .profile-card-body {
            padding: 12px;
        }

        .profile-detail-grid {
            grid-template-columns: 1fr;
        }

        .profile-scope-item {
            flex-direction: column;
            gap: 3px;
        }

        .profile-password-form .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
@php
    $displayName = $user->full_name ?: $user->name;
    $roles = $user->roles->pluck('name');
    $statusLabel = match ((int) $user->status) {
        1 => 'Active',
        2 => 'Must Change Password',
        default => 'Inactive',
    };
    $statusClass = match ((int) $user->status) {
        1 => '',
        2 => 'warning',
        default => 'inactive',
    };
@endphp

<section class="profile-page">
    @if($user->status == 2)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Notice:</strong> You must change your password to continue using the system.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following issues:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="profile-hero">
        <div class="profile-identity">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <h1>{{ $displayName }}</h1>
                <p>{{ $user->email }} | Username: {{ $user->name }}</p>
            </div>
        </div>
        <span class="profile-status {{ $statusClass }}">{{ $statusLabel }}</span>
    </div>

    <div class="profile-grid">
        <div class="profile-card">
            <div class="profile-card-header">
                <h2><i class="fas fa-id-card me-2"></i>Account Information</h2>
            </div>
            <div class="profile-card-body">
                <div class="profile-detail-grid">
                    <div class="profile-detail">
                        <span>Full Name</span>
                        <strong>{{ $user->full_name ?: 'Not set' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>User Name</span>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Email</span>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Status</span>
                        <strong>{{ $statusLabel }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>District</span>
                        <strong>{{ $user->district->name ?? 'Not assigned' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>PNGO</span>
                        <strong>{{ $user->pngo->name ?? 'Not assigned' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Joined</span>
                        <strong>{{ optional($user->created_at)->format('j M, Y') ?: '-' }}</strong>
                    </div>
                    <div class="profile-detail">
                        <span>Last Updated</span>
                        <strong>{{ optional($user->updated_at)->format('j M, Y') ?: '-' }}</strong>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="profile-detail">
                        <span>Roles</span>
                        <div class="profile-chip-list">
                            @forelse ($roles as $role)
                                <span class="profile-chip">{{ $role }}</span>
                            @empty
                                <strong>No role assigned</strong>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="profile-detail">
                        <span>Additional District-PNGO Access</span>
                        @if ($user->pngoScopes->isNotEmpty())
                            <div class="profile-scope-list">
                                @foreach ($user->pngoScopes as $scope)
                                    <div class="profile-scope-item">
                                        <strong>{{ $scope->district->name ?? 'Unknown district' }}</strong>
                                        <span>{{ $scope->pngo->name ?? 'Unknown PNGO' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <strong>No additional scope assigned</strong>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-card">
            <div class="profile-card-header">
                <h2><i class="fas fa-key me-2"></i>Change Password</h2>
            </div>
            <div class="profile-card-body">
                <p class="profile-password-note">
                    Password must be at least 8 characters and include uppercase, lowercase, number, and special character.
                </p>
                <form action="{{ route('users.change-my-password') }}" method="POST" class="profile-password-form">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
