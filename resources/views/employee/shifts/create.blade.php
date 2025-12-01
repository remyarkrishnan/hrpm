@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee/shifts/create.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/shifts/create.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.shifts.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/shifts/common.actions.back') }}
        </a>
    </div>
    <h2>{{ __('employee/shifts/create.title') }}</h2>
    <p>{{ __('employee/shifts/create.subtitle') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.shifts.store') }}" method="POST" class="shift-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                {{ __('employee/shifts/create.sections.info') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">{{ __('employee/shifts/common.labels.name') }} *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                           placeholder="{{ __('employee/shifts/create.placeholders.name') }}">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">{{ __('employee/shifts/common.labels.type') }} *</label>
                    <select id="type" name="type" required>
                        <option value="">{{ __('employee/shifts/create.options.select_type') }}</option>
                        <option value="morning" {{ old('type') === 'morning' ? 'selected' : '' }}>{{ __('employee/shifts/common.types.morning') }}</option>
                        <option value="evening" {{ old('type') === 'evening' ? 'selected' : '' }}>{{ __('employee/shifts/common.types.evening') }}</option>
                        <option value="night" {{ old('type') === 'night' ? 'selected' : '' }}>{{ __('employee/shifts/common.types.night') }}</option>
                        <option value="flexible" {{ old('type') === 'flexible' ? 'selected' : '' }}>{{ __('employee/shifts/common.types.flexible') }}</option>
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
                {{ __('employee/shifts/create.sections.timing') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_time">{{ __('employee/shifts/common.labels.start_time') }} *</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">{{ __('employee/shifts/common.labels.end_time') }} *</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('employee/shifts/common.labels.break_duration') }} ({{ __('employee/shifts/common.units.minutes') }})</label>
                    <input type="number" id="break_duration" name="break_duration" 
                           value="{{ old('break_duration', 60) }}" min="0" max="120" 
                           placeholder="{{ __('employee/shifts/create.placeholders.break') }}">
                </div>

                <div class="form-group">
                    <label for="total_hours">{{ __('employee/shifts/common.labels.total_hours') }}</label>
                    <input type="text" id="total_hours" readonly class="calculated-field">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('employee/shifts/create.sections.location') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="location">{{ __('employee/shifts/common.labels.location') }}</label>
                    <select id="location" name="location">
                        <option value="">{{ __('employee/shifts/create.options.select_location') }}</option>
                        <option value="Site A - Gurgaon">Site A - Gurgaon</option>
                        <option value="Site B - Noida">Site B - Noida</option>
                        <option value="Site C - Faridabad">Site C - Faridabad</option>
                        <option value="Office - DLF">Office - DLF City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="max_employees">{{ __('employee/shifts/common.labels.max_employees') }}</label>
                    <input type="number" id="max_employees" name="max_employees" 
                           value="{{ old('max_employees') }}" min="1" max="100" 
                           placeholder="{{ __('employee/shifts/create.placeholders.max') }}">
                </div>

                <div class="form-group">
                    <label for="supervisor">{{ __('employee/shifts/common.labels.supervisor') }}</label>
                    <select id="supervisor" name="supervisor">
                        <option value="">{{ __('employee/shifts/create.options.select_supervisor') }}</option>
                        <option value="1">Rajesh Kumar - Site Engineer</option>
                        <option value="2">Priya Singh - Construction Manager</option>
                        <option value="3">Amit Sharma - Safety Officer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="project_id">{{ __('employee/shifts/common.labels.project') }}</label>
                    <select id="project_id" name="project_id">
                        <option value="">{{ __('employee/shifts/create.options.select_project') }}</option>
                        <option value="1">Residential Complex - Phase 2</option>
                        <option value="2">Commercial Mall Construction</option>
                        <option value="3">Highway Bridge Project</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">settings</i>
                {{ __('employee/shifts/create.sections.config') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="working_days">{{ __('employee/shifts/common.labels.working_days') }}</label>
                    <div class="checkbox-group">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="{{ $day }}" {{ $day != 'sunday' ? 'checked' : '' }}>
                            <span>{{ ucfirst(substr($day, 0, 3)) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-group">
                    <label for="overtime_allowed">{{ __('employee/shifts/common.labels.overtime') }}</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="1" checked>
                            <span>{{ __('employee/shifts/create.options.overtime_yes') }}</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="0">
                            <span>{{ __('employee/shifts/create.options.overtime_no') }}</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">{{ __('employee/shifts/common.labels.description') }}</label>
                    <textarea id="description" name="description" rows="3" 
                              placeholder="{{ __('employee/shifts/create.placeholders.description') }}">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <div class="preview-section">
            <h3 class="section-title">
                <i class="material-icons">preview</i>
                {{ __('employee/shifts/create.sections.preview') }}
            </h3>

            <div class="preview-card">
                <div class="preview-header">
                    <h4 id="preview-name">{{ __('employee/shifts/create.preview.new') }}</h4>
                    <span class="preview-type" id="preview-type">morning</span>
                </div>

                <div class="preview-details">
                    <div class="preview-time">
                        <i class="material-icons">schedule</i>
                        <span id="preview-time">--:-- to --:--</span>
                    </div>

                    <div class="preview-location">
                        <i class="material-icons">location_on</i>
                        <span id="preview-location">{{ __('employee/shifts/create.preview.no_location') }}</span>
                    </div>

                    <div class="preview-capacity">
                        <i class="material-icons">groups</i>
                        <span id="preview-capacity">{{ __('employee/shifts/create.preview.no_limit') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">add</i>
                {{ __('employee/shifts/common.actions.create') }}
            </button>
            <a href="{{ route('admin.shifts.index') }}" class="btn-cancel">
                {{ __('employee/shifts/common.actions.cancel') }}
            </a>
        </div>
    </form>
</div>
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

    .form-container {
        max-width: 1000px;
    }

    .shift-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section, .preview-section {
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

    .checkbox-item input, .radio-item input {
        margin: 0;
        padding: 0;
    }

    .preview-card {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .preview-header h4 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    .preview-type {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        background: #fff3e0;
        color: #f57c00;
    }

    .preview-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .preview-time, .preview-location, .preview-capacity {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #666;
    }

    .preview-time i, .preview-location i, .preview-capacity i {
        color: #6750A4;
        font-size: 20px;
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
        .form-grid, .checkbox-group, .radio-group { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
        .preview-header { flex-direction: column; gap: 12px; text-align: center; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const typeSelect = document.getElementById('type');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
    const breakInput = document.getElementById('break_duration');
    const totalHoursInput = document.getElementById('total_hours');
    const locationSelect = document.getElementById('location');
    const maxEmployeesInput = document.getElementById('max_employees');

    // Preview elements
    const previewName = document.getElementById('preview-name');
    const previewType = document.getElementById('preview-type');
    const previewTime = document.getElementById('preview-time');
    const previewLocation = document.getElementById('preview-location');
    const previewCapacity = document.getElementById('preview-capacity');

    // Calculate total working hours
    function calculateHours() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('1970-01-01 ' + startTimeInput.value);
            const end = new Date('1970-01-01 ' + endTimeInput.value);

            let diffMs = end - start;
            if (diffMs < 0) {
                // Handle overnight shifts
                diffMs += 24 * 60 * 60 * 1000;
            }

            const diffHours = diffMs / (1000 * 60 * 60);
            const breakHours = (parseInt(breakInput.value) || 0) / 60;
            const workingHours = Math.max(0, diffHours - breakHours);

            totalHoursInput.value = workingHours.toFixed(1) + ' hours';
        } else {
            totalHoursInput.value = '';
        }
    }

    // Update preview
    function updatePreview() {
        // Name
        previewName.textContent = nameInput.value || 'New Shift';

        // Type
        const typeValue = typeSelect.value || 'morning';
        previewType.textContent = typeValue.replace('_', ' ');
        previewType.className = 'preview-type shift-' + typeValue;

        // Time
        if (startTimeInput.value && endTimeInput.value) {
            previewTime.textContent = startTimeInput.value + ' to ' + endTimeInput.value;
        } else {
            previewTime.textContent = '--:-- to --:--';
        }

        // Location
        previewLocation.textContent = locationSelect.value || 'No location selected';

        // Capacity
        if (maxEmployeesInput.value) {
            previewCapacity.textContent = 'Max ' + maxEmployeesInput.value + ' employees';
        } else {
            previewCapacity.textContent = 'No limit set';
        }
    }

    // Event listeners
    startTimeInput.addEventListener('change', function() {
        calculateHours();
        updatePreview();
    });

    endTimeInput.addEventListener('change', function() {
        calculateHours();
        updatePreview();
    });

    breakInput.addEventListener('input', function() {
        calculateHours();
    });

    nameInput.addEventListener('input', updatePreview);
    typeSelect.addEventListener('change', updatePreview);
    locationSelect.addEventListener('change', updatePreview);
    maxEmployeesInput.addEventListener('input', updatePreview);

    // Set default times based on shift type
    typeSelect.addEventListener('change', function() {
        const type = this.value;
        switch(type) {
            case 'morning':
                startTimeInput.value = '07:00';
                endTimeInput.value = '15:00';
                break;
            case 'evening':
                startTimeInput.value = '15:00';
                endTimeInput.value = '23:00';
                break;
            case 'night':
                startTimeInput.value = '23:00';
                endTimeInput.value = '07:00';
                break;
        }
        calculateHours();
        updatePreview();
    });

    // Initial update
    updatePreview();
});
</script>
@endpush