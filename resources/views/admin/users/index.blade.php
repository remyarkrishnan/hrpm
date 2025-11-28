@extends('layouts.admin')
@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@section('title', __('admin.users.index.Employee Management') . ' - ' . env('COMPANY_NAME', 'Teqin Valley'))
@section('page-title', __('admin.users.index.Employee Management'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <p style="margin: 4px 0 0 0; color: #666;">{{ __('admin.users.index.Manage employee profiles roles and permissions') }}</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <i class="material-icons">add</i>
        {{ __('admin.users.index.Add Employee') }}
    </a>
</div>

<!-- Filters -->
<div class="filters-card">
    <form method="GET" class="filters-form">
        <div class="filter-group">
            <input type="text" name="search" placeholder="{{ __('admin.users.index.Search employees') }}" 
                   value="{{ request('search') }}" class="filter-input">
        </div>

        <div class="filter-group">
            <select name="department" class="filter-select">
                <option value="">{{ __('admin.users.index.All Departments') }}</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                        {{ $dept }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <select name="role" class="filter-select">
                <option value="">{{ __('admin.users.index.All Roles') }}</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                        {{ ucwords(str_replace('-', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <select name="status" class="filter-select">
                <option value="">{{ __('admin.users.index.All Status') }}</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>{{ __('admin.users.index.Active') }}</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>{{ __('admin.users.index.Inactive') }}</option>
            </select>
        </div>

        <button type="submit" class="btn-filter">
            <i class="material-icons">search</i>
            {{ __('admin.users.index.Filter') }}
        </button>

        @if(request()->hasAny(['search', 'department', 'role', 'status']))
            <a href="{{ route('admin.users.index') }}" class="btn-clear">
                <i class="material-icons">clear</i>
                {{ __('admin.users.index.Clear') }}
            </a>
        @endif
    </form>
</div>

<!-- Employees Table -->
<div class="table-card">
    @if($employees->count() > 0)
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('admin.users.index.Employee') }}</th>
                        <th>{{ __('admin.users.index.Employee ID') }}</th>
                        <th>{{ __('admin.users.index.Department') }}</th>
                        <th>{{ __('admin.users.index.Role') }}</th>
                        <th>{{ __('admin.users.index.Joining Date') }}</th>
                        <th>{{ __('admin.users.index.Status') }}</th>
                        <th>{{ __('admin.users.index.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr>
                            <td>
                                <div class="employee-info">
                                    <div class="employee-avatar">
                                        <span>{{ strtoupper(substr($employee->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="employee-details">
                                        <strong>{{ $employee->name }}</strong>
                                        <small>{{ $employee->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="employee-id">{{ $employee->employee_id }}</span>
                            </td>
                            <td>{{ $employee->department }}</td>
                            <td>
                                <span class="role-badge role-{{ $employee->getRoleNames()->first() }}">
                                    {{ ucwords(str_replace('-', ' ', $employee->getRoleNames()->first() ?? 'No Role')) }}
                                </span>
                            </td>
                            <td>{{ $employee->joining_date ? $employee->joining_date->format('M d, Y') : '-' }}</td>
                            <td>
                                <button class="status-toggle" 
                                        onclick="toggleStatus({{ $employee->id }})"
                                        data-status="{{ $employee->status ? 'active' : 'inactive' }}">
                                    <span class="status-indicator {{ $employee->status ? 'active' : 'inactive' }}">
                                        {{ $employee->status ? __('admin.users.index.Active') : __('admin.users.index.Inactive') }}
                                    </span>
                                </button>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.users.show', $employee) }}" class="btn-action view" title="{{ __('admin.users.index.View') }}">
                                        <i class="material-icons">visibility</i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $employee) }}" class="btn-action edit" title="{{ __('admin.users.index.Edit') }}">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button onclick="deleteEmployee({{ $employee->id }})" class="btn-action delete" title="{{ __('admin.users.index.Delete') }}">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $employees->withQueryString()->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="material-icons">people_outline</i>
            <h3>{{ __('admin.users.index.No Employees Found') }}</h3>
            <p>{{ request()->hasAny(['search', 'department', 'role', 'status']) ? __('admin.users.index.No employees match your filters') : __('admin.users.index.Start by adding your first employee') }}</p>
            @if(!request()->hasAny(['search', 'department', 'role', 'status']))
                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                    <i class="material-icons">add</i>
                    {{ __('admin.users.index.Add First Employee') }}
                </a>
            @endif
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
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

    .filters-card {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filters-form {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
        align-items: center;
    }

    .filter-group { flex: 1; min-width: 200px; }

    .filter-input, .filter-select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-input:focus, .filter-select:focus {
        outline: none;
        border-color: #6750A4;
    }

    .btn-filter, .btn-clear {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 16px;
        border-radius: 6px;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-filter {
        background: #6750A4;
        color: white;
        border: none;
        cursor: pointer;
    }

    .btn-clear {
        background: #f5f5f5;
        color: #666;
        border: 1px solid #ddd;
    }

    .table-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-container { overflow-x: auto; }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th, .data-table td {
        padding: 16px;
        text-align: left;
        border-bottom: 1px solid #f0f0f0;
    }

    .data-table th {
        background: #f8f9fa;
        font-weight: 600;
        color: #333;
        font-size: 14px;
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
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .employee-details strong {
        display: block;
        margin-bottom: 2px;
    }

    .employee-details small {
        color: #666;
        font-size: 12px;
    }

    .employee-id {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
    }

    .role-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .role-super-admin { background: #e8f5e8; color: #2e7d32; }
    .role-admin { background: #fff3e0; color: #f57c00; }
    .role-project-manager { background: #e3f2fd; color: #1565c0; }
    .role-employee { background: #f3e5f5; color: #7b1fa2; }
    .role-supervisor { background: #fce4ec; color: #c2185b; }

    .status-toggle {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
    }

    .status-indicator {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-indicator.active {
        background: #e8f5e8;
        color: #2e7d32;
    }

    .status-indicator.inactive {
        background: #ffebee;
        color: #c62828;
    }

    .action-buttons {
        display: flex;
        gap: 4px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }

    .btn-action.view { background: #e3f2fd; color: #1565c0; }
    .btn-action.edit { background: #fff3e0; color: #f57c00; }
    .btn-action.delete { background: #ffebee; color: #c62828; }

    .btn-action:hover { transform: scale(1.1); }
    .btn-action i { font-size: 16px; }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .empty-state h3 {
        margin: 0 0 8px 0;
        color: #333;
    }

    .empty-state p { margin: 0 0 24px 0; }

    .pagination-wrapper {
        padding: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .filters-form { flex-direction: column; }
        .filter-group { width: 100%; min-width: unset; }
        .table-container { font-size: 14px; }
        .employee-info { flex-direction: column; align-items: flex-start; gap: 8px; }
        .action-buttons { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
    async function toggleStatus(employeeId) {
        try {
            const response = await fetch(`/admin/users/${employeeId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("messages.Failed to update status") }}');
            }
        } catch (error) {
            alert('{{ __("messages.Error updating status") }}');
            console.error(error);
        }
    }

    async function deleteEmployee(employeeId) {
        if (!confirm('{{ __("messages.Are you sure you want to delete this employee") }}')) {
            return;
        }

        try {
            const response = await fetch(`/admin/users/${employeeId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                alert(data.message || '{{ __("messages.Failed to delete employee") }}');
            }
        } catch (error) {
            alert('{{ __("messages.Error deleting employee") }}');
            console.error(error);
        }
    }
</script>
@endpush
