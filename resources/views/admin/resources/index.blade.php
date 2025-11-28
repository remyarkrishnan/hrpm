@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('resources.index.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('resources.index.page_title'))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('resources.index.description') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.resources.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('resources.index.create_resource') }}
        </a>
    </div>
</div>

<!-- Resource Stats -->
<div class="stats-grid">
    <div class="stat-card morning">
        <div class="stat-icon">
            <i class="material-icons">wb_sunny</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['morning'] ?? 25 }}</h3>
            <p>{{ __('resources.index.morning_workers') }}</p>
            <small>{{ __('resources.index.morning_time') }}</small>
        </div>
    </div>

    <div class="stat-card evening">
        <div class="stat-icon">
            <i class="material-icons">brightness_3</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['evening'] ?? 18 }}</h3>
            <p>{{ __('resources.index.evening_workers') }}</p>
            <small>{{ __('resources.index.evening_time') }}</small>
        </div>
    </div>

    <div class="stat-card night">
        <div class="stat-icon">
            <i class="material-icons">bedtime</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['night'] ?? 8 }}</h3>
            <p>{{ __('resources.index.night_workers') }}</p>
            <small>{{ __('resources.index.night_time') }}</small>
        </div>
    </div>

    <div class="stat-card total">
        <div class="stat-icon">
            <i class="material-icons">groups</i>
        </div>
        <div class="stat-info">
            <h3>{{ $stats['total'] ?? 51 }}</h3>
            <p>{{ __('resources.index.total_active') }}</p>
            <small>{{ __('resources.index.all_combined') }}</small>
        </div>
    </div>
</div>

<!-- Resource Table -->
<div class="resources-table-card">
    <h3>{{ __('resources.index.current_allocation') }}</h3>
    <div class="table-responsive">
        <table class="resources-table">
            <thead>
                <tr>
                    <th>{{ __('resources.index.employee') }}</th>
                    <th>{{ __('resources.index.department') }}</th>
                    <th>{{ __('resources.index.project_name') }}</th>
                    <th>{{ __('resources.index.role') }}</th>
                    <th>{{ __('resources.index.allocation_percentage') }}</th>
                    <th>{{ __('resources.index.status') }}</th>
                    <th>{{ __('resources.index.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resources as $resource)
                <tr>
                    <td>
                        <div class="resource-info">
                            <strong>{{ $resource->employee_name }}</strong>
                            <small>{{ $resource->employee_code }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="resource-type resource-{{ $resource->department }}">
                            {{ ucfirst(str_replace('_', ' ', $resource->department)) }}
                        </span>
                    </td>
                    <td>
                        <div class="resource-project">
                            <strong>{{ $resource->project_name }}</strong>
                        </div>
                    </td>
                    <td>{{ $resource->role }}</td>
                    <td>
                        <span class="allocation-badge">{{ $resource->allocation_percentage }}%</span>
                    </td>
                    <td>
                        <span class="status-badge status-active">
                            {{ __('resources.index.active') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.resources.show', $resource->id) }}" class="btn-action" title="{{ __('resources.index.view') }}">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.resources.edit', $resource->id) }}" class="btn-action" title="{{ __('resources.index.edit') }}">
                                <i class="material-icons">edit</i>
                            </a>
                            <button class="btn-action btn-assign" onclick="manageAssignments({{ $resource->id }})" title="{{ __('resources.index.assign_employees') }}">
                                <i class="material-icons">group_add</i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">
                        {{ __('resources.index.no_resources') }}
                    </td>
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

    .btn-primary:hover { 
        background: #5A4A94; 
    }

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

    .resources-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .resources-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .resources-table {
        width: 100%;
        border-collapse: collapse;
    }

    .resources-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .resources-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .resource-info strong {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .resource-info small {
        color: #666;
        font-size: 12px;
    }

    .resource-type {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .resource-project strong {
        display: block;
        margin-bottom: 2px;
    }

    .allocation-badge {
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

    .status-active { 
        background: #e8f5e8; 
        color: #2e7d32; 
    }

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

    .btn-assign:hover { 
        background: #4CAF50; 
    }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    @media (max-width: 768px) {
        .stats-grid { 
            grid-template-columns: 1fr; 
        }
        .header-actions { 
            flex-direction: column;
            width: 100%; 
        }
        .btn-primary { 
            justify-content: center; 
        }
    }
</style>
@endpush

@push('scripts')
<script>
function manageAssignments(resourceId) {
    // TODO: Open assignment management modal
    alert('{{ __("resources.index.assignment_coming_soon") }}');
}
</script>
@endpush
