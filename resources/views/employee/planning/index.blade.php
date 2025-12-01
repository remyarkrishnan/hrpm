@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/planning/index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/planning/index.title'))

@section('content')
<div class="page-header">
    <div>
        <h2>{{ __('employee/planning/index.title') }}</h2>
        <p>{{ __('employee/planning/index.subtitle') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.planning.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('employee/planning/common.actions.create') }}
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card active">
        <div class="stat-icon">
            <i class="material-icons">engineering</i>
        </div>
        <div class="stat-info">
            <h3>8</h3>
            <p>{{ __('employee/planning/index.stats.active') }}</p>
            <small>{{ __('employee/planning/common.status.in_progress') }}</small>
        </div>
    </div>

    <div class="stat-card milestones">
        <div class="stat-icon">
            <i class="material-icons">flag</i>
        </div>
        <div class="stat-info">
            <h3>156</h3>
            <p>{{ __('employee/planning/index.stats.milestones') }}</p>
            <small>Across all projects</small>
        </div>
    </div>

    <div class="stat-card completed">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>89</h3>
            <p>{{ __('employee/planning/index.stats.completed') }}</p>
            <small>This month</small>
        </div>
    </div>

    <div class="stat-card progress">
        <div class="stat-icon">
            <i class="material-icons">trending_up</i>
        </div>
        <div class="stat-info">
            <h3>74%</h3>
            <p>{{ __('employee/planning/index.stats.avg_progress') }}</p>
            <small>All active projects</small>
        </div>
    </div>
</div>

