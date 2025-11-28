@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.superplans.edit.title', ['activity' => $subplan->activity_name]))
@section('page-title', __('projects.superplans.edit.page_title', ['activity' => $subplan->activity_name]))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.subplans.index', $subplan->project_step_id) }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('projects.superplans.edit.back_button') }}
        </a>
    </div>
</div>

<form action="{{ route('admin.subplans.update', $subplan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Basic Project Information -->
    <div class="form-section">
        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('projects.superplans.edit.form.activity_name') }} *</label>
                <input type="text" id="activity_name" name="activity_name" value="{{ old('activity_name', $subplan->activity_name) }}" required>
                @error('activity_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">{{ __('projects.superplans.edit.form.description') }}</label>
                <textarea name="description" class="form-control">{{ old('description', $subplan->description) }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">{{ __('projects.superplans.edit.form.start_date') }} *</label>
                <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $subplan->start_date?->format('Y-m-d')) }}">
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">{{ __('projects.superplans.edit.form.end_date') }} *</label>
                <input type="date" name="end_date" class="form-control" value="{{ old('end_date', $subplan->end_date?->format('Y-m-d')) }}">
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">{{ __('projects.superplans.edit.form.progress_percentage') }}</label>
                <input type="number" name="progress_percentage" class="form-control" min="0" max="100" value="{{ old('progress_percentage', $subplan->progress_percentage) }}">
                @error('progress_percentage')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="form-actions">
                <button type="submit" class="btn-primary">
                    <i class="material-icons">save</i>
                    {{ __('projects.superplans.edit.form.update_button') }}
                </button>
                <a href="{{ route('admin.subplans.index', $subplan->project_step_id) }}" class="btn-cancel">
                    {{ __('projects.superplans.edit.form.cancel_button') }}
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back, .btn-secondary {
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

    .btn-back:hover, .btn-secondary:hover { background: rgba(103, 80, 164, 0.08); }

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

    .project-form {
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

    .form-help {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
    }

    /* Document List Styles */
    .document-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .document-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .document-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #333;
    }

    .document-info i {
        color: #6750A4;
    }

    .btn-view {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6750A4;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background 0.2s;
        font-size: 14px;
    }

    .btn-view:hover { background: rgba(103, 80, 164, 0.08); }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }

    .btn-primary, .btn-cancel, .btn-danger {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
    }

    .btn-primary {
        background: #6750A4;
        color: white;
    }

    .btn-primary:hover { background: #5A4A94; }

    .btn-cancel {
        border: 2px solid #e0e0e0;
        color: #666;
        background: white;
    }

    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }

    .btn-danger {
        background: #d32f2f;
        color: white;
    }

    .btn-danger:hover { background: #b71c1c; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush
