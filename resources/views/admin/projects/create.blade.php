@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.create.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('projects.create.back_button') }}
        </a>
    </div>
   
    <p>{{ __('projects.create.description', ['company' => env('COMPANY_NAME', 'Teqin Vally')]) }}</p>
</div>

<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            {{ __('projects.create.sections.project_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('projects.create.form.project_name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="{{ __('projects.create.form.project_name_placeholder') }}">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">{{ __('projects.create.form.project_code') }} *</label>
                <input type="text" id="project_code" name="project_code" value="{{ old('project_code') }}" required
                       placeholder="{{ __('projects.create.form.project_code_placeholder') }}">
                @error('project_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">{{ __('projects.create.form.priority') }} *</label>
                <select id="priority" name="priority" required>
                    <option value="">{{ __('projects.create.form.select_priority') }}</option>
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>{{ __('projects.create.form.priority_low') }}</option>
                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>{{ __('projects.create.form.priority_medium') }}</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>{{ __('projects.create.form.priority_high') }}</option>
                </select>
                @error('priority')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description">{{ __('projects.create.form.description') }} *</label>
                <textarea id="description" name="description" rows="4" required 
                          placeholder="{{ __('projects.create.form.description_placeholder') }}">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">{{ __('projects.create.form.location') }} *</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required
                       placeholder="{{ __('projects.create.form.location_placeholder') }}">
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
            {{ __('projects.create.sections.client_info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="client_name">{{ __('projects.create.form.client_name') }} *</label>
                <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required
                       placeholder="{{ __('projects.create.form.client_name_placeholder') }}">
                @error('client_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_contact">{{ __('projects.create.form.client_contact') }}</label>
                <input type="text" id="client_contact" name="client_contact" value="{{ old('client_contact') }}"
                       placeholder="{{ __('projects.create.form.client_contact_placeholder') }}">
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
            {{ __('projects.create.sections.project_planning') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="budget">{{ __('projects.create.form.budget') }} *</label>
                <input type="number" id="budget" name="budget" value="{{ old('budget') }}" required
                       min="0" step="0.01" placeholder="{{ __('projects.create.form.budget_placeholder') }}">
                @error('budget')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">{{ __('projects.create.form.start_date') }} *</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', date('Y-m-d')) }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">{{ __('projects.create.form.end_date') }} *</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ old('end_date') }}" required>
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_manager_id">{{ __('projects.create.form.project_manager') }} *</label>
                <select id="manager_id" name="manager_id" required>
                    <option value="">{{ __('projects.create.form.select_manager') }}</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->designation ?? __('projects.create.form.default_manager_title') }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- 12-Step Approval Workflow Info -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">approval</i>
            {{ __('projects.create.sections.approval_workflow') }}
        </h3>

        <div class="workflow-info">
            <p class="workflow-description">
                <strong>{{ __('projects.create.workflow.process_title') }}:</strong> {{ __('projects.create.workflow.description') }}
            </p>

            <div class="workflow-steps">
                <div class="workflow-grid">
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.1') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.2') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.3') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.4') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.5') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.6') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.7') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.8') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.9') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.10') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.11') }}</div>
                    <div class="workflow-step">{{ __('projects.create.workflow.steps.12') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('projects.create.form.create_button') }}
        </button>
        <a href="{{ route('admin.projects.index') }}" class="btn-cancel">
            {{ __('projects.create.form.cancel_button') }}
        </a>
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

    /* Workflow Info Styles */
    .workflow-info {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        border-left: 4px solid #6750A4;
    }

    .workflow-description {
        margin: 0 0 20px 0;
        color: #333;
        line-height: 1.5;
    }

    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .workflow-step {
        background: white;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #6750A4;
        text-align: center;
        border: 1px solid #e0e0e0;
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
        .workflow-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const projectCodeInput = document.getElementById('project_code');
    const prioritySelect = document.getElementById('priority');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');

    // Auto-generate project code based on name
    function generateProjectCode() {
        const name = nameInput.value;
        const year = new Date().getFullYear();

        if (name) {
            const nameCode = name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, '');
            const randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');
            projectCodeInput.value = `PROJ-${nameCode}-${year}-${randomNum}`;
        }
    }

    nameInput.addEventListener('blur', generateProjectCode);

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
            alert('{{ __("projects.create.validation.end_date_after_start") }}');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> {{ __("projects.create.form.creating") }}...';
        submitButton.disabled = true;
    });
});
</script>
@endpush