<div class="phases-overview">
    <h3>{{ __('employee/planning/phases.title') }}</h3>
    <div class="phases-grid">
        <div class="phase-card foundation">
            <div class="phase-icon">
                <i class="material-icons">foundation</i>
            </div>
            <div class="phase-info">
                <h4>{{ __('employee/planning/common.phases.foundation') }}</h4>
                <p>{{ __('employee/planning/index.phases.foundation_desc') }}</p>
                <div class="phase-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 85%"></div>
                    </div>
                    <span>85% {{ __('employee/planning/index.phases.complete') }}</span>
                </div>
            </div>
        </div>

        <div class="phase-card structure">
            <div class="phase-icon">
                <i class="material-icons">apartment</i>
            </div>
            <div class="phase-info">
                <h4>{{ __('employee/planning/common.phases.structure') }}</h4>
                <p>{{ __('employee/planning/index.phases.structure_desc') }}</p>
                <div class="phase-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 62%"></div>
                    </div>
                    <span>62% {{ __('employee/planning/index.phases.complete') }}</span>
                </div>
            </div>
        </div>

        <div class="phase-card finishing">
            <div class="phase-icon">
                <i class="material-icons">palette</i>
            </div>
            <div class="phase-info">
                <h4>{{ __('employee/planning/common.phases.finishing') }}</h4>
                <p>{{ __('employee/planning/index.phases.finishing_desc') }}</p>
                <div class="phase-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 28%"></div>
                    </div>
                    <span>28% {{ __('employee/planning/index.phases.complete') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="planning-table-card">
    <h3>{{ __('employee/planning/index.table.title') }}</h3>
    <div class="table-responsive">
        <table class="planning-table">
            <thead>
                <tr>
                    <th>{{ __('employee/planning/index.table.project') }}</th>
                    <th>{{ __('employee/planning/index.table.phase') }}</th>
                    <th>{{ __('employee/planning/index.table.progress') }}</th>
                    <th>{{ __('employee/planning/index.table.timeline') }}</th>
                    <th>{{ __('employee/planning/index.table.milestone') }}</th>
                    <th>{{ __('employee/planning/index.table.status') }}</th>
                    <th>{{ __('employee/planning/index.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="project-info">
                            <strong>Residential Complex - Phase 2</strong>
                            <small>PROJ-001</small>
                        </div>
                    </td>
                    <td>
                        <span class="phase-badge foundation">{{ __('employee/planning/common.phases.foundation') }}</span>
                    </td>
                    <td>
                        <div class="progress-cell">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                            <span>85%</span>
                        </div>
                    </td>
                    <td>
                        <div class="timeline-info">
                            <strong>Dec 2025</strong>
                            <small>{{ __('employee/planning/index.table.expected') }}</small>
                        </div>
                    </td>
                    <td>Foundation Inspection</td>
                    <td>
                        <span class="status-badge status-on-track">{{ __('employee/planning/common.status.on_track') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.planning.show', 1) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.planning.edit', 1) }}" class="btn-action">
                                <i class="material-icons">edit</i>
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="project-info">
                            <strong>Commercial Mall Construction</strong>
                            <small>PROJ-002</small>
                        </div>
                    </td>
                    <td>
                        <span class="phase-badge structure">{{ __('employee/planning/common.phases.structure') }}</span>
                    </td>
                    <td>
                        <div class="progress-cell">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 62%"></div>
                            </div>
                            <span>62%</span>
                        </div>
                    </td>
                    <td>
                        <div class="timeline-info">
                            <strong>Mar 2026</strong>
                            <small>{{ __('employee/planning/index.table.expected') }}</small>
                        </div>
                    </td>
                    <td>Structural Framework</td>
                    <td>
                        <span class="status-badge status-on-track">{{ __('employee/planning/common.status.on_track') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.planning.show', 2) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.planning.edit', 2) }}" class="btn-action">
                                <i class="material-icons">edit</i>
                            </a>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td>
                        <div class="project-info">
                            <strong>Highway Bridge Project</strong>
                            <small>PROJ-003</small>
                        </div>
                    </td>
                    <td>
                        <span class="phase-badge finishing">{{ __('employee/planning/common.phases.finishing') }}</span>
                    </td>
                    <td>
                        <div class="progress-cell">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 28%"></div>
                            </div>
                            <span>28%</span>
                        </div>
                    </td>
                    <td>
                        <div class="timeline-info">
                            <strong>Jun 2026</strong>
                            <small>{{ __('employee/planning/index.table.expected') }}</small>
                        </div>
                    </td>
                    <td>Bridge Surface Coating</td>
                    <td>
                        <span class="status-badge status-delayed">{{ __('employee/planning/common.status.delayed') }}</span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.planning.show', 3) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            <a href="{{ route('admin.planning.edit', 3) }}" class="btn-action">
                                <i class="material-icons">edit</i>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

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

    .stat-card.active .stat-icon { background: #2196F3; }
    .stat-card.milestones .stat-icon { background: #FF9800; }
    .stat-card.completed .stat-icon { background: #4CAF50; }
    .stat-card.progress .stat-icon { background: #9C27B0; }

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

    .phases-overview, .planning-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .phases-overview h3, .planning-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .phases-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .phase-card {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .phase-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        flex-shrink: 0;
    }

    .phase-card.foundation .phase-icon { background: #8D6E63; }
    .phase-card.structure .phase-icon { background: #607D8B; }
    .phase-card.finishing .phase-icon { background: #9C27B0; }

    .phase-info {
        flex: 1;
    }

    .phase-info h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .phase-info p {
        margin: 0 0 12px 0;
        color: #666;
        font-size: 14px;
        line-height: 1.4;
    }

    .phase-progress {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .progress-bar {
        flex: 1;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: #6750A4;
        transition: width 0.3s ease;
    }

    .phase-progress span {
        font-size: 12px;
        font-weight: 600;
        color: #6750A4;
        white-space: nowrap;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .planning-table {
        width: 100%;
        border-collapse: collapse;
    }

    .planning-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .planning-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .project-info strong {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .project-info small {
        color: #666;
        font-size: 12px;
    }

    .phase-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .phase-badge.foundation { background: #d7ccc8; color: #5d4037; }
    .phase-badge.structure { background: #cfd8dc; color: #37474f; }
    .phase-badge.finishing { background: #f3e5f5; color: #7b1fa2; }

    .progress-cell {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 120px;
    }

    .progress-cell .progress-bar {
        flex: 1;
    }

    .progress-cell span {
        font-size: 12px;
        font-weight: 600;
        color: #6750A4;
        white-space: nowrap;
    }

    .timeline-info strong {
        display: block;
        font-size: 14px;
        margin-bottom: 2px;
    }

    .timeline-info small {
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

    .status-on-track { background: #e8f5e8; color: #2e7d32; }
    .status-delayed { background: #ffebee; color: #c62828; }
    .status-ahead { background: #e3f2fd; color: #1565c0; }

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

    @media (max-width: 768px) {
        .stats-grid, .phases-grid { grid-template-columns: 1fr; }
        .phase-card { flex-direction: column; text-align: center; }
        .page-header { flex-direction: column; gap: 16px; }
        .progress-cell { min-width: auto; }
    }
</style>
@endpush
@endsection