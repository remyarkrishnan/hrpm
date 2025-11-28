@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('resources.edit.title', ['resource' => $resource->name ?? 'Resource', 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('resources.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.resources.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('resources.edit.back_to_resources') }}
        </a>
        <a href="{{ route('admin.resources.show', $resource->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('resources.edit.view_details') }}
        </a>
    </div>
    <h2>{{ __('resources.edit.edit_resource') }}</h2>
    <p>{{ __('resources.edit.update_description', ['resource' => $resource->name ?? 'Morning Resource']) }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.resources.update', $resource->id ?? 1) }}" method="POST" class="resource-form">
        @csrf
        @method('PUT')

        <!-- Current Status Display -->
        <div class="current-status">
            <div class="status-info">
                <div class="resource-icon">
                    <i class="material-icons">schedule</i>
                </div>
                <div>
                    <h3>{{ $resource->name ?? __('resources.edit.morning_resource') }}</h3>
                    <p>{{ $resource->location ?? config('resources.default_location', 'Site A') }} • {{ $resource->employees_count ?? 15 }} {{ __('resources.edit.employees_assigned') }}</p>
                </div>
            </div>
            <div class="current-status-badge">
                <span class="resource-type resource-{{ $resource->type ?? 'morning' }}">
                    {{ __('resources.edit.resource_types.' . ($resource->type ?? 'morning')) }}
                </span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                {{ __('resources.edit.update_resource_info') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">{{ __('resources.edit.resource_name') }} *</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $resource->name ?? __('resources.edit.morning_resource')) }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">{{ __('resources.edit.resource_type') }} *</label>
                    <select id="type" name="type" required>
                        <option value="morning" {{ ($resource->type ?? 'morning') === 'morning' ? 'selected' : '' }}>
                            {{ __('resources.edit.resource_types.morning') }}
                        </option>
                        <option value="evening" {{ ($resource->type ?? '') === 'evening' ? 'selected' : '' }}>
                            {{ __('resources.edit.resource_types.evening') }}
                        </option>
                        <option value="night" {{ ($resource->type ?? '') === 'night' ? 'selected' : '' }}>
                            {{ __('resources.edit.resource_types.night') }}
                        </option>
                        <option value="flexible" {{ ($resource->type ?? '') === 'flexible' ? 'selected' : '' }}>
                            {{ __('resources.edit.resource_types.flexible') }}
                        </option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">access_time</i>
                {{ __('resources.edit.update_timing') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_time">{{ __('resources.edit.start_time') }} *</label>
                    <input type="time" id="start_time" name="start_time" 
                           value="{{ old('start_time', $resource->start_time ?? '07:00') }}" required>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">{{ __('resources.edit.end_time') }} *</label>
                    <input type="time" id="end_time" name="end_time" 
                           value="{{ old('end_time', $resource->end_time ?? '15:00') }}" required>
                    @error('end_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('resources.edit.break_duration') }} ({{ __('resources.edit.minutes') }})</label>
                    <input type="number" id="break_duration" name="break_duration" 
                           value="{{ old('break_duration', $resource->break_duration ?? 60) }}" 
                           min="0" max="120">
                </div>

                <div class="form-group">
                    <label for="total_hours">{{ __('resources.edit.total_hours') }}</label>
                    <input type="text" id="total_hours" readonly class="calculated-field">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('resources.edit.location_updates') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="location">{{ __('resources.edit.work_location') }}</label>
                    <select id="location" name="location">
                        <option value="">{{ __('resources.edit.select_location') }}</option>
                        @foreach(config('resources.locations', []) as $value => $label)
                            <option value="{{ $value }}" {{ old('location', $resource->location ?? '') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="max_employees">{{ __('resources.edit.max_employees') }}</label>
                    <input type="number" id="max_employees" name="max_employees" 
                           value="{{ old('max_employees', $resource->max_employees ?? 25) }}" min="1" max="100">
                </div>

                <div class="form-group">
                    <label for="supervisor">{{ __('resources.edit.resource_supervisor') }}</label>
                    <select id="supervisor" name="supervisor">
                        <option value="">{{ __('resources.edit.select_supervisor') }}</option>
                        @foreach($supervisors ?? [] as $supervisor)
                            <option value="{{ $supervisor->id }}" {{ old('supervisor', $resource->supervisor_id ?? '') == $supervisor->id ? 'selected' : '' }}>
                                {{ $supervisor->name }} - {{ $supervisor->role ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="project_id">{{ __('resources.edit.associated_project') }}</label>
                    <select id="project_id" name="project_id">
                        <option value="">{{ __('resources.edit.select_project_optional') }}</option>
                        @foreach($projects ?? [] as $project)
                            <option value="{{ $project->id }}" {{ old('project_id', $resource->project_id ?? '') == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Current Employee Assignments -->
        <div class="assignments-section">
            <h3 class="section-title">
                <i class="material-icons">groups</i>
                {{ __('resources.edit.current_assignments') }}
            </h3>

            <div class="assignments-summary">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">groups</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $resource->employees_count ?? 15 }} / {{ $resource->max_employees ?? 25 }}</h4>
                        <p>{{ __('resources.edit.employees_assigned') }}</p>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">person</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $resource->supervisor_name ?? __('resources.edit.default_supervisor') }}</h4>
                        <p>{{ __('resources.edit.shift_supervisor') }}</p>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">engineering</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $resource->skill_distribution ?? '8 ' . __('resources.edit.skilled') . ', 7 ' . __('resources.edit.general') }}</h4>
                        <p>{{ __('resources.edit.skill_distribution') }}</p>
                    </div>
                </div>
            </div>

            <div class="assignment-actions">
                <button type="button" class="btn-secondary" onclick="manageAssignments()">
                    <i class="material-icons">manage_accounts</i>
                    {{ __('resources.edit.manage_assignments') }}
                </button>
                <button type="button" class="btn-success" onclick="addEmployees()">
                    <i class="material-icons">person_add</i>
                    {{ __('resources.edit.add_employees') }}
                </button>
            </div>
        </div>

        <!-- Configuration Updates -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">settings</i>
                {{ __('resources.edit.configuration_updates') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ __('resources.edit.working_days') }}</label>
                    <div class="checkbox-group">
                        @foreach(config('resources.days', []) as $day)
                            <label class="checkbox-item">
                                <input type="checkbox" name="working_days[]" value="{{ $day['value'] }}" 
                                       {{ in_array(old('working_days.0', []), [$day['value']]) || (in_array($day['value'], $resource->working_days ?? []) || $day['default']) ? 'checked' : '' }}>
                                <span>{{ $day['label'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ __('resources.edit.overtime_settings') }}</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="1" 
                                   {{ old('overtime_allowed', $resource->overtime_allowed ?? 1) ? 'checked' : '' }}>
                            <span>{{ __('resources.edit.overtime_allowed') }}</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="0" 
                                   {{ !old('overtime_allowed', $resource->overtime_allowed ?? 1) ? 'checked' : '' }}>
                            <span>{{ __('resources.edit.no_overtime') }}</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">{{ __('resources.edit.resource_description') }}</label>
                    <textarea id="description" name="description" rows="3">
                        {{ old('description', $resource->description ?? __('resources.edit.default_description')) }}
                    </textarea>
                </div>
            </div>
        </div>

        <!-- Change Log -->
        <div class="audit-section">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                {{ __('resources.edit.recent_changes') }}
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">{{ __('resources.edit.oct_05_2025') }}</div>
                    <div class="audit-action">{{ __('resources.edit.resource_created') }}</div>
                    <div class="audit-user">{{ __('resources.edit.admin_user') }}</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">{{ __('resources.edit.oct_06_2025') }}</div>
                    <div class="audit-action">{{ __('resources.edit.employees_assigned_audit', ['count' => 15]) }}</div>
                    <div class="audit-user">{{ __('resources.edit.hr_manager') }}</div>
                </div>

                <div class="audit-item current">
                    <div class="audit-time">{{ now()->format('M d, Y - h:i A') }}</div>
                    <div class="audit-action">{{ __('resources.edit.configuration_modifying') }}</div>
                    <div class="audit-user">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('resources.edit.update_resource_button') }}
            </button>
            <a href="{{ route('admin.resources.show', $resource->id ?? 1) }}" class="btn-cancel">
                {{ __('resources.edit.cancel_changes') }}
            </a>
            <button type="button" class="btn-danger" onclick="deleteResource()">
                <i class="material-icons">delete</i>
                {{ __('resources.edit.delete_resource') }}
            </button>
        </div>
    </form>
</div>
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


    .form-container {
        max-width: 1000px;
    }


    .shift-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }


    .current-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }


    .status-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }


    .shift-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }


    .status-info h3 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 500;
    }


    .status-info p {
        margin: 0;
        color: #666;
    }


    .shift-type {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }


    .shift-morning { background: #fff3e0; color: #f57c00; }
    .shift-evening { background: #f3e5f5; color: #7b1fa2; }
    .shift-night { background: #e8eaf6; color: #3949ab; }
    .shift-flexible { background: #e0f2f1; color: #00695c; }


    .form-section, .assignments-section, .audit-section {
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
        font-size: 18px;
        font-weight: 500;
        color: #1C1B1F;
    }


    .section-title i { color: #6750A4; }


    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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


    .calculated-field {
        background: #f5f5f5 !important;
        color: #666;
    }


    .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }


    .checkbox-group, .radio-group {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 8px;
    }


    .checkbox-item, .radio-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.2s;
    }


    .checkbox-item:hover, .radio-item:hover {
        background: #e3f2fd;
    }


    .assignments-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }


    .summary-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }


    .summary-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }


    .summary-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }


    .summary-info p {
        margin: 0;
        color: #666;
        font-size: 12px;
    }


    .assignment-actions {
        display: flex;
        gap: 16px;
        justify-content: center;
    }


    .audit-trail {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }


    .audit-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #6750A4;
    }


    .audit-item.current {
        background: #e3f2fd;
        border-left-color: #2196F3;
    }


    .audit-time {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }


    .audit-action {
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }


    .audit-user {
        font-size: 12px;
        color: #6750A4;
    }


    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }


    .btn-primary, .btn-cancel, .btn-danger, .btn-success {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        border: none;
        transition: all 0.2s;
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


    .btn-success {
        background: #4CAF50;
        color: white;
    }


    .btn-success:hover { background: #45a049; }


    @media (max-width: 768px) {
        .form-grid, .checkbox-group, .radio-group, .assignments-summary { grid-template-columns: 1fr; }
        .current-status { flex-direction: column; gap: 16px; text-align: center; }
        .form-actions, .assignment-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger, .btn-success { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const breakInput = document.getElementById('break_duration');
    const totalHoursInput = document.getElementById('total_hours');

    function calculateHours() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('1970-01-01 ' + startTimeInput.value);
            const end = new Date('1970-01-01 ' + endTimeInput.value);

            let diffMs = end - start;
            if (diffMs < 0) {
                diffMs += 24 * 60 * 60 * 1000;
            }

            const diffHours = diffMs / (1000 * 60 * 60);
            const breakHours = (parseInt(breakInput.value) || 0) / 60;
            const workingHours = Math.max(0, diffHours - breakHours);

            totalHoursInput.value = workingHours.toFixed(1) + ' {{ __("resources.edit.hours") }}';
        }
    }

    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);
    breakInput.addEventListener('input', calculateHours);

    calculateHours();
});

function manageAssignments() {
    alert('{{ __("resources.edit.assignment_management_todo") }}');
}

function addEmployees() {
    alert('{{ __("resources.edit.employee_addition_todo") }}');
}

function deleteResource() {
    if (confirm('{{ __("resources.edit.delete_confirm") }}')) {
        fetch(`/admin/resources/{{ $resource->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("resources.edit.delete_success") }}');
                window.location.href = '/admin/resources';
            } else {
                alert('{{ __("resources.edit.delete_error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("resources.edit.delete_error") }}');
        });
    }
}

document.querySelector('.resource-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> {{ __("resources.edit.updating") }}...';
    submitButton.disabled = true;
});
</script>
@endpush
