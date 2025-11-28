@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('shift.edit.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('shift.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.shifts.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('shift.edit.back_to_shifts') }}
        </a>
        <a href="{{ route('admin.shifts.show', $shift->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('shift.edit.view_details') }}
        </a>
    </div>
    <p>{{ __('shift.edit.update_note', ['name' => $shift->name ?? __('shift.show.default_name')]) }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.shifts.update', $shift->id ?? 1) }}" method="POST" class="shift-form">
        @csrf
        @method('PUT')

        <!-- Current Status Display -->
        <div class="current-status">
            <div class="status-info">
                <div class="shift-icon">
                    <i class="material-icons">schedule</i>
                </div>
                <div>
                    <h3>{{ $shift->name ?? 'Morning Shift' }}</h3>
                    <p>{{ $shift->location ?? 'Site A' }} • {{ $shift->employees_count ?? 15 }} employees assigned</p>
                </div>
            </div>
            <div class="current-status-badge">
                <span class="shift-type shift-{{ $shift->type ?? 'morning' }}">
                    {{ ucfirst($shift->type ?? 'Morning') }} Shift
                </span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                {{ __('shift.edit.update_shift_information') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="name">{{ __('shift.create.shift_name') }} *</label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', $shift->name ?? 'Morning Shift') }}" required>
                    @error('name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type">{{ __('shift.create.shift_type') }} *</label>
                    <select id="type" name="type" required>
                        <option value="morning" {{ ($shift->type ?? 'morning') === 'morning' ? 'selected' : '' }}>{{ __('shift.create.type_morning') }}</option>
                        <option value="evening" {{ ($shift->type ?? '') === 'evening' ? 'selected' : '' }}>{{ __('shift.create.type_evening') }}</option>
                        <option value="night" {{ ($shift->type ?? '') === 'night' ? 'selected' : '' }}>{{ __('shift.create.type_night') }}</option>
                        <option value="flexible" {{ ($shift->type ?? '') === 'flexible' ? 'selected' : '' }}>{{ __('shift.create.type_flexible') }}</option>
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
                {{ __('shift.edit.update_shift_timing') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_time">{{ __('shift.create.start_time') }} *</label>
                    <input type="time" id="start_time" name="start_time" 
                           value="{{ old('start_time', $shift->start_time ?? '07:00') }}" required>
                    @error('start_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time">{{ __('shift.create.end_time') }} *</label>
                    <input type="time" id="end_time" name="end_time" 
                           value="{{ old('end_time', $shift->end_time ?? '15:00') }}" required>
                    @error('end_time')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('shift.create.break_duration') }}</label>
                    <input type="number" id="break_duration" name="break_duration" 
                           value="{{ old('break_duration', $shift->break_duration ?? 60) }}" 
                           min="0" max="120">
                </div>

                <div class="form-group">
                    <label for="total_hours">{{ __('shift.create.total_working_hours') }}</label>
                    <input type="text" id="total_hours" readonly class="calculated-field" value="8.0 hours">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('shift.edit.location_assignment_updates') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="location">Work Location</label>
                    <select id="location" name="location">
                        <option value="">Select Location</option>
                        <option value="Site A - Gurgaon" {{ ($shift->location ?? 'Site A - Gurgaon') === 'Site A - Gurgaon' ? 'selected' : '' }}>Site A - Gurgaon</option>
                        <option value="Site B - Noida" {{ ($shift->location ?? '') === 'Site B - Noida' ? 'selected' : '' }}>Site B - Noida</option>
                        <option value="Site C - Faridabad" {{ ($shift->location ?? '') === 'Site C - Faridabad' ? 'selected' : '' }}>Site C - Faridabad</option>
                        <option value="Office - DLF" {{ ($shift->location ?? '') === 'Office - DLF' ? 'selected' : '' }}>Office - DLF City</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="max_employees">{{ __('shift.create.max_employees') }}</label>
                    <input type="number" id="max_employees" name="max_employees" 
                           value="{{ old('max_employees', $shift->max_employees ?? 25) }}" min="1" max="100">
                </div>

                <div class="form-group">
                    <label for="supervisor">Shift Supervisor</label>
                    <select id="supervisor" name="supervisor">
                        <option value="">Select Supervisor</option>
                        <option value="1" {{ ($shift->supervisor_id ?? 1) == 1 ? 'selected' : '' }}>Rajesh Kumar - Site Engineer</option>
                        <option value="2" {{ ($shift->supervisor_id ?? '') == 2 ? 'selected' : '' }}>Priya Singh - Construction Manager</option>
                        <option value="3" {{ ($shift->supervisor_id ?? '') == 3 ? 'selected' : '' }}>Amit Sharma - Safety Officer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="project_id">Associated Project</label>
                    <select id="project_id" name="project_id">
                        <option value="">Select Project (Optional)</option>
                        <option value="1" {{ ($shift->project_id ?? 1) == 1 ? 'selected' : '' }}>Residential Complex - Phase 2</option>
                        <option value="2" {{ ($shift->project_id ?? '') == 2 ? 'selected' : '' }}>Commercial Mall Construction</option>
                        <option value="3" {{ ($shift->project_id ?? '') == 3 ? 'selected' : '' }}>Highway Bridge Project</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Current Employee Assignments -->
        <div class="assignments-section">
            <h3 class="section-title">
                <i class="material-icons">groups</i>
                {{ __('shift.edit.current_employee_assignments') }}
            </h3>

            <div class="assignments-summary">
                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">groups</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $shift->employees_count ?? 15 }} / {{ $shift->max_employees ?? 25 }}</h4>
                        <p>Employees Assigned</p>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">person</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $shift->supervisor ?? 'Rajesh Kumar' }}</h4>
                        <p>Shift Supervisor</p>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon">
                        <i class="material-icons">engineering</i>
                    </div>
                    <div class="summary-info">
                        <h4>{{ $shift->skill_distribution ?? '8 Skilled, 7 General' }}</h4>
                        <p>Skill Distribution</p>
                    </div>
                </div>
            </div>

            <div class="assignment-actions">
                <button type="button" class="btn-secondary" onclick="manageAssignments()">
                    <i class="material-icons">manage_accounts</i>
                    {{ __('shift.edit.manage_assignments') }}
                </button>
                <button type="button" class="btn-success" onclick="addEmployees()">
                    <i class="material-icons">person_add</i>
                    {{ __('shift.edit.add_employees') }}
                </button>
            </div>
        </div>

        <!-- Configuration Updates -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">settings</i>
                Shift Configuration Updates
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="working_days">Working Days</label>
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
                    <label for="overtime_allowed">Overtime Settings</label>
                    <div class="radio-group">
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="1" 
                                   {{ ($shift->overtime_allowed ?? true) ? 'checked' : '' }}>
                            <span>Overtime Allowed</span>
                        </label>
                        <label class="radio-item">
                            <input type="radio" name="overtime_allowed" value="0" 
                                   {{ !($shift->overtime_allowed ?? true) ? 'checked' : '' }}>
                            <span>No Overtime</span>
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="description">Shift Description</label>
                    <textarea id="description" name="description" rows="3">{{ old('description', $shift->description ?? 'This shift handles primary construction activities including foundation work, structural assembly, and site preparation. All safety protocols must be followed strictly.') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Change Log -->
        <div class="audit-section">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                {{ __('shift.edit.audit_recent_changes') }}
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">Oct 05, 2025 - 02:30 PM</div>
                    <div class="audit-action">Shift created</div>
                    <div class="audit-user">Admin User</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">Oct 06, 2025 - 10:15 AM</div>
                    <div class="audit-action">15 employees assigned to shift</div>
                    <div class="audit-user">HR Manager</div>
                </div>

                <div class="audit-item current">
                    <div class="audit-time">{{ now()->format('M d, Y - h:i A') }}</div>
                    <div class="audit-action">Shift configuration being modified</div>
                    <div class="audit-user">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('shift.edit.update_shift') }}
            </button>
            <a href="{{ route('admin.shifts.show', $shift->id ?? 1) }}" class="btn-cancel">
                {{ __('shift.edit.cancel_changes') }}
            </a>
            <button type="button" class="btn-danger" onclick="deleteShift()">
                <i class="material-icons">delete</i>
                {{ __('shift.show.delete') }}
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
                // Handle overnight shifts
                diffMs += 24 * 60 * 60 * 1000;
            }

            const diffHours = diffMs / (1000 * 60 * 60);
            const breakHours = (parseInt(breakInput.value) || 0) / 60;
            const workingHours = Math.max(0, diffHours - breakHours);

            totalHoursInput.value = workingHours.toFixed(1) + ' hours';
        }
    }

    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);
    breakInput.addEventListener('input', calculateHours);

    // Initial calculation
    calculateHours();
});

function manageAssignments() {
    // TODO: Open assignment management modal
    alert('Assignment management functionality will be implemented');
}

function addEmployees() {
    // TODO: Open employee selection modal
    alert('Employee addition functionality will be implemented');
}

function deleteShift() {
    if (confirm(@json(__('shift.edit.delete_shift_confirm')))) {
         fetch(`/admin/shifts/{{ $shift->id ?? 1 }}`, {
             method: 'DELETE',
             headers: {
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                 'Content-Type': 'application/json'
             }
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                alert(@json(__('shift.edit.delete_success')));
                 window.location.href = '/admin/shifts';
             } else {
                alert(@json(__('shift.edit.delete_error')));
             }
         })
         .catch(error => {
             console.error('Error:', error);
            alert(@json(__('shift.edit.delete_error')));
         });
     }
}

// Form submission with loading state
document.querySelector('.shift-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Updating...';
    submitButton.disabled = true;
});
</script>
@endpush