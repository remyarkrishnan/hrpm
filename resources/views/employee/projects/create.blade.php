@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/projects/form.create_title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/projects/form.create_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/projects/common.actions.back') }}
        </a>
    </div>
    <h2>{{ __('employee/projects/form.create_title') }}</h2>
    <p>{{ __('employee/projects/form.create_subtitle') }}</p>
</div>

<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            {{ __('employee/projects/form.sections.info') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('employee/projects/common.labels.name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="{{ __('employee/projects/form.placeholders.name') }}">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">{{ __('employee/projects/common.labels.code') }} *</label>
                <input type="text" id="project_code" name="project_code" value="{{ old('project_code') }}" required
                       placeholder="{{ __('employee/projects/form.placeholders.code') }}">
                @error('project_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">{{ __('employee/projects/common.labels.type') }} *</label>
                <select id="type" name="type" required>
                    <option value="">{{ __('employee/projects/form.options.select_type') }}</option>
                    @foreach(App\Models\Project::getTypes() as $key => $label)
                        <option value="{{ $key }}" {{ old('type') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">{{ __('employee/projects/common.labels.priority') }} *</label>
                <select id="priority" name="priority" required>
                    <option value="">{{ __('employee/projects/form.options.select_priority') }}</option>
                    @foreach(App\Models\Project::getPriorities() as $key => $label)
                        <option value="{{ $key }}" {{ old('priority', 'medium') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description">{{ __('employee/projects/common.labels.description') }} *</label>
                <textarea id="description" name="description" rows="4" required 
                          placeholder="{{ __('employee/projects/form.placeholders.desc') }}">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">{{ __('employee/projects/common.labels.location') }} *</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required
                       placeholder="{{ __('employee/projects/form.placeholders.location') }}">
                @error('location')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">business</i>
            {{ __('employee/projects/form.sections.client') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="client_name">{{ __('employee/projects/common.labels.client_name') }} *</label>
                <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required
                       placeholder="{{ __('employee/projects/form.placeholders.client_name') }}">
                @error('client_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_contact">{{ __('employee/projects/common.labels.client_contact') }}</label>
                <input type="text" id="client_contact" name="client_contact" value="{{ old('client_contact') }}"
                       placeholder="{{ __('employee/projects/form.placeholders.client_contact') }}">
                @error('client_contact')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">schedule</i>
            {{ __('employee/projects/form.sections.planning') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="budget">{{ __('employee/projects/common.labels.budget') }} (₹) *</label>
                <input type="number" id="budget" name="budget" value="{{ old('budget') }}" required
                       min="0" step="0.01" placeholder="{{ __('employee/projects/form.placeholders.budget') }}">
                @error('budget')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">{{ __('employee/projects/common.labels.start_date') }} *</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', date('Y-m-d')) }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="expected_end_date">{{ __('employee/projects/common.labels.end_date') }} *</label>
                <input type="date" id="expected_end_date" name="expected_end_date" 
                       value="{{ old('expected_end_date') }}" required>
                @error('expected_end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_manager_id">{{ __('employee/projects/common.labels.manager') }} *</label>
                <select id="project_manager_id" name="project_manager_id" required>
                    <option value="">{{ __('employee/projects/form.options.select_manager') }}</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" {{ old('project_manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->designation ?? 'Project Manager' }}
                        </option>
                    @endforeach
                </select>
                @error('project_manager_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">attachment</i>
            {{ __('employee/projects/form.sections.documents') }}
        </h3>

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="documents">{{ __('employee/projects/form.labels.upload_optional') }}</label>
                <input type="file" id="documents" name="documents[]" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="form-help">{{ __('employee/projects/form.help.formats') }}</small>
                @error('documents.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">approval</i>
            {{ __('employee/projects/form.sections.workflow') }}
        </h3>

        <div class="workflow-info">
            <p class="workflow-description">
                <strong>{{ __('employee/projects/form.workflow_info') }}</strong>
            </p>

            <div class="workflow-steps">
                <div class="workflow-grid">
                    @foreach(__('employee/projects/form.steps') as $step)
                        <div class="workflow-step">{{ $step }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('employee/projects/common.actions.create') }}
        </button>
        <a href="{{ route('admin.projects.index') }}" class="btn-cancel">
            {{ __('employee/projects/common.actions.cancel') }}
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
    const typeSelect = document.getElementById('type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('expected_end_date');

    // Auto-generate project code based on name and type
    function generateProjectCode() {
        const name = nameInput.value;
        const type = typeSelect.value;
        const year = new Date().getFullYear();

        if (name && type) {
            const nameCode = name.substring(0, 3).toUpperCase();
            const typeCode = type.substring(0, 3).toUpperCase();
            const randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');

            projectCodeInput.value = `${typeCode}-${nameCode}-${year}-${randomNum}`;
        }
    }

    nameInput.addEventListener('blur', generateProjectCode);
    typeSelect.addEventListener('change', generateProjectCode);

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
            alert('Expected completion date must be after start date');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Creating Project...';
        submitButton.disabled = true;
    });
});
</script>
@endpush