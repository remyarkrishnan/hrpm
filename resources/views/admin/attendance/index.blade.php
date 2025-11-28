@extends('layouts.admin')

@section('title', 'Attendance Management - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Attendance Management')

@section('content')
<div class="page-header">
    <div>
       
        <p>Track employee attendance with GPS location validation</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.attendance.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            Manual Entry
        </a>
    </div>
</div>

<!-- Attendance Stats -->
<div class="stats-grid">
    <div class="stat-card present">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $attendanceCounts->present ?? 0 }}</h3>
            <p>Present Today</p>
        </div>
    </div>

    <div class="stat-card absent">
        <div class="stat-icon">
            <i class="material-icons">cancel</i>
        </div>
        <div class="stat-info">
            <h3>{{ $attendanceCounts->absent ?? 0 }}</h3>
            <p>Absent Today</p>
        </div>
    </div>

    <div class="stat-card late">
        <div class="stat-icon">
            <i class="material-icons">schedule</i>
        </div>
        <div class="stat-info">
            <h3>{{ $attendanceCounts->late ?? 0 }}</h3>
            <p>Late Arrivals</p>
        </div>
    </div>

    <div class="stat-card on-leave">
        <div class="stat-icon">
            <i class="material-icons">event_available</i>
        </div>
        <div class="stat-info">
            <h3>{{ $attendanceCounts->leave_count ?? 0 }}</h3>
            <p>On Leave</p>
        </div>
    </div>
</div>

<!-- Attendance Table -->
<div class="attendance-table-card">
    <h3>Today's Attendance</h3>
    <div class="table-responsive">
        <table class="attendance-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Project</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Working Hours</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $attendance)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div>
                                <strong>{{ $attendance->user->name ?? '-' }}</strong>
                                <small>{{ $attendance->user->employee_code }}</small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $attendance->project->name ?? '-' }}</td>
                    <td>{{ $attendance->projectLocation->location_name ?? '-' }}</td>
                    <td>{{ $attendance->date->format('d-m-Y') }}</td>
                    <td>{{ optional($attendance->check_in)->format('h:i A') ?? '-' }}</td>
                    <td>{{ optional($attendance->check_out)->format('h:i A') ?? '-' }}</td>
                    <td> @if($attendance->work_hours)
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
    @endif</td>
                    <td>
                        <span class="status-badge status-{{ $attendance->status }}">
                            {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                        </span>
                    </td>
                    <td>{{ $attendance->notes ?? '-' }}</td>
                    
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.attendance.show', $attendance->id) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.attendance.edit', $attendance->id) }}" class="btn-action">
                                <i class="material-icons">edit</i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No attendance records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .page-header p {
        margin: 0;
        color: #666;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-primary:hover { background: #5A4A94; }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .stat-card.present .stat-icon { background: #4CAF50; }
    .stat-card.absent .stat-icon { background: #f44336; }
    .stat-card.late .stat-icon { background: #FF9800; }
    .stat-card.on-leave .stat-icon { background: #2196F3; }

    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        color: #666;
        font-size: 14px;
    }

    .attendance-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .attendance-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .attendance-table {
        width: 100%;
        border-collapse: collapse;
    }

    .attendance-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .attendance-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .employee-info strong {
        display: block;
        margin-bottom: 2px;
    }

    .employee-info small {
        color: #666;
        font-size: 12px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-present { background: #e8f5e8; color: #2e7d32; }
    .status-absent { background: #ffebee; color: #c62828; }
    .status-late { background: #fff3e0; color: #f57c00; }
    .status-half-day { background: #e3f2fd; color: #1565c0; }

    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f5f5f5;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 16px;
    }

    .btn-action:hover {
        background: #6750A4;
        color: white;
    }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; gap: 16px; }
    }
</style>
@endpush