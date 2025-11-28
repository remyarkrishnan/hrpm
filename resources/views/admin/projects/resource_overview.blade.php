@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('projects.resources_overview.title', ['project' => $project->name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('projects.resources_overview.page_title', ['project' => $project->name]))

@section('content')
<div class="page-header">
    <div>
        <p>{{ __('projects.resources_overview.description') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.projects.index') }}" class="btn-primary">
            <i class="material-icons">arrow_back</i>
            {{ __('projects.resources_overview.back_to_projects') }}
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:#4CAF50;">
            <i class="material-icons">layers</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalSteps ?? 0 }}</h3>
            <p>{{ __('projects.resources_overview.stats.total_steps') }}</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background:#FF9800;">
            <i class="material-icons">timeline</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalSubplans ?? 0 }}</h3>
            <p>{{ __('projects.resources_overview.stats.total_subplans') }}</p>
        </div>
    </div>
</div>

<!-- Project Cards -->
<div class="container-fluid mt-4">
    <div class="shifts-table-card mb-4">
        <h3 class="text-primary mb-3">{{ $project->name }}
            <small class="text-muted">({{ ucfirst($project->status_label ?? $project->status) }})</small>
        </h3>

        @forelse($project->steps as $step)
            <div class="card mb-3 p-3 border-light shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="fw-bold text-dark mb-0">
                        {{ __('projects.resources_overview.step_label', ['number' => $loop->iteration, 'name' => $step->step_name]) }}
                    </h5>
                    <span class="badge bg-info text-dark">{{ ucfirst(__('projects.resources_overview.status.' . $step->status)) }}</span>
                </div>

                @forelse($step->subplans as $subplan)
                    <div class="border rounded p-3 mb-3" style="background:#fafafa;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>{{ $subplan->activity_name }}</strong><br>
                                <small class="text-muted">{{ Str::limit($subplan->description, 100) }}</small>
                            </div>
                            <span class="badge bg-success text-white">
                                {{ __('projects.resources_overview.progress_label') }}: {{ $subplan->progress_percentage ?? 0 }}%
                            </span>
                        </div>

                        <div class="progress mb-3" style="height:6px;">
                            <div class="progress-bar bg-success" style="width: {{ $subplan->progress_percentage ?? 0 }}%;"></div>
                        </div>

                        <table class="shifts-table mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('projects.resources_overview.table.employee') }}</th>
                                    <th>{{ __('projects.resources_overview.table.role') }}</th>
                                    <th>{{ __('projects.resources_overview.table.allocation') }}</th>
                                    <th>{{ __('projects.resources_overview.table.performance') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subplan->allocations as $alloc)
                                    @php
                                        $p = (int)($alloc->allocation_percentage ?? 0);
                                        if ($p >= 80) $perfClass = 'perf-excellent';
                                        elseif ($p >= 50) $perfClass = 'perf-good';
                                        elseif ($p >= 20) $perfClass = 'perf-average';
                                        else $perfClass = 'perf-poor';
                                    @endphp
                                    <tr>
                                        <td>{{ $alloc->employee->name ?? __('projects.resources_overview.not_assigned') }}</td>
                                        <td>{{ ucfirst($alloc->role ?? '-') }}</td>
                                        <td>{{ $alloc->allocation_percentage ?? 0 }}%</td>
                                        <td><span class="status-badge {{ $perfClass }}">{{ $alloc->allocation_percentage ?? 0 }}%</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-muted text-center">{{ __('projects.resources_overview.no_employees_assigned') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @empty
                    <p class="text-muted ms-3">{{ __('projects.resources_overview.no_subplans') }}</p>
                @endforelse
            </div>
        @empty
            <p class="text-muted">{{ __('projects.resources_overview.no_steps') }}</p>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
    .page-header { 
        display:flex; 
        justify-content:space-between; 
        align-items:flex-start; 
        margin-bottom:32px; 
    }
    
    .header-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-primary { 
        display:inline-flex; 
        align-items:center; 
        gap:8px; 
        background:#6750A4; 
        color:white; 
        padding:12px 20px; 
        border-radius:8px; 
        text-decoration:none; 
        font-weight:500; 
        transition:background .2s; 
    }
    
    .btn-primary:hover { 
        background:#5A4A94; 
    }

    .stats-grid { 
        display:grid; 
        grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); 
        gap:20px; 
        margin-bottom:24px; 
    }
    
    .stat-card { 
        background:white; 
        padding:20px; 
        border-radius:12px; 
        box-shadow:0 2px 8px rgba(0,0,0,.08); 
        display:flex; 
        gap:16px; 
        align-items:center; 
    }
    
    .stat-icon { 
        width:56px; 
        height:56px; 
        border-radius:12px; 
        display:flex; 
        align-items:center; 
        justify-content:center; 
        color:white; 
        font-size:24px; 
    }

    .shifts-table-card { 
        background:white; 
        padding:24px; 
        border-radius:12px; 
        box-shadow:0 2px 8px rgba(0,0,0,.06); 
    }
    
    .shifts-table { 
        width:100%; 
        border-collapse:collapse; 
        margin-top:10px; 
    }
    
    .shifts-table th, .shifts-table td { 
        padding:12px; 
        border-bottom:1px solid #eee; 
        text-align:left; 
    }
    
    .shifts-table th {
        font-weight: 600;
        color: #333;
        background: #f8f9fa;
    }
    
    .status-badge { 
        padding:6px 12px; 
        border-radius:12px; 
        font-weight:600; 
        text-transform:none; 
        display:inline-block; 
        font-size: 12px;
    }

    .perf-excellent { 
        background:#E8F5E9; 
        color:#2E7D32; 
    }
    
    .perf-good { 
        background:#E3F2FD; 
        color:#1565C0; 
    }
    
    .perf-average { 
        background:#FFF8E1; 
        color:#F9A825; 
    }
    
    .perf-poor { 
        background:#FFEBEE; 
        color:#C62828; 
    }

    .text-muted { 
        color:#777 !important; 
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            gap: 16px;
        }
        
        .header-actions {
            width: 100%;
            justify-content: flex-start;
        }
        
        .shifts-table {
            font-size: 14px;
        }
        
        .shifts-table th,
        .shifts-table td {
            padding: 8px 4px;
        }
    }
</style>
@endpush
