@extends('layouts.supervisor')

@section('title', 'Manual Attendance Entry - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Manual Attendance Entry')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('supervisor.attendance.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Attendance
        </a>
    </div>
   
    <p>Add attendance record for employees manually</p>
</div>

<div class="form-container">
    <form action="{{ route('supervisor.attendance.store') }}" method="POST" enctype="multipart/form-data" class="attendance-form">
    @csrf
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                Employee Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="employee">Select Employee *</label>
                    <select id="user_id" name="user_id" required>
                        <option value="">Choose Employee</option>
                         @foreach($employees as $emp)
                         <option value="{{ $emp->id }}" {{ old('user_id') === $emp->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $emp->name)) }}
                         </option>
                         @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="date">Attendance Date *</label>
                    <input type="date" id="date" name="date" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                Time Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="check_in">Check In Time *</label>
                    <input type="time" id="check_in" name="check_in" required>
                </div>

                <div class="form-group">
                    <label for="check_out">Check Out Time</label>
                    <input type="time" id="check_out" name="check_out">
                </div>

                <div class="form-group">
                    <label for="status">Attendance Status *</label>
                    <select id="status" name="status" required>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="half_day">Half Day</option>
                        <option value="work_from_home">Work From Home</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="break_duration">Break Duration (minutes)</label>
                    <input type="number" id="break_duration" name="break_duration" placeholder="e.g. 60" min="0">
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
                    <label for="project">Project Assignment</label>
                    <select id="project" name="project_id">
                    <option value="">Select Project</option>
                    @foreach($projects as $prj)
                        <option value="{{ $prj->id }}" {{ old('project_id') == $prj->id ? 'selected' : '' }}>
                            {{ $prj->name }}
                        </option>
                    @endforeach   
                    </select>
                </div>
                <div class="form-group">
                    <label for="location">Project Location</label>
                    <select id="project_location_id" name="project_location_id">
                        <option value="">Select Location</option>
                        
                    </select>
                </div>

                
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">note</i>
                Additional Information
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="notes">Notes/Remarks</label>
                    <textarea id="notes" name="notes" rows="3" 
                              placeholder="Any additional notes about this attendance record"></textarea>
                </div>

                <div class="form-group" style="display: none;">
                    <label for="approved_by">Approved By</label>
                    <select id="approved_by" name="approved_by">
                        <option value="">Select Approver</option>
                        <option value="1">Project Manager - Neha Gupta</option>
                        <option value="2">HR Manager - Suresh Patel</option>
                        <option value="3">Site Supervisor - Vikram Singh</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="overtime_hours">Overtime Hours</label>
                    <input type="number" id="overtime_hours" name="overtime_hours" 
                           step="0.5" min="0" placeholder="e.g. 2.5">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                Save Attendance Record
            </button>
            <a href="{{ route('admin.attendance.index') }}" class="btn-cancel">
                Cancel
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
        max-width: 800px;
    }

    .attendance-form {
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
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
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
