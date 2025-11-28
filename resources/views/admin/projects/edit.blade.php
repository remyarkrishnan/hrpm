@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.edit.title', ['project' => $project->name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('projects.edit.back_button') }}
        </a>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('projects.edit.view_button') }}
        </a>
    </div>
    <h2>{{ $project->name }}</h2>
    <p>{{ __('projects.edit.description') }}</p>
</div>

<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf
    @method('PUT')

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            {{ __('projects.edit.sections.project_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('projects.edit.form.project_name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">{{ __('projects.edit.form.project_code') }} *</label>
                <input type="text" id="project_code" name="project_code" 
                       value="{{ old('project_code', $project->project_code) }}" required>
                @error('project_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">{{ __('projects.edit.form.priority') }} *</label>
                <select id="priority" name="priority" required>
                    <option value="low" {{ old('priority', $project->priority) === 'low' ? 'selected' : '' }}>
                        {{ __('projects.edit.form.priority_low') }}
                    </option>
                    <option value="medium" {{ old('priority', $project->priority) === 'medium' ? 'selected' : '' }}>
                        {{ __('projects.edit.form.priority_medium') }}
                    </option>
                    <option value="high" {{ old('priority', $project->priority) === 'high' ? 'selected' : '' }}>
                        {{ __('projects.edit.form.priority_high') }}
                    </option>
                </select>
                @error('priority')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description">{{ __('projects.edit.form.description') }} *</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">{{ __('projects.edit.form.location') }} *</label>
                <input type="text" id="location" name="location" value="{{ old('location', $project->location) }}" required>
                @error('location')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">business</i>
            {{ __('projects.edit.sections.client_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="client_name">{{ __('projects.edit.form.client_name') }} *</label>
                <input type="text" id="client_name" name="client_name" 
                       value="{{ old('client_name', $project->client_name) }}" required>
                @error('client_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_contact">{{ __('projects.edit.form.client_contact') }}</label>
                <input type="text" id="client_contact" name="client_contact" 
                       value="{{ old('client_contact', $project->client_contact) }}">
                @error('client_contact')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Project Planning -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">schedule</i>
            {{ __('projects.edit.sections.project_planning') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="budget">{{ __('projects.edit.form.budget') }} *</label>
                <input type="number" id="budget" name="budget" value="{{ old('budget', $project->budget) }}" 
                       required min="0" step="0.01">
                @error('budget')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">{{ __('projects.edit.form.start_date') }} *</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">{{ __('projects.edit.form.end_date') }} *</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}" required>
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_manager_id">{{ __('projects.edit.form.project_manager') }} *</label>
                <select id="manager_id" name="manager_id" required>
                    <option value="">{{ __('projects.edit.form.select_manager') }}</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" 
                                {{ old('manager_id', $project->manager_id) == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->designation ?? __('projects.edit.form.default_manager_title') }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('projects.edit.form.update_button') }}
        </button>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-cancel">
            {{ __('projects.edit.form.cancel_button') }}
        </a>
        <button type="button" onclick="deleteProject({{ $project->id }})" class="btn-danger">
            <i class="material-icons">delete</i>
            {{ __('projects.edit.form.delete_button') }}
        </button>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 30); // Minimum 30 days

        endDateInput.min = minEndDate.toISOString().split('T')[0];

        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        }
    });

    // Form validation
    document.querySelector('.project-form').addEventListener('submit', function(e) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate <= startDate) {
            e.preventDefault();
            alert('{{ __("projects.edit.validation.end_date_after_start") }}');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> {{ __("projects.edit.form.updating") }}...';
        submitButton.disabled = true;
    });
});

// Delete project function
async function deleteProject(projectId) {
    if (!confirm('{{ __("projects.edit.delete.confirm1") }}')) {
        return;
    }

    const confirmation = prompt('{{ __("projects.edit.delete.confirm2") }}');
    if (confirmation !== 'DELETE') {
        alert('{{ __("projects.edit.delete.cancelled") }}');
        return;
    }

    try {
        const response = await fetch(`/admin/projects/${projectId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('{{ __("projects.edit.delete.success") }}');
            window.location.href = '/admin/projects';
        } else {
            alert(data.message || '{{ __("projects.edit.delete.error") }}');
        }
    } catch (error) {
        alert('{{ __("projects.edit.delete.error") }}: ' + error.message);
        console.error(error);
    }
}
</script>
@endpush
