@extends('layouts.supervisor')

@section('title', 'Attendance Details - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Attendance Details')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('supervisor.attendance.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Attendance
        </a>
    </div>
    <div class="attendance-header">
        <div class="employee-info">
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
                <h2>{{ $attendance->user->name }}</h2>
                <p>{{ $attendance->user->designation }} • {{ $attendance->user->department }}</p>
                <div class="attendance-date">
                    <i class="material-icons">event</i>
                    <span>{{ $attendance->date->format('d-m-Y') }}</span>
                </div>
            </div>
        </div>
        <div class="attendance-status">
            <span class="status-badge status-late" style="display:none">Late Arrival</span>
            <div class="working-hours" style="display:none">
                <strong>8h 15m</strong>
                <small>Working Hours</small>
            </div>
        </div>
    </div>
</div>

<div class="attendance-details">
    <!-- Time Tracking -->
    <div class="detail-grid">
        <div class="detail-card">
            <h3>Time Tracking</h3>
            <div class="time-entries">
                <div class="time-entry check-in">
                    <div class="time-icon">
                        <i class="material-icons">login</i>
                    </div>
                    <div class="time-info">
                        <h4>Check In</h4>
                        <div class="time-value">{{ optional($attendance->check_in)->format('h:i A') ?? '-' }}</div>
                        <div class="time-status late" style="display:none">15 minutes late</div>
                        <div class="location" style="display:none">
                            <i class="material-icons">location_on</i>
                            Site A - Gurgaon
                        </div>
                    </div>
                </div>

                <div class="time-entry check-out">
                    <div class="time-icon">
                        <i class="material-icons">logout</i>
                    </div>
                    <div class="time-info">
                        <h4>Check Out</h4>
                        <div class="time-value">{{ optional($attendance->check_out)->format('h:i A') ?? '-' }}</div>
                        <div class="time-status on-time" style="display:none">On time</div>
                        <div class="location" style="display:none">
                            <i class="material-icons">location_on</i>
                            Site A - Gurgaon
                        </div>
                    </div>
                </div>
            </div>

            <div class="break-info" style="display:none">
                <h4>Break Duration</h4>
                <div class="break-time">45 minutes</div>
                <small>Lunch break: 12:30 PM - 01:15 PM</small>
            </div>
        </div>

        <div class="detail-card">
            <h3>Work Summary</h3>
            <div class="work-stats">
                <div class="stat-item">
                    <div class="stat-value"> @if($attendance->work_hours)
        {{ $attendance->work_hours }}
    @elseif($attendance->check_in && $attendance->check_out)
        @php
            $duration = \Carbon\Carbon::parse($attendance->check_out)
                        ->diffInMinutes(\Carbon\Carbon::parse($attendance->check_in));
            $hours = intdiv($duration, 60);
            $minutes = $duration % 60;
        @endphp
        {{ $hours }}h {{ $minutes }}m
    @else
        -
    @endif</div>
                    <div class="stat-label">Total Hours</div>
                </div>
                <div class="stat-item" style="display:none">
                    <div class="stat-value">7h 30m</div>
                    <div class="stat-label">Productive Hours</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $attendance->break_duration ?? '-' }}</div>
                    <div class="stat-label">Break Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $attendance->overtime_hours ?? '-' }}</div>
                    <div class="stat-label">Overtime</div>
                </div>
            </div>

            <div class="project-assignment">
                <h4>Project </h4>
                <div class="project-info">
                    <strong>{{ $attendance->project->name ?? '-' }}</strong>
                    <small>{{ $attendance->projectLocation->location_name ?? '-' }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Tracking -->
    <div class="location-card" style="">
        <h3>Location Tracking</h3>
        <div class="location-details">
            <div class="location-item">
                <div class="location-point check-in-point">
                    <i class="material-icons">location_on</i>
                </div>
                <div class="location-info">
                    <h4>Check-in Location</h4>
                    <p>{{ $attendance->projectLocation->location_name ?? '-' }}</p>
                    <div class="coordinates"></div>
                    <div class="accuracy">
                        <i class="material-icons">gps_fixed</i>
                        <span>{{ $attendance->check_in_location ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <div class="location-item">
                <div class="location-point check-out-point">
                    <i class="material-icons">location_on</i>
                </div>
                <div class="location-info">
                    <h4>Check-out Location</h4>
                    <p>{{ $attendance->projectLocation->location_name ?? '-' }}</p>
                    <div class="coordinates"></div>
                    <div class="accuracy">
                        <i class="material-icons">gps_fixed</i>
                        <span>{{ $attendance->check_out_location ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="map-placeholder">
            <div class="map-icon">
                <i class="material-icons">map</i>
            </div>
            <p>Interactive map showing check-in/out locations</p>
            <small>Google Maps integration will be implemented here</small>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="additional-info" >
        <div class="info-card" style="display:none">
            <h3>Approval Information</h3>
            <div class="approval-details">
                <div class="approval-item">
                    <strong>Approved By:</strong>
                    <span>Neha Gupta - Project Manager</span>
                </div>
                <div class="approval-item">
                    <strong>Approval Date:</strong>
                    <span>October 07, 2025 at 07:00 PM</span>
                </div>
                <div class="approval-item">
                    <strong>Status:</strong>
                    <span class="status-approved">Approved</span>
                </div>
            </div>
        </div>

        <div class="info-card">
            <h3>Notes & Remarks</h3>
            <div class="notes-content">
                <p>{{ $attendance->notes ?? '-' }}</p>

                <div class="note-author" style="display:none">
                    <small>Added by: Vikram Singh - Site Supervisor</small>
                    <small>Time: 06:45 PM</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-section">
        <a href="{{ route('supervisor.attendance.edit', $attendance->id) }}" class="btn-primary">
            <i class="material-icons">edit</i>
            Edit Attendance
        </a>
        <button class="btn-secondary" onclick="generateReport()" style="display:none">
            <i class="material-icons">print</i>
            Generate Report
        </button>
        <button class="btn-danger" onclick="deleteAttendance()">
            <i class="material-icons">delete</i>
            Delete Record
        </button>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav { margin-bottom: 20px; }

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

    .attendance-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .employee-info {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .employee-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 24px;
    }

    .employee-info h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .employee-info p {
        margin: 0 0 12px 0;
        color: #666;
    }

    .attendance-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        font-weight: 500;
    }

    .attendance-status {
        text-align: right;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 12px;
    }

    .status-late { background: #fff3e0; color: #f57c00; }

    .working-hours strong {
        display: block;
        font-size: 20px;
        color: #1C1B1F;
    }

    .working-hours small {
        color: #666;
        font-size: 12px;
    }

    .attendance-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .detail-card, .location-card, .info-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .detail-card h3, .location-card h3, .info-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .time-entries {
        display: flex;
        flex-direction: column;
        gap: 24px;
        margin-bottom: 32px;
    }

    .time-entry {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .time-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .time-info h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .time-value {
        font-size: 20px;
        font-weight: 600;
        color: #1C1B1F;
        margin-bottom: 4px;
    }

    .time-status {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .time-status.late { color: #f57c00; }
    .time-status.on-time { color: #2e7d32; }

    .location {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #666;
        font-size: 14px;
    }

    .location i {
        font-size: 16px;
        color: #6750A4;
    }

    .break-info {
        border-top: 1px solid #f0f0f0;
        padding-top: 20px;
    }

    .break-info h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .break-time {
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
        margin-bottom: 4px;
    }

    .work-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-item {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 20px;
        font-weight: 600;
        color: #1C1B1F;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        font-weight: 500;
    }

    .project-assignment {
        border-top: 1px solid #f0f0f0;
        padding-top: 20px;
    }

    .project-assignment h4 {
        margin: 0 0 12px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .project-info strong {
        display: block;
        margin-bottom: 4px;
    }

    .project-info small {
        color: #666;
        font-size: 14px;
    }

    .location-details {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 32px;
    }

    .location-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .location-point {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .check-in-point { background: #4CAF50; }
    .check-out-point { background: #f44336; }

    .location-info h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
    }

    .location-info p {
        margin: 0 0 8px 0;
        color: #333;
    }

    .coordinates {
        font-size: 12px;
        color: #666;
        margin-bottom: 8px;
    }

    .accuracy {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        color: #4CAF50;
    }

    .map-placeholder {
        background: #f0f0f0;
        padding: 40px;
        border-radius: 12px;
        text-align: center;
        color: #666;
    }

    .map-icon i {
        font-size: 48px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .additional-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .approval-details, .notes-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .approval-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .approval-item:last-child { border-bottom: none; }

    .status-approved {
        padding: 4px 12px;
        background: #e8f5e8;
        color: #2e7d32;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .notes-content p {
        margin: 0 0 16px 0;
        line-height: 1.5;
        color: #333;
    }

    .note-author {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding-top: 16px;
        border-top: 1px solid #f0f0f0;
    }

    .note-author small {
        color: #666;
        font-size: 12px;
    }

    .action-section {
        display: flex;
        gap: 16px;
        justify-content: center;
        padding: 32px 0;
        border-top: 1px solid #e0e0e0;
    }

    .btn-primary, .btn-secondary, .btn-danger {
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

    .btn-secondary {
        background: #f5f5f5;
        color: #666;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
        color: #333;
    }

    .btn-danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-danger:hover {
        background: #d32f2f;
        color: white;
    }

    @media (max-width: 768px) {
        .attendance-header { flex-direction: column; gap: 20px; }
        .detail-grid, .additional-info { grid-template-columns: 1fr; }
        .work-stats { grid-template-columns: 1fr; }
        .action-section { flex-direction: column; }
    }
</style>
<script async
    src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initAttendanceMaps">
</script>

<script>
function initAttendanceMaps() {
    // ✅ Get coordinates from backend (Blade)
    const checkInCoords = "{{ $attendance->check_in_location ?? '' }}";
    const checkOutCoords = "{{ $attendance->check_out_location ?? '' }}";

    // Reference the existing map container
    const mapContainer = document.querySelector('.map-placeholder');
    mapContainer.innerHTML = ''; // Clear placeholder text but keep styling

    // Wrapper for maps (preserves layout)
    const wrapper = document.createElement('div');
    wrapper.style.display = 'grid';
    wrapper.style.gridTemplateColumns = 'repeat(auto-fit, minmax(300px, 1fr))';
    wrapper.style.gap = '20px';

    /**
     * Create a map box with title and marker
     */
    const createMapBox = (title, coords, color) => {
        const mapBox = document.createElement('div');
        mapBox.style.border = '1px solid #e0e0e0';
        mapBox.style.borderRadius = '12px';
        mapBox.style.overflow = 'hidden';
        mapBox.style.background = '#fff';
        mapBox.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';

        const header = document.createElement('div');
        header.style.padding = '8px 12px';
        header.style.fontWeight = '600';
        header.style.background = color;
        header.style.color = 'white';
        header.innerText = title;

        const mapDiv = document.createElement('div');
        mapDiv.style.width = '100%';
        mapDiv.style.height = '250px';

        mapBox.appendChild(header);
        mapBox.appendChild(mapDiv);
        wrapper.appendChild(mapBox);

        if (coords) {
            const [lat, lng] = coords.split(',').map(Number);
            const map = new google.maps.Map(mapDiv, {
                zoom: 15,
                center: { lat, lng },
                disableDefaultUI: true,
            });

            new google.maps.Marker({
                position: { lat, lng },
                map,
                title,
                icon: {
                    url: `https://maps.google.com/mapfiles/ms/icons/${color === '#4CAF50' ? 'green' : 'red'}-dot.png`
                }
            });
        } else {
            mapDiv.innerHTML = '<p style="padding:20px;text-align:center;color:#666;">No location data</p>';
        }
    };

    // 🧭 Decide what to show
    if (checkInCoords && checkOutCoords) {
        // ✅ Both maps
        createMapBox('Check-In Location', checkInCoords, '#4CAF50');
        createMapBox('Check-Out Location', checkOutCoords, '#f44336');
    } else if (checkInCoords) {
        // ✅ Only Check-In
        createMapBox('Check-In Location', checkInCoords, '#4CAF50');
    } else if (checkOutCoords) {
        // ✅ Only Check-Out
        createMapBox('Check-Out Location', checkOutCoords, '#f44336');
    } else {
        // ❌ No data
        mapContainer.innerHTML = '<p style="padding:20px;text-align:center;color:#999;">No location data available</p>';
        return;
    }

    mapContainer.appendChild(wrapper);
}
</script>

<script>
function deleteAttendance() {
    if (confirm('Are you sure you want to delete this attendance record?\n\nThis action cannot be undone.')) {
        // Delete attendance logic here
        alert('Attendance record deleted successfully');
        window.location.href = '/supervisor/attendance';
    }
}


</script>
@endpush
