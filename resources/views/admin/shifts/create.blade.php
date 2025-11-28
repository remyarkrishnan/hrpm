@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('shift.create.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('shift.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.shifts.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('shift.create.back_to_shifts') }}
        </a>
    </div>
    <p>{{ __('shift.create.description') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.shifts.store') }}" method="POST" class="shift-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                {{ __('shift.create.shift_information') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">{{ __('shift.create.shift_name') }} *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           placeholder="{{ __('shift.create.placeholder_shift_name') }}">
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">{{ __('shift.create.shift_type') }} *</label>
                    <select id="type" name="type" required>
                        <option value="">{{ __('shift.create.select_shift_type') }}</option>
                        <option value="morning" {{ old('type') === 'morning' ? 'selected' : '' }}>{{ __('shift.create.type_morning') }}</option>
                        <option value="evening" {{ old('type') === 'evening' ? 'selected' : '' }}>{{ __('shift.create.type_evening') }}</option>
                        <option value="night" {{ old('type') === 'night' ? 'selected' : '' }}>{{ __('shift.create.type_night') }}</option>
                        <option value="flexible" {{ old('type') === 'flexible' ? 'selected' : '' }}>{{ __('shift.create.type_flexible') }}</option>
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
                {{ __('shift.create.shift_timing') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_time">{{ __('shift.create.start_time') }} *</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">{{ __('shift.create.end_time') }} *</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('shift.create.break_duration') }}</label>
                    <input type="number" id="break_duration" name="break_duration"
                           value="{{ old('break_duration', 60) }}" min="0" max="120"
                           placeholder="{{ __('shift.create.placeholder_break') }}">
                </div>

                <div class="form-group">
                    <label for="total_hours">{{ __('shift.create.total_working_hours') }}</label>
                    <input type="text" id="total_hours" readonly class="calculated-field">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('shift.create.location_assignment') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="location">{{ __('shift.create.work_location') }}</label>
                    <select id="location" name="location">
                        <option value="">{{ __('shift.create.select_location') }}</option>
                        <option value="Site A - Gurgaon">Site A - Gurgaon</option>
                        <option value="Site B - Noida">Site B - Noida</option>
                        <option value="Site C - Faridabad">Site C - Faridabad</option>
                        <option value="Office - DLF">Office - DLF City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="max_employees">{{ __('shift.create.max_employees') }}</label>
                    <input type="number" id="max_employees" name="max_employees"
                           value="{{ old('max_employees') }}" min="1" max="100"
                           placeholder="{{ __('shift.create.placeholder_max_employees') }}">
                </div>

                <div class="form-group">
                    <label for="supervisor">{{ __('shift.create.supervisor') }}</label>
                    <select id="supervisor" name="supervisor">
                        <option value="">{{ __('shift.create.select_supervisor') }}</option>
                        <option value="1">Rajesh Kumar - Site Engineer</option>
                        <option value="2">Priya Singh - Construction Manager</option>
                        <option value="3">Amit Sharma - Safety Officer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="project_id">{{ __('shift.create.associated_project') }}</label>
                    <select id="project_id" name="project_id">
                        <option value="">{{ __('shift.create.select_project_optional') }}</option>
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
                {{ __('shift.create.shift_configuration') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="working_days">{{ __('shift.create.working_days') }}</label>
                    <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="monday" checked>
                            <span>Monday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="tuesday" checked>
                            <span>Tuesday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="wednesday" checked>
                            <span>Wednesday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="thursday" checked>
                            <span>Thursday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="friday" checked>
                            <span>Friday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="saturday" checked>
                            <span>Saturday</span>
                        </label>
                        <label class="checkbox-item">
                            <input type="checkbox" name="working_days[]" value="sunday">
                            <span>Sunday</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="overtime_allowed">{{ __('shift.create.overtime_settings') }}</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="1" checked>
                            <span>{{ __('shift.create.overtime_allowed') }}</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="0">
                            <span>{{ __('shift.create.no_overtime') }}</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">{{ __('shift.create.shift_description') }}</label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="{{ __('shift.create.shift_description') }}">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Shift Preview -->
        <div class="preview-section">
            <h3 class="section-title">
                <i class="material-icons">preview</i>
                {{ __('shift.create.preview') }}
            </h3>

            <div class="preview-card">
                <div class="preview-header">
                    <h4 id="preview-name">{{ __('shift.create.preview_new_shift') }}</h4>
                    <span class="preview-type" id="preview-type">{{ __('shift.create.preview_type_default') }}</span>
                </div>

                <div class="preview-details">
                    <div class="preview-time">
                        <i class="material-icons">schedule</i>
                        <span id="preview-time">{{ __('shift.create.preview_time_default') }}</span>
                    </div>

                    <div class="preview-location">
                        <i class="material-icons">location_on</i>
                        <span id="preview-location">{{ __('shift.create.preview_location_none') }}</span>
                    </div>

                    <div class="preview-capacity">
                        <i class="material-icons">groups</i>
                        <span id="preview-capacity">{{ __('shift.create.preview_capacity_none') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">add</i>
                {{ __('shift.create.create_shift') }}
            </button>
            <a href="{{ route('admin.shifts.index') }}" class="btn-cancel">
                {{ __('shift.create.cancel') }}
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
    // translations for JS preview defaults
    const t = {
        newShift: @json(__('shift.create.preview_new_shift')),
        typeDefault: @json(__('shift.create.preview_type_default')),
        timeDefault: @json(__('shift.create.preview_time_default')),
        locationNone: @json(__('shift.create.preview_location_none')),
        capacityNone: @json(__('shift.create.preview_capacity_none'))
    };

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
        previewName.textContent = nameInput.value || t.newShift;

        // Type
        const typeValue = typeSelect.value || t.typeDefault;
        previewType.textContent = typeValue.replace('_', ' ');
        previewType.className = 'preview-type shift-' + typeValue;

        // Time
        if (startTimeInput.value && endTimeInput.value) {
            previewTime.textContent = startTimeInput.value + ' to ' + endTimeInput.value;
        } else {
            previewTime.textContent = t.timeDefault;
        }

        // Location
        previewLocation.textContent = locationSelect.value || t.locationNone;

        // Capacity
        if (maxEmployeesInput.value) {
            previewCapacity.textContent = 'Max ' + maxEmployeesInput.value + ' employees';
        } else {
            previewCapacity.textContent = t.capacityNone;
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