@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee/shifts/show.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/shifts/show.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.shifts.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/shifts/common.actions.back') }}
        </a>
    </div>
    <div class="shift-header">
        <div class="shift-info">
            <h2>{{ $shift->name ?? 'Morning Shift' }}</h2>
            <p>{{ $shift->location ?? 'Site A' }} • {{ $shift->employees_count ?? 15 }} {{ __('employee/shifts/common.labels.employees') }}</p>
            <div class="shift-timing">
                <i class="material-icons">schedule</i>
                <span>{{ $shift->start_time ?? '07:00' }} - {{ $shift->end_time ?? '15:00' }}</span>
                <strong>(8 {{ __('employee/shifts/common.units.hours') }})</strong>
            </div>
        </div>
        <div class="shift-status">
            <span class="shift-type shift-{{ $shift->type ?? 'morning' }}">
                {{ __('employee/shifts/common.types.' . ($shift->type ?? 'morning')) }}
            </span>
            <div class="shift-capacity">
                <strong>{{ $shift->employees_count ?? 15 }}/{{ $shift->max_employees ?? 25 }}</strong>
                <small>{{ __('employee/shifts/common.labels.capacity') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="shift-details">
    <div class="detail-grid">
        <div class="detail-card">
            <h3>{{ __('employee/shifts/show.info_card') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.name') }}:</strong>
                    <span>{{ $shift->name ?? 'Morning Shift' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.type') }}:</strong>
                    <span class="shift-type shift-{{ $shift->type ?? 'morning' }}">
                        {{ __('employee/shifts/common.types.' . ($shift->type ?? 'morning')) }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.start_time') }}:</strong>
                    <span>{{ $shift->start_time ?? '07:00 AM' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.end_time') }}:</strong>
                    <span>{{ $shift->end_time ?? '15:00 PM' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.break_duration') }}:</strong>
                    <span>{{ $shift->break_duration ?? 60 }} {{ __('employee/shifts/common.units.minutes') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.total_hours') }}:</strong>
                    <span>{{ $shift->working_hours ?? 8 }} {{ __('employee/shifts/common.units.hours') }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>{{ __('employee/shifts/show.location_card') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.location') }}:</strong>
                    <span>{{ $shift->location ?? 'Site A - Gurgaon' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.supervisor') }}:</strong>
                    <span>{{ $shift->supervisor ?? 'Rajesh Kumar' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.project') }}:</strong>
                    <span>{{ $shift->project ?? 'Residential Complex - Phase 2' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/common.labels.max_employees') }}:</strong>
                    <span>{{ $shift->max_employees ?? 25 }} {{ __('employee/shifts/common.units.employees') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/show.labels.current_assigned') }}:</strong>
                    <span class="employee-count">{{ $shift->employees_count ?? 15 }} {{ __('employee/shifts/common.units.employees') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/shifts/show.labels.overtime') }}:</strong>
                    <span class="overtime-status">{{ $shift->overtime_allowed ? __('employee/shifts/show.labels.yes') : __('employee/shifts/show.labels.no') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="schedule-section">
        <h3>{{ __('employee/shifts/show.schedule_card') }}</h3>
        <div class="days-grid">
            <div class="day-item active">
                <div class="day-name">Mon</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">8h</div>
            </div>
            <div class="day-item active">
                <div class="day-name">Tue</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">8h</div>
            </div>
            <div class="day-item active">
                <div class="day-name">Wed</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">8h</div>
            </div>
            <div class="day-item active">
                <div class="day-name">Thu</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">8h</div>
            </div>
            <div class="day-item active">
                <div class="day-name">Fri</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">8h</div>
            </div>
            <div class="day-item active">
                <div class="day-name">Sat</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.working') }}</div>
                <div class="day-hours">6h</div>
            </div>
            <div class="day-item off">
                <div class="day-name">Sun</div>
                <div class="day-status">{{ __('employee/shifts/show.labels.off') }}</div>
                <div class="day-hours">-</div>
            </div>
        </div>
    </div>

    <div class="employees-section">
        <div class="employees-header">
            <h3>{{ __('employee/shifts/show.employees_card', ['count' => $shift->employees_count ?? 15]) }}</h3>
            <button class="btn-primary" onclick="assignMoreEmployees()">
                <i class="material-icons">person_add</i>
                {{ __('employee/shifts/show.assign_more') }}
            </button>
        </div>

        <div class="employees-grid">
            <div class="employee-card">
                <div class="employee-avatar">RK</div>
                <div class="employee-info">
                    <h4>Rajesh Kumar</h4>
                    <p>Site Engineer</p>
                    <small>EMP-001</small>
                </div>
                <div class="employee-role">Supervisor</div>
                <div class="employee-actions">
                    <button class="btn-action btn-remove" onclick="removeEmployee(1)">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>

            <div class="employee-card">
                <div class="employee-avatar">PS</div>
                <div class="employee-info">
                    <h4>Priya Singh</h4>
                    <p>Construction Worker</p>
                    <small>EMP-002</small>
                </div>
                <div class="employee-role">Worker</div>
                <div class="employee-actions">
                    <button class="btn-action btn-remove" onclick="removeEmployee(2)">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>

            <div class="employee-card">
                <div class="employee-avatar">AS</div>
                <div class="employee-info">
                    <h4>Amit Sharma</h4>
                    <p>Safety Officer</p>
                    <small>EMP-003</small>
                </div>
                <div class="employee-role">Safety</div>
                <div class="employee-actions">
                    <button class="btn-action btn-remove" onclick="removeEmployee(3)">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>

            <div class="employee-card placeholder">
                <div class="employee-avatar">+</div>
                <div class="employee-info">
                    <h4>{{ __('employee/shifts/show.more_employees', ['count' => 12]) }}</h4>
                    <p>{{ __('employee/shifts/show.click_view') }}</p>
                </div>
                <div class="employee-actions">
                    <button class="btn-action" onclick="viewAllEmployees()">
                        <i class="material-icons">list</i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(isset($shift->description))
    <div class="description-section">
        <h3>{{ __('employee/shifts/show.desc_card') }}</h3>
        <div class="description-content">
            <p>{{ $shift->description ?? 'This shift handles primary construction activities including foundation work, structural assembly, and site preparation. All safety protocols must be followed strictly.' }}</p>
        </div>
    </div>
    @endif

    <div class="action-section">
        <div class="shift-actions">
            <a href="{{ route('admin.shifts.edit', $shift->id ?? 1) }}" class="btn-primary">
                <i class="material-icons">edit</i>
                {{ __('employee/shifts/common.actions.edit') }}
            </a>
            <button class="btn-secondary" onclick="duplicateShift()">
                <i class="material-icons">content_copy</i>
                {{ __('employee/shifts/common.actions.duplicate') }}
            </button>
        </div>

        <div class="other-actions">
            <button class="btn-success" onclick="activateShift()">
                <i class="material-icons">play_arrow</i>
                {{ __('employee/shifts/common.actions.activate') }}
            </button>
            <button class="btn-danger" onclick="deleteShift()">
                <i class="material-icons">delete</i>
                {{ __('employee/shifts/common.actions.delete') }}
            </button>
        </div>
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

    .shift-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .shift-info h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .shift-info p {
        margin: 0 0 12px 0;
        color: #666;
    }

    .shift-timing {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        font-weight: 500;
    }

    .shift-status {
        text-align: right;
    }

    .shift-type {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 12px;
    }

    .shift-morning { background: #fff3e0; color: #f57c00; }
    .shift-evening { background: #f3e5f5; color: #7b1fa2; }
    .shift-night { background: #e8eaf6; color: #3949ab; }
    .shift-flexible { background: #e0f2f1; color: #00695c; }

    .shift-capacity strong {
        display: block;
        font-size: 20px;
        color: #1C1B1F;
    }

    .shift-capacity small {
        color: #666;
        font-size: 12px;
    }

    .shift-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .detail-card, .schedule-section, .employees-section, .description-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .detail-card h3, .schedule-section h3, .employees-section h3, .description-section h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child { border-bottom: none; }

    .employee-count {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .overtime-status {
        color: #4CAF50;
        font-weight: 600;
    }

    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
    }

    .day-item {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }

    .day-item.active {
        background: #e8f5e8;
        border-color: #4CAF50;
    }

    .day-item.off {
        background: #ffebee;
        border-color: #f44336;
        opacity: 0.6;
    }

    .day-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .day-status {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }

    .day-hours {
        font-size: 12px;
        font-weight: 600;
        color: #6750A4;
    }

    .employees-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .employees-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .employee-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .employee-card.placeholder {
        opacity: 0.6;
        border-style: dashed;
    }

    .employee-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
    }

    .employee-info {
        flex: 1;
    }

    .employee-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .employee-info p {
        margin: 0 0 2px 0;
        color: #666;
        font-size: 14px;
    }

    .employee-info small {
        color: #999;
        font-size: 12px;
    }

    .employee-role {
        padding: 4px 8px;
        background: #e3f2fd;
        color: #1565c0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .employee-actions {
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

    .btn-remove:hover { background: #f44336; }

    .description-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #6750A4;
    }

    .description-content p {
        margin: 0;
        line-height: 1.6;
        color: #333;
    }

    .action-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        flex-wrap: wrap;
        gap: 20px;
    }

    .shift-actions, .other-actions {
        display: flex;
        gap: 16px;
    }

    .btn-primary, .btn-secondary, .btn-success, .btn-danger {
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

    .btn-success {
        background: #4CAF50;
        color: white;
    }

    .btn-success:hover { background: #45a049; }

    .btn-danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-danger:hover {
        background: #d32f2f;
        color: white;
    }

    @media (max-width: 768px) {
        .shift-header { flex-direction: column; gap: 20px; }
        .detail-grid, .employees-grid { grid-template-columns: 1fr; }
        .days-grid { grid-template-columns: repeat(7, 1fr); font-size: 12px; }
        .action-section { flex-direction: column; }
        .shift-actions, .other-actions { width: 100%; }
        .btn-primary, .btn-secondary, .btn-success, .btn-danger { width: 100%; justify-content: center; }
        .employees-header { flex-direction: column; gap: 16px; }
    }
</style>
@endpush

@push('scripts')
<script>
function assignMoreEmployees() {
    // TODO: Open employee assignment modal
    alert('Employee assignment functionality will be implemented');
}

function removeEmployee(employeeId) {
    if (confirm('Are you sure you want to remove this employee from the shift?')) {
        // TODO: Implement employee removal
        alert('Employee removal functionality will be implemented');
    }
}

function viewAllEmployees() {
    // TODO: Open detailed employee list modal
    alert('Employee list view functionality will be implemented');
}

function duplicateShift() {
    if (confirm('Create a copy of this shift?')) {
        // TODO: Implement shift duplication
        alert('Shift duplication functionality will be implemented');
    }
}

function activateShift() {
    // TODO: Implement shift activation
    alert('Shift activation functionality will be implemented');
}

function deleteShift() {
    if (confirm('Are you sure you want to delete this shift?\n\nThis action cannot be undone and will remove all employee assignments.')) {
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
                alert('Shift deleted successfully');
                window.location.href = '/admin/shifts';
            } else {
                alert('Error deleting shift');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting shift');
        });
    }
}
</script>
@endpush