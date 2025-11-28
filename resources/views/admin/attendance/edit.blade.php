@extends('layouts.admin')

@section('title', 'Edit Attendance - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Edit Attendance')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.attendance.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Attendance
        </a>
        <a href="{{ route('admin.attendance.show', 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            View Details
        </a>
    </div>
    <p>Update attendance information</p>
</div>

<div class="form-container">
    <form class="attendance-form" action="{{ route('admin.attendance.update', $attendance->id) }}" method="POST">
            @csrf
    @method('PUT')
    <input type="hidden" id="user_id" name="user_id" value="{{ $attendance->user->id }}">
        <!-- Current Status -->
        <div class="current-status">
            <div class="status-info">
                <div class="employee-avatar">
                  @if(!empty($attendance->user->photo) && Storage::disk('public')->exists($attendance->user->photo))
                <img src="{{ asset('storage/' . $attendance->user->photo) }}" alt="{{ $attendance->user->name }}" 
                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            @else
                <span >
                    {{ strtoupper(substr($attendance->user->name, 0, 1)) }}
                </span>
            @endif  


                </div>
                <div>
                    <h3>{{ $attendance->user->name }}</h3>
                    <p>{{ $attendance->user->designation }} • {{ $attendance->user->department }}</p>
                    <div class="attendance-date">{{ $attendance->date->format('d-m-Y') }}</div>
                </div>
            </div>
            <div class="current-status-badge" style="display:none">
                <span class="status-badge status-late">Late Arrival</span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                Time Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="date">Attendance Date *</label>
                    <input type="date" id="date" name="date" value="{{ $attendance->date->format('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="check_in">Check In Time *</label>
                    <input type="time" id="check_in" name="check_in" value="{{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '' }}" required>
                    <small class="form-help" style="display:none">Original: 09:15 AM (15 minutes late)</small>
                </div>

                <div class="form-group">
                    <label for="check_out">Check Out Time</label>
                    <input type="time" id="check_out" name="check_out" value="{{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '' }}">
                    <small class="form-help" style="display:none">Original: 06:30 PM</small>
                </div>

                <div class="form-group">
                    <label for="status">Attendance Status *</label>
                    <select id="status" name="status" required>
                        <option value="present"  {{ old('status', $attendance->status) === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ old('status', $attendance->status) === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ old('status', $attendance->status) === 'late' ? 'selected' : '' }}>Late</option>
                        <option value="half_day" {{ old('status', $attendance->status) === 'half_day' ? 'selected' : '' }}>Half Day</option>
                        <option value="work_from_home" {{ old('status', $attendance->status) === 'work_from_home' ? 'selected' : '' }}>Work From Home</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="break_duration">Break Duration (minutes)</label>
                    <input type="number" id="break_duration" name="break_duration" value="{{ $attendance->break_duration }}" min="0">
                    <small class="form-help" style="display:none">Current: 45 minutes</small>
                </div>

                <div class="form-group">
                    <label for="overtime_hours">Overtime Hours</label>
                    <input type="number" id="overtime_hours" name="overtime_hours" 
                           step="0.5" min="0" value="0" placeholder="e.g. 2.5" value="{{ $attendance->overtime_hours }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">location_on</i>
                Location Information
            </h3>

            <div class="form-grid">
               <div class="form-group">
                    <label for="project">Project </label>
                    <select id="project" name="project_id">
                    <option value="">Select Project</option>
                    @foreach($projects as $prj)
                        <option value="{{ $prj->id }}"  {{ old('project_id', $attendance->project_id) ===  $prj->id ? 'selected' : '' }}>
                            {{ $prj->name }}
                        </option>
                    @endforeach   
                    </select>
                </div>

                <div class="form-group">
                    <label for="location">Project Location</label>
                    <select id="project_location_id" name="project_location_id">
                        <option value="">Select Location</option>
                        @foreach($projectLocations as $prjl)
                        <option value="{{ $prjl->id }}"  {{ old('project_location_id', $attendance->project_location_id) ===  $prjl->id ? 'selected' : '' }}>
                            {{ $prjl->location_name }}
                        </option>
                    @endforeach   
                    </select>
                </div>

                

                <div class="form-group" style="display:none">
                    <label for="check_in_coords">Check-in Coordinates</label>
                    <input type="text" id="check_in_coords" name="check_in_coordinates" 
                           value="28.4595, 77.0266" readonly>
                    <small class="form-help">GPS coordinates from mobile app</small>
                </div>

                <div class="form-group" style="display:none">
                    <label for="check_out_coords">Check-out Coordinates</label>
                    <input type="text" id="check_out_coords" name="check_out_coordinates" 
                           value="28.4595, 77.0266" readonly>
                    <small class="form-help">GPS coordinates from mobile app</small>
                </div>
            </div>
        </div>

        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">approval</i>
                Approval Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="approved_by">Approved By</label>
                    <select id="approved_by" name="approved_by">
                        <option value="1" selected>Neha Gupta - Project Manager</option>
                        <option value="2">Suresh Patel - HR Manager</option>
                        <option value="3">Vikram Singh - Site Supervisor</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approval_status">Approval Status</label>
                    <select id="approval_status" name="approval_status">
                        <option value="pending">Pending</option>
                        <option value="approved" selected>Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approval_date">Approval Date</label>
                    <input type="datetime-local" id="approval_date" name="approval_date" 
                           value="2025-10-07T19:00">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">note</i>
                Notes & Remarks
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="notes">Current Notes</label>
                    <textarea id="notes" name="notes" rows="3">{{ old('notes', $attendance->notes) }}</textarea>
                </div>

                <div class="form-group full-width" style="display:none">
                    <label for="admin_notes">Admin Notes (Additional)</label>
                    <textarea id="admin_notes" name="admin_notes" rows="2" 
                              placeholder="Add any additional administrative notes"></textarea>
                </div>

                <div class="form-group" style="display:none">
                    <label for="late_reason">Late Arrival Reason</label>
                    <select id="late_reason" name="late_reason">
                        <option value="">Select Reason</option>
                        <option value="traffic" selected>Traffic Delay</option>
                        <option value="transport">Transport Issues</option>
                        <option value="personal">Personal Emergency</option>
                        <option value="weather">Weather Conditions</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="form-group" style="display:none">
                    <label for="penalty">Apply Penalty</label>
                    <select id="penalty" name="penalty">
                        <option value="none" selected>No Penalty</option>
                        <option value="warning">Warning</option>
                        <option value="half_day_deduction">Half Day Deduction</option>
                        <option value="salary_cut">Salary Deduction</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Audit Trail -->
        <div class="audit-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                Change History
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">Oct 07, 2025 - 07:00 PM</div>
                    <div class="audit-action">Record approved by Neha Gupta</div>
                    <div class="audit-user">System Admin</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">Oct 07, 2025 - 06:30 PM</div>
                    <div class="audit-action">Check-out recorded automatically</div>
                    <div class="audit-user">Mobile App</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">Oct 07, 2025 - 09:15 AM</div>
                    <div class="audit-action">Check-in recorded with GPS location</div>
                    <div class="audit-user">Mobile App</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                Update Attendance
            </button>
            <a href="{{ route('admin.attendance.show', $attendance->id) }}" class="btn-cancel">
                Cancel Changes
            </a>
            <button type="button" class="btn-danger" onclick="deleteAttendance()">
                <i class="material-icons">delete</i>
                Delete Record
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

    .employee-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 20px;
    }

    .status-info h3 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 500;
    }

    .status-info p {
        margin: 0 0 8px 0;
        color: #666;
    }

    .attendance-date {
        color: #6750A4;
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-late { background: #fff3e0; color: #f57c00; }

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
        .current-status { flex-direction: column; gap: 16px; text-align: center; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteAttendance() {
    if (confirm('Are you sure you want to delete this attendance record?\n\nThis action cannot be undone.')) {
        // Delete attendance logic here
        alert('Attendance record deleted successfully');
        window.location.href = '/admin/attendance';
    }
}


</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const projectSelect = document.getElementById('project');
    const locationSelect = document.getElementById('project_location_id');

    projectSelect.addEventListener('change', function () {
        const projectId = this.value;

        // Clear previous options
        locationSelect.innerHTML = '<option value="">Select Location</option>';

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
                    opt.textContent = 'No locations available';
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
