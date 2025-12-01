@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/profile/edit.title'))
@section('page-title', __('employee/profile/edit.page_title'))


@section('content')
<div class="page-header">
    <div class="page-nav">
       
    </div>
    <p>{{ __('employee/profile/edit.subtitle') }}</p>
</div>


<form method="POST" action="{{ route('admin.profile.update') }}" style="display:none">
    @csrf

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">person</i>
            {{ __('employee/profile/edit.sections.basic') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('employee/profile/edit.labels.name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('employee/profile/edit.labels.email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="fa fa-save me-2"></i>{{ __('employee/profile/edit.actions.update_profile') }}
        </button>
    </div>
</form>

<form method="POST" action="{{ route('admin.profile.password') }}" style="margin-top:10px">
    @csrf

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">account_circle</i>
            {{ __('employee/profile/edit.sections.password') }}
        </h3>
        <div class="form-grid">
            <div class="form-group">
                <label for="password">{{ __('employee/profile/edit.labels.current_password') }} *</label>
                <input type="password" id="password" name="current_password" required>
                @error('current_password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('employee/profile/edit.labels.new_password') }} *</label>
                <input type="password" id="password" name="new_password" required>
                @error('new_password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('employee/profile/edit.labels.confirm_password') }} *</label>
                <input type="password" id="password_confirmation" name="new_password_confirmation" required>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
             <i class="fa fa-sync-alt me-2"></i>{{ __('employee/profile/edit.actions.update_password') }}
        </button>
    </div>
</form>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav { margin-bottom: 16px; }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .btn-back:hover { background: rgba(103, 80, 164, 0.08); }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .page-header p {
        margin: 0;
        color: #666;
        font-size: 16px;
    }

    .employee-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 24px 0;
        font-size: 20px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .section-title i { color: #6750A4; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width { grid-column: 1 / -1; }

    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6750A4;
    }

    .form-group .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
    }

    .btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #5A4A94; }

    .btn-cancel {
        padding: 12px 24px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        text-decoration: none;
        color: #666;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fa fa-user-circle text-primary me-2"></i> {{ __('employee/profile/edit.title') }}
            </h4>
            <p class="text-muted mb-0">{{ __('employee/profile/edit.subtitle') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0">
            <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fa fa-id-card me-2"></i> {{ __('employee/profile/edit.sections.update_details') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('employee/profile/edit.labels.name') }}</label>
                            <input type="text" name="name" class="form-control rounded-pill"
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('employee/profile/edit.labels.email') }}</label>
                            <input type="email" name="email" class="form-control rounded-pill"
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fa fa-save me-2"></i>{{ __('employee/profile/edit.actions.update_profile') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-warning text-dark py-3">
                    <h6 class="mb-0 fw-semibold">
                        <i class="fa fa-key me-2"></i> {{ __('employee/profile/edit.sections.password') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.password') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('employee/profile/edit.labels.current_password') }}</label>
                            <input type="password" name="current_password" class="form-control rounded-pill" required>
                            @error('current_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        {{-- NOTE: New password inputs were missing in the second section of original code, added below for completeness --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('employee/profile/edit.labels.new_password') }}</label>
                            <input type="password" name="new_password" class="form-control rounded-pill" required>
                            @error('new_password') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">{{ __('employee/profile/edit.labels.confirm_password') }}</label>
                            <input type="password" name="new_password_confirmation" class="form-control rounded-pill" required>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-warning rounded-pill px-4 text-dark fw-semibold">
                                <i class="fa fa-sync-alt me-2"></i>{{ __('employee/profile/edit.actions.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection