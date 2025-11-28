@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('resources.show.title', ['resource' => $resource->name ?? 'Resource', 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('resources.show.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.resources.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('resources.show.back_to_resources') }}
        </a>
    </div>
    <div class="resource-header">
        <div class="resource-info">
            <h2>{{ $resource->name ?? __('resources.show.morning_resource') }}</h2>
            <p>{{ $resource->location ?? config('resources.default_location', 'Site A') }} • {{ $resource->employees_count ?? 15 }} {{ __('resources.show.employees_assigned') }}</p>
            <div class="resource-timing">
                <i class="material-icons">schedule</i>
                <span>{{ $resource->start_time ?? '07:00' }} - {{ $resource->end_time ?? '15:00' }}</span>
                <strong>({{ $resource->working_hours ?? 8 }} {{ __('resources.show.hours') }})</strong>
            </div>
        </div>
        <div class="resource-status">
            <span class="resource-type resource-{{ $resource->type ?? 'morning' }}">
                {{ __('resources.show.resource_types.' . ($resource->type ?? 'morning')) }}
            </span>
            <div class="resource-capacity">
                <strong>{{ $resource->employees_count ?? 15 }}/{{ $resource->max_employees ?? 25 }}</strong>
                <small>{{ __('resources.show.capacity') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="resource-details">
    <!-- Resource Information -->
    <div class="detail-grid">
        <div class="detail-card">
            <h3>{{ __('resources.show.resource_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('resources.show.resource_name') }}:</strong>
                    <span>{{ $resource->name ?? __('resources.show.morning_resource') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.resource_type') }}:</strong>
                    <span class="resource-type resource-{{ $resource->type ?? 'morning' }}">
                        {{ __('resources.show.resource_types.' . ($resource->type ?? 'morning')) }}
                    </span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.start_time') }}:</strong>
                    <span>{{ $resource->start_time ?? '07:00' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.end_time') }}:</strong>
                    <span>{{ $resource->end_time ?? '15:00' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.break_duration') }}:</strong>
                    <span>{{ $resource->break_duration ?? 60 }} {{ __('resources.show.minutes') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.working_hours') }}:</strong>
                    <span>{{ $resource->working_hours ?? 8 }} {{ __('resources.show.hours') }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>{{ __('resources.show.location_assignment') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('resources.show.work_location') }}:</strong>
                    <span>{{ $resource->location ?? config('resources.default_location', 'Site A') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.resource_supervisor') }}:</strong>
                    <span>{{ $resource->supervisor_name ?? __('resources.show.default_supervisor') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.associated_project') }}:</strong>
                    <span>{{ $resource->project_name ?? __('resources.show.default_project') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.max_capacity') }}:</strong>
                    <span>{{ $resource->max_employees ?? 25 }} {{ __('resources.show.employees') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.currently_assigned') }}:</strong>
                    <span class="employee-count">{{ $resource->employees_count ?? 15 }} {{ __('resources.show.employees') }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('resources.show.overtime_allowed') }}:</strong>
                    <span class="overtime-status">{{ $resource->overtime_allowed ? __('resources.show.yes') : __('resources.show.no') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Working Days Schedule -->
    <div class="schedule-section">
        <h3>{{ __('resources.show.working_days_schedule') }}</h3>
        <div class="days-grid">
            @foreach(config('resources.days', []) as $day)
            <div class="day-item {{ in_array($day['value'], $resource->working_days ?? []) ? 'active' : 'off' }}">
                <div class="day-name">{{ $day['label'] }}</div>
                <div class="day-status">{{ in_array($day['value'], $resource->working_days ?? []) ? __('resources.show.working') : __('resources.show.off') }}</div>
                <div class="day-hours">{{ in_array($day['value'], $resource->working_days ?? []) ? ($day['hours'] ?? '8h') : '-' }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Assigned Employees -->
    <div class="employees-section">
        <div class="employees-header">
            <h3>{{ __('resources.show.assigned_employees', ['count' => $resource->employees_count ?? 15]) }}</h3>
            <button class="btn-primary" onclick="assignMoreEmployees()">
                <i class="material-icons">person_add</i>
                {{ __('resources.show.assign_more') }}
            </button>
        </div>

        <div class="employees-grid">
            @forelse($resource->employees ?? [] as $employee)
            <div class="employee-card">
                <div class="employee-avatar">{{ substr($employee->name, 0, 2) }}</div>
                <div class="employee-info">
                    <h4>{{ $employee->name }}</h4>
                    <p>{{ $employee->role ?? $employee->position }}</p>
                    <small>{{ $employee->employee_code ?? 'EMP-' . $employee->id }}</small>
                </div>
                <div class="employee-role">{{ $employee->role ?? __('resources.show.worker') }}</div>
                <div class="employee-actions">
                    <button class="btn-action btn-remove" onclick="removeEmployee({{ $employee->id }})">
                        <i class="material-icons">remove</i>
                    </button>
                </div>
            </div>
            @empty
            <div class="employee-card placeholder">
                <div class="employee-avatar">+</div>
                <div class="employee-info">
                    <h4>{{ __('resources.show.no_employees_yet') }}</h4>
                    <p>{{ __('resources.show.assign_to_start') }}</p>
                </div>
                <div class="employee-actions">
                    <button class="btn-action" onclick="assignMoreEmployees()">
                        <i class="material-icons">add</i>
                    </button>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Resource Description -->
    @if($resource->description)
    <div class="description-section">
        <h3>{{ __('resources.show.resource_description') }}</h3>
        <div class="description-content">
            <p>{{ $resource->description }}</p>
        </div>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="action-section">
        <div class="resource-actions">
            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn-primary">
                <i class="material-icons">edit</i>
                {{ __('resources.show.edit_resource') }}
            </a>
            <button class="btn-secondary" onclick="duplicateResource()">
                <i class="material-icons">content_copy</i>
                {{ __('resources.show.duplicate_resource') }}
            </button>
        </div>

        <div class="other-actions">
            <button class="btn-success" onclick="activateResource()">
                <i class="material-icons">play_arrow</i>
                {{ __('resources.show.activate_resource') }}
            </button>
            <button class="btn-danger" onclick="deleteResource()">
                <i class="material-icons">delete</i>
                {{ __('resources.show.delete_resource') }}
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
    alert('{{ __("resources.show.employee_assignment_todo") }}');
}

function removeEmployee(employeeId) {
    if (confirm('{{ __("resources.show.remove_confirm") }}')) {
        alert('{{ __("resources.show.employee_removal_todo") }}');
    }
}

function duplicateResource() {
    if (confirm('{{ __("resources.show.duplicate_confirm") }}')) {
        alert('{{ __("resources.show.duplicate_todo") }}');
    }
}

function activateResource() {
    alert('{{ __("resources.show.activation_todo") }}');
}

function deleteResource() {
    if (confirm('{{ __("resources.show.delete_confirm") }}')) {
        fetch(`/admin/resources/{{ $resource->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("resources.show.delete_success") }}');
                window.location.href = '/admin/resources';
            } else {
                alert('{{ __("resources.show.delete_error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("resources.show.delete_error") }}');
        });
    }
}
</script>
@endpush
