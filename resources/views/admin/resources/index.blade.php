@extends('layouts.admin')

@section('title', 'Resource Management - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Resource Management')

@section('content')
<div class="page-header">
    <div>
       
        <p>Manage resources for construction sites</p>
    </div>
    <div class="header-actions" style="display:none">
        <a href="{{ route('admin.resources.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            Create Resource Allocation
        </a>
    </div>
</div>

<!-- Shift Stats -->
<div class="stats-grid" style="display:none">
    <div class="stat-card morning">
        <div class="stat-icon">
            <i class="material-icons">wb_sunny</i>
        </div>
        <div class="stat-info">
            <h3>25</h3>
            <p>Morning Shift Workers</p>
            <small>07:00 - 15:00</small>
        </div>
    </div>

    <div class="stat-card evening">
        <div class="stat-icon">
            <i class="material-icons">brightness_3</i>
        </div>
        <div class="stat-info">
            <h3>18</h3>
            <p>Evening Shift Workers</p>
            <small>15:00 - 23:00</small>
        </div>
    </div>

    <div class="stat-card night">
        <div class="stat-icon">
            <i class="material-icons">bedtime</i>
        </div>
        <div class="stat-info">
            <h3>8</h3>
            <p>Night Shift Workers</p>
            <small>23:00 - 07:00</small>
        </div>
    </div>

    <div class="stat-card total">
        <div class="stat-icon">
            <i class="material-icons">groups</i>
        </div>
        <div class="stat-info">
            <h3>51</h3>
            <p>Total Active Shifts</p>
            <small>All shifts combined</small>
        </div>
    </div>
</div>

<!-- Shift Schedule Table -->
<div class="shifts-table-card">
    <h3>Current Resource Allocation</h3>
    <div class="table-responsive">
        <table class="shifts-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Project Name</th>
                    <th>Role</th>
                    <th>Allocation Percentage</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr>
                    <td>
                        <div class="shift-info">
                            <strong>{{ $resource->employee_name }}</strong>
                            <small>{{ $resource->employee_code }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="shift-type shift-{{ $resource->department }}">
                            {{ ucfirst(str_replace('_', ' ', $resource->department)) }}
                        </span>
                    </td>
                    <td>
                        <div class="shift-time">
                            <strong>{{ $resource->project_name }}</strong>
                           
                    </td>
                    <td>{{ $resource->role  }}</td>
                    <td>
                        <span class="employee-count">{{ $resource->allocation_percentage }} </span>
                    </td>
                    <td>
                        <span class="status-badge status-active">Active</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a onclick="assignEmployees({{ $resource->id }})" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a onclick="assignEmployees({{ $resource->id }})" class="btn-action">
                                <i class="material-icons">edit</i>
                            </a>
                            <button class="btn-action btn-assign" onclick="assignEmployees({{ $resource->id }})">
                                <i class="material-icons">group_add</i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No resources configured yet</td>
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

    .stat-card.morning .stat-icon { background: #FF9800; }
    .stat-card.evening .stat-icon { background: #673AB7; }
    .stat-card.night .stat-icon { background: #3F51B5; }
    .stat-card.total .stat-icon { background: #4CAF50; }

    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        color: #333;
        font-size: 14px;
        font-weight: 500;
    }

    .stat-info small {
        color: #666;
        font-size: 12px;
    }

    .shifts-table-card, .schedule-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .shifts-table-card h3, .schedule-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .shifts-table {
        width: 100%;
        border-collapse: collapse;
    }

    .shifts-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .shifts-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .shift-info strong {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .shift-info small {
        color: #666;
        font-size: 12px;
    }

    .shift-type {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .shift-morning { background: #fff3e0; color: #f57c00; }
    .shift-evening { background: #f3e5f5; color: #7b1fa2; }
    .shift-night { background: #e8eaf6; color: #3949ab; }
    .shift-flexible { background: #e0f2f1; color: #00695c; }

    .shift-time strong {
        display: block;
        margin-bottom: 2px;
    }

    .shift-time small {
        color: #666;
        font-size: 12px;
    }

    .employee-count {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-active { background: #e8f5e8; color: #2e7d32; }

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
        border: none;
        cursor: pointer;
    }

    .btn-action:hover {
        background: #6750A4;
        color: white;
    }

    .btn-assign:hover { background: #4CAF50; }

    .schedule-grid {
        display: grid;
        grid-template-columns: 120px repeat(7, 1fr);
        gap: 1px;
        background: #e0e0e0;
        border-radius: 8px;
        overflow: hidden;
    }

    .schedule-header {
        display: contents;
    }

    .schedule-row {
        display: contents;
    }

    .time-slot, .day-slot, .shift-slot {
        background: white;
        padding: 16px 12px;
        text-align: center;
        font-weight: 500;
    }

    .time-slot, .day-slot {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .shift-slot {
        font-size: 12px;
        font-weight: 600;
    }

    .morning-shift {
        background: #fff3e0;
        color: #f57c00;
    }

    .evening-shift {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .night-shift {
        background: #e8eaf6;
        color: #3949ab;
    }

    .shift-slot.off {
        background: #f5f5f5;
        color: #999;
    }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .schedule-grid { 
            grid-template-columns: 80px repeat(7, 1fr);
            font-size: 10px;
        }
        .time-slot, .day-slot, .shift-slot { padding: 8px 4px; }
    }
</style>
@endpush

@push('scripts')
<script>
function assignEmployees(shiftId) {
    // TODO: Open modal or redirect to employee assignment page
    alert('Coming soon');
}
</script>
@endpush