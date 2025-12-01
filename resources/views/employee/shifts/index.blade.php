@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee/shifts/index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/shifts/index.title'))

@section('content')
<div class="page-header">
    <div>
        <h2>{{ __('employee/shifts/index.title') }}</h2>
        <p>{{ __('employee/shifts/index.subtitle') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.shifts.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('employee/shifts/common.actions.create') }}
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card morning">
        <div class="stat-icon">
            <i class="material-icons">wb_sunny</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['morning'] ?? 25 }}</h3>
            <p>{{ __('employee/shifts/index.stats.morning') }}</p>
            <small>07:00 AM - 03:00 PM</small>
        </div>
    </div>

    <div class="stat-card evening">
        <div class="stat-icon">
            <i class="material-icons">brightness_3</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['evening'] ?? 18 }}</h3>
            <p>{{ __('employee/shifts/index.stats.evening') }}</p>
            <small>03:00 PM - 11:00 PM</small>
        </div>
    </div>

    <div class="stat-card night">
        <div class="stat-icon">
            <i class="material-icons">bedtime</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['night'] ?? 8 }}</h3>
            <p>{{ __('employee/shifts/index.stats.night') }}</p>
            <small>11:00 PM - 07:00 AM</small>
        </div>
    </div>

    <div class="stat-card total">
        <div class="stat-icon">
            <i class="material-icons">groups</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total'] ?? 51 }}</h3>
            <p>{{ __('employee/shifts/index.stats.total') }}</p>
            <small>{{ __('employee/shifts/index.stats.combined') }}</small>
        </div>
    </div>
</div>

<div class="shifts-table-card">
    <h3>{{ __('employee/shifts/index.table.title') }}</h3>
    <div class="table-responsive">
        <table class="shifts-table">
            <thead>
                <tr>
                    <th>{{ __('employee/shifts/common.labels.name') }}</th>
                    <th>{{ __('employee/shifts/common.labels.type') }}</th>
                    <th>{{ __('employee/shifts/index.table.time') }}</th>
                    <th>{{ __('employee/shifts/common.labels.location') }}</th>
                    <th>{{ __('employee/shifts/common.labels.employees') }}</th>
                    <th>{{ __('employee/shifts/common.labels.status') }}</th>
                    <th>{{ __('employee/shifts/common.actions.view') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                <tr>
                    <td>
                        <div class="shift-info">
                            <strong>{{ $shift->name }}</strong>
                            <small>{{ $shift->location }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="shift-type shift-{{ $shift->type }}">
                            {{ __('employee/shifts/common.types.' . $shift->type) }}
                        </span>
                    </td>
                    <td>
                        <div class="shift-time">
                            <strong>{{ $shift->start_time }} - {{ $shift->end_time }}</strong>
                            <small>{{ \Carbon\Carbon::parse($shift->start_time)->diffForHumans(\Carbon\Carbon::parse($shift->end_time), true) }}</small>
                        </div>
                    </td>
                    <td>{{ $shift->location }}</td>
                    <td>
                        <span class="employee-count">{{ $shift->employees_count }} {{ __('employee/shifts/common.units.workers') }}</span>
                    </td>
                    <td>
                        <span class="status-badge status-active">Active</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.shifts.show', $shift->id) }}" class="btn-action" title="{{ __('employee/shifts/common.actions.view') }}">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.shifts.edit', $shift->id) }}" class="btn-action" title="{{ __('employee/shifts/common.actions.edit') }}">
                                <i class="material-icons">edit</i>
                            </a>
                            <button class="btn-action btn-assign" onclick="assignEmployees({{ $shift->id }})" title="{{ __('employee/shifts/common.actions.assign') }}">
                                <i class="material-icons">group_add</i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('employee/shifts/index.table.empty') }}</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="schedule-card">
    <h3>{{ __('employee/shifts/index.schedule.title') }}</h3>
    <div class="schedule-grid">
        <div class="schedule-header">
            <div class="time-slot">{{ __('employee/shifts/index.schedule.time') }}</div>
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
            <div class="day-slot">{{ $day }}</div>
            @endforeach
        </div>

        <div class="schedule-row">
            <div class="time-slot">07:00 - 15:00</div>
            @for($i=0; $i<6; $i++)
                <div class="shift-slot morning-shift">{{ $stats['morning'] ?? 25 }} {{ __('employee/shifts/common.units.workers') }}</div>
            @endfor
            <div class="shift-slot off">{{ __('employee/shifts/index.schedule.off') }}</div>
        </div>

        <div class="schedule-row">
            <div class="time-slot">15:00 - 23:00</div>
            @for($i=0; $i<6; $i++)
                <div class="shift-slot evening-shift">{{ $stats['evening'] ?? 18 }} {{ __('employee/shifts/common.units.workers') }}</div>
            @endfor
            <div class="shift-slot off">{{ __('employee/shifts/index.schedule.off') }}</div>
        </div>

        <div class="schedule-row">
            <div class="time-slot">23:00 - 07:00</div>
            @for($i=0; $i<5; $i++)
                <div class="shift-slot night-shift">{{ $stats['night'] ?? 8 }} {{ __('employee/shifts/common.units.workers') }}</div>
            @endfor
            <div class="shift-slot off">{{ __('employee/shifts/index.schedule.off') }}</div>
            <div class="shift-slot off">{{ __('employee/shifts/index.schedule.off') }}</div>
        </div>
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
    alert("{{ __('employee/shifts/common.messages.assign_todo') }}");
}
</script>
@endpush
