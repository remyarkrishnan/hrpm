@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee.attendance.edit.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee.attendance.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.attendance.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee.attendance.edit.back_to_attendance') }}
        </a>
        <a href="{{ route('employee.attendance.show', $attendance->id) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('employee.attendance.edit.view_details') }}
        </a>
    </div>
    <p>{{ __('employee.attendance.edit.update_info') }}</p>
</div>

<div class="form-container">
    <form class="attendance-form" action="{{ route('employee.attendance.update', $attendance->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" id="user_id" name="user_id" value="{{ $attendance->user->id }}">

        <!-- Time Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                {{ __('employee.attendance.edit.time_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="date">{{ __('employee.attendance.edit.date_label') }} *</label>
                    <input type="date" id="date" name="date" value="{{ $attendance->date->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="check_in">{{ __('employee.attendance.edit.check_in_label') }} *</label>
                    <input type="time" id="check_in" name="check_in" value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}" required>
                    <small class="form-help" style="display:none">{{ __('employee.attendance.edit.check_in_original') }}</small>
                </div>

                <div class="form-group">
                    <label for="check_out">{{ __('employee.attendance.edit.check_out_label') }}</label>
                    <input type="time" id="check_out" name="check_out" value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
                    <small class="form-help" style="display:none">{{ __('employee.attendance.edit.check_out_original') }}</small>
                </div>

                <div class="form-group">
                    <label for="status">{{ __('employee.attendance.edit.status_label') }} *</label>
                    <select id="status" name="status" required>
                        <option value="present" {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>
                            {{ __('employee.attendance.edit.status.present') }}
                        </option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>
                            {{ __('employee.attendance.edit.status.absent') }}
                        </option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>
                            {{ __('employee.attendance.edit.status.late') }}
                        </option>
                        <option value="half_day" {{ old('status', $attendance->status) === 'half_day' ? 'selected' : '' }}>
                            {{ __('employee.attendance.edit.status.half_day') }}
                        </option>
                        <option value="work_from_home" {{ old('status', $attendance->status) === 'work_from_home' ? 'selected' : '' }}>
                            {{ __('employee.attendance.edit.status.work_from_home') }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="break_duration">{{ __('employee.attendance.edit.break_duration_label') }}</label>
                    <input type="number" id="break_duration" name="break_duration" value="{{ $attendance->break_duration }}" min="0">
                    <small class="form-help" style="display:none">{{ __('employee.attendance.edit.break_duration_current') }}</small>
                </div>

                <div class="form-group">
                    <label for="overtime_hours">{{ __('employee.attendance.edit.overtime_label') }}</label>
                    <input type="number" id="overtime_hours" name="overtime_hours" 
                           step="0.5" min="0" value="{{ $attendance->overtime_hours }}" placeholder="{{ __('employee.attendance.edit.overtime_placeholder') }}">
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                {{ __('employee.attendance.edit.location_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="project">{{ __('employee.attendance.edit.project_label') }}</label>
                    <select id="project" name="project_id">
                        <option value="">{{ __('employee.attendance.edit.project_select') }}</option>
                        @foreach($projects as $prj)
                        <option value="{{ $prj->id }}" {{ old('project_id', $attendance->project_id) === $prj->id ? 'selected' : '' }}>
                            {{ $prj->name }}
                        </option>
                        @endforeach    
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">{{ __('employee.attendance.edit.location_label') }}</label>
                    <select id="project_location_id" name="project_location_id">
                        <option value="">{{ __('employee.attendance.edit.location_select') }}</option>
                        @foreach($projectLocations as $prjl)
                        <option value="{{ $prjl->id }}" {{ old('project_location_id', $attendance->project_location_id) === $prjl->id ? 'selected' : '' }}>
                            {{ $prjl->location_name }}
                        </option>
                        @endforeach    
                    </select>
                </div>

                <div class="form-group">
                    <label for="gps">
                        {{ __('employee.attendance.edit.gps_label') }}
                        <button type="button" id="gpsBtn" class="btn btn-primary btn-sm ml-2" onclick="enableGPS()" style="margin-top: 10px;">
                            {{ __('employee.attendance.edit.gps_enable') }}
                        </button>
                    </label>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                </div>

                <div class="form-group">
                    <label for="location_image">{{ __('employee.attendance.edit.photo_label') }}</label>
                    <input type="file" id="location_image" name="location_image" accept="image/*">
                </div>

                <div class="form-group" style="display:none">
                    <label for="check_in_coords">{{ __('employee.attendance.edit.check_in_coords_label') }}</label>
                    <input type="text" id="check_in_coords" name="check_in_coordinates" 
                           value="28.4595, 77.0266" readonly>
                    <small class="form-help">{{ __('employee.attendance.edit.check_in_coords_help') }}</small>
                </div>

                <div class="form-group" style="display:none">
                    <label for="check_out_coords">{{ __('employee.attendance.edit.check_out_coords_label') }}</label>
                    <input type="text" id="check_out_coords" name="check_out_coordinates" 
                           value="28.4595, 77.0266" readonly>
                    <small class="form-help">{{ __('employee.attendance.edit.check_out_coords_help') }}</small>
                </div>
            </div>
        </div>

        <!-- Approval Information (Hidden) -->
        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">approval</i>
                {{ __('employee.attendance.edit.approval_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="approved_by">{{ __('employee.attendance.edit.approved_by_label') }}</label>
                    <select id="approved_by" name="approved_by">
                        <option value="1">{{ __('employee.attendance.edit.approved_by.neha_gupta') }}</option>
                        <option value="2">{{ __('employee.attendance.edit.approved_by.suresh_patel') }}</option>
                        <option value="3">{{ __('employee.attendance.edit.approved_by.vikram_singh') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approval_status">{{ __('employee.attendance.edit.approval_status_label') }}</label>
                    <select id="approval_status" name="approval_status">
                        <option value="pending">{{ __('employee.attendance.edit.approval_status.pending') }}</option>
                        <option value="approved">{{ __('employee.attendance.edit.approval_status.approved') }}</option>
                        <option value="rejected">{{ __('employee.attendance.edit.approval_status.rejected') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approval_date">{{ __('employee.attendance.edit.approval_date_label') }}</label>
                    <input type="datetime-local" id="approval_date" name="approval_date" 
                           value="2025-10-07T19:00">
                </div>
            </div>
        </div>

        <!-- Notes & Remarks -->
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">note</i>
                {{ __('employee.attendance.edit.notes_section_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="notes">{{ __('employee.attendance.edit.notes_label') }}</label>
                    <textarea id="notes" name="notes" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                </div>

                <div class="form-group full-width" style="display:none">
                    <label for="admin_notes">{{ __('employee.attendance.edit.admin_notes_label') }}</label>
                    <textarea id="admin_notes" name="admin_notes" rows="2" 
                              placeholder="{{ __('employee.attendance.edit.admin_notes_placeholder') }}"></textarea>
                </div>

                <div class="form-group" style="display:none">
                    <label for="late_reason">{{ __('employee.attendance.edit.late_reason_label') }}</label>
                    <select id="late_reason" name="late_reason">
                        <option value="">{{ __('employee.attendance.edit.late_reason_select') }}</option>
                        <option value="traffic">{{ __('employee.attendance.edit.late_reason.traffic') }}</option>
                        <option value="transport">{{ __('employee.attendance.edit.late_reason.transport') }}</option>
                        <option value="personal">{{ __('employee.attendance.edit.late_reason.personal') }}</option>
                        <option value="weather">{{ __('employee.attendance.edit.late_reason.weather') }}</option>
                        <option value="other">{{ __('employee.attendance.edit.late_reason.other') }}</option>
                    </select>
                </div>

                <div class="form-group" style="display:none">
                    <label for="penalty">{{ __('employee.attendance.edit.penalty_label') }}</label>
                    <select id="penalty" name="penalty">
                        <option value="none">{{ __('employee.attendance.edit.penalty.none') }}</option>
                        <option value="warning">{{ __('employee.attendance.edit.penalty.warning') }}</option>
                        <option value="half_day_deduction">{{ __('employee.attendance.edit.penalty.half_day') }}</option>
                        <option value="salary_cut">{{ __('employee.attendance.edit.penalty.salary_cut') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Audit Trail (Hidden) -->
        <div class="audit-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                {{ __('employee.attendance.edit.audit_title') }}
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">{{ __('employee.attendance.edit.audit.approved_neha') }}</div>
                    <div class="audit-action">{{ __('employee.attendance.edit.audit.record_approved') }}</div>
                    <div class="audit-user">{{ __('employee.attendance.edit.audit.system_admin') }}</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">{{ __('employee.attendance.edit.audit.checkout_auto') }}</div>
                    <div class="audit-action">{{ __('employee.attendance.edit.audit.checkout_recorded') }}</div>
                    <div class="audit-user">{{ __('employee.attendance.edit.audit.mobile_app') }}</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">{{ __('employee.attendance.edit.audit.checkin_gps') }}</div>
                    <div class="audit-action">{{ __('employee.attendance.edit.audit.checkin_recorded') }}</div>
                    <div class="audit-user">{{ __('employee.attendance.edit.audit.mobile_app') }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('employee.attendance.edit.update_button') }}
            </button>
            <a href="{{ route('employee.attendance.show', $attendance->id) }}" class="btn-cancel">
                {{ __('employee.attendance.edit.cancel_button') }}
            </a>
            <button type="button" class="btn-danger" onclick="deleteAttendance()">
                <i class="material-icons">delete</i>
                {{ __('employee.attendance.edit.delete_button') }}
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

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .page-header p {
        margin: 0;
        color: #666;
    }

    .form-container {
        max-width: 1000px;
    }

    .attendance-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section, .audit-section {
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

    .form-group input[readonly] {
        background: #f5f5f5;
        color: #666;
    }

    .form-help {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
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

    .btn-primary, .btn-cancel, .btn-danger {
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

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteAttendance() {
    if (confirm('{{ __('employee.attendance.edit.delete_confirm') }}')) {
        alert('{{ __('employee.attendance.edit.delete_success') }}');
        window.location.href = '/employee/attendance';
    }
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const projectSelect = document.getElementById('project');
    const locationSelect = document.getElementById('project_location_id');

    projectSelect.addEventListener('change', function () {
        const projectId = this.value;

        locationSelect.innerHTML = '<option value="">{{ __('employee.attendance.edit.location_select') }}</option>';

        if (!projectId) return;

        fetch(`/admin/projects/${projectId}/locations`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.locations.length > 0) {
                    data.locations.forEach(loc => {
                        const option = document.createElement('option');
                        option.value = loc.id;
                        option.textContent = loc.name;
                        locationSelect.appendChild(option);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.textContent = '{{ __('employee.attendance.edit.no_locations') }}';
                    locationSelect.appendChild(opt);
                }
            })
            .catch(error => {
                console.error('Error fetching locations:', error);
            });
    });
});
</script>
@endpush
