@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('project_locations.index.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('project_locations.index.page_title'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <p style="margin: 4px 0 0 0; color: #666;">{{ __('project_locations.index.description') }}</p>
    </div>
    <a href="{{ route('admin.project-locations.create') }}" class="btn-primary">
        <i class="material-icons">add</i>
        {{ __('project_locations.index.new_location') }}
    </a>
</div>

<!-- Filters -->
<div class="filters-card">
    <form method="GET" class="filters-form">
        <div class="filter-group">
            <input type="text" name="search" placeholder="{{ __('project_locations.index.search_placeholder') }}" 
                   value="{{ request('search') }}" class="filter-input">
        </div>

        <div class="filter-group">
            <select name="project_id" class="filter-select">
                <option value="">{{ __('global.select_option') }}</option>
                @foreach($projects as $prj)
                    <option value="{{ $prj->id }}" {{ request('project_id') == $prj->id ? 'selected' : '' }}>
                        {{ $prj->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-filter">
            <i class="material-icons">search</i>
            {{ __('project_locations.index.filter_button') }}
        </button>

        @if(request()->hasAny(['search', 'project_id']))
            <a href="{{ route('admin.project-locations.index') }}" class="btn-clear">
                <i class="material-icons">clear</i>
                {{ __('project_locations.index.clear_button') }}
            </a>
        @endif
    </form>
</div>

<!-- Locations Table -->
<div class="table-card">
    @if($locations->count() > 0)
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('project_locations.index.table.project') }}</th>
                        <th>{{ __('project_locations.index.table.location_name') }}</th>
                        <th>{{ __('project_locations.index.table.city') }}</th>
                        <th>{{ __('project_locations.index.table.state') }}</th>
                        <th style="display:none">{{ __('project_locations.index.table.country') }}</th>
                        <th>{{ __('project_locations.index.table.postal_code') }}</th>
                        <th style="display:none">{{ __('project_locations.index.table.latitude') }}</th>
                        <th style="display:none">{{ __('project_locations.index.table.longitude') }}</th>
                        <th style="display:none">{{ __('project_locations.index.table.address') }}</th>
                        <th>{{ __('project_locations.index.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($locations as $loc)
                        <tr>
                            <td>{{ $loc->project->name }}</td>
                            <td>{{ $loc->location_name }}</td>
                            <td>{{ $loc->city }}</td>
                            <td>{{ $loc->state }}</td>
                            <td style="display:none">{{ $loc->country ?? '' }}</td>
                            <td>{{ $loc->postal_code }}</td>
                            <td style="display:none">{{ $loc->latitude }}</td>
                            <td style="display:none">{{ $loc->longitude }}</td>
                            <td style="display:none">{{ $loc->address ?? '' }}</td>
                            
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.project-locations.edit', $loc) }}" class="btn-action edit" title="{{ __('project_locations.index.edit_tooltip') }}">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button onclick="deleteLocation({{ $loc->id }})" class="btn-action delete" title="{{ __('project_locations.index.delete_tooltip') }}">
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
            {{ $locations->withQueryString()->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="material-icons">location_off</i>
            <h3>{{ __('project_locations.index.no_locations') }}</h3>
            <p>
                @if(request()->hasAny(['search', 'project_id']))
                    {{ __('project_locations.index.no_locations_filtered') }}
                @else
                    {{ __('project_locations.index.no_locations_start') }}
                @endif
            </p>
            @if(!request()->hasAny(['search', 'project_id']))
                <a href="{{ route('admin.project-locations.create') }}" class="btn-primary">
                    <i class="material-icons">add</i>
                    {{ __('project_locations.index.add_first') }}
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
        .action-buttons { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
async function deleteLocation(locId) {
    if (!confirm('{{ __("project_locations.index.delete_confirm") }}')) {
        return;
    }

    const confirmation = prompt('{{ __("project_locations.index.delete_confirm_text") }}');
    if (confirmation !== 'DELETE') {
        alert('{{ __("project_locations.index.delete_cancelled") }}');
        return;
    }

    try {
        const response = await fetch(`/admin/project-locations/${locId}`, {
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
            alert(data.message || '{{ __("project_locations.index.delete_error") }}');
        }
    } catch (error) {
        alert('{{ __("project_locations.index.delete_network_error") }}');
        console.error(error);
    }
}
</script>
@endpush
