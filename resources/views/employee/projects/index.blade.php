@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/projects/index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/projects/index.title'))

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <div>
        <h2 style="margin: 0; font-size: 24px; font-weight: 500;">{{ __('employee/projects/index.title') }}</h2>
        <p style="margin: 4px 0 0 0; color: #666;">{{ __('employee/projects/index.subtitle') }}</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn-primary">
        <i class="material-icons">add</i>
        {{ __('employee/projects/common.actions.create') }}
    </a>
</div>

<div class="filters-card">
    <form method="GET" class="filters-form">
        <div class="filter-group">
            <input type="text" name="search" placeholder="{{ __('employee/projects/index.filters.search') }}" 
                   value="{{ request('search') }}" class="filter-input">
        </div>

        <div class="filter-group">
            <select name="status" class="filter-select">
                <option value="">{{ __('employee/projects/index.filters.all_status') }}</option>
                @foreach(App\Models\Project::getStatuses() as $key => $label)
                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <select name="type" class="filter-select">
                <option value="">{{ __('employee/projects/index.filters.all_types') }}</option>
                @foreach(App\Models\Project::getTypes() as $key => $label)
                    <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <select name="project_manager" class="filter-select">
                <option value="">{{ __('employee/projects/index.filters.all_managers') }}</option>
                @foreach($projectManagers as $manager)
                    <option value="{{ $manager->id }}" {{ request('project_manager') == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-filter">
            <i class="material-icons">search</i>
            {{ __('employee/projects/common.actions.filter') }}
        </button>

        @if(request()->hasAny(['search', 'status', 'type', 'project_manager']))
            <a href="{{ route('admin.projects.index') }}" class="btn-clear">
                <i class="material-icons">clear</i>
                {{ __('employee/projects/common.actions.clear') }}
            </a>
        @endif
    </form>
</div>

<div class="projects-grid">
    @if($projects->count() > 0)
        @foreach($projects as $project)
            <div class="project-card">
                <div class="project-header">
                    <div class="project-info">
                        <h3>{{ $project->name }}</h3>
                        <p class="project-code">{{ $project->project_code }}</p>
                        <p class="project-location">📍 {{ $project->location }}</p>
                    </div>
                    <div class="project-actions">
                        <div class="dropdown">
                            <button class="dropdown-btn">
                                <i class="material-icons">more_vert</i>
                            </button>
                            <div class="dropdown-menu">
                                <a href="{{ route('admin.projects.show', $project) }}">
                                    <i class="material-icons">visibility</i>
                                    {{ __('employee/projects/common.actions.view') }}
                                </a>
                                <a href="{{ route('admin.projects.edit', $project) }}">
                                    <i class="material-icons">edit</i>
                                    {{ __('employee/projects/common.actions.edit') }}
                                </a>
                                <a href="#" onclick="deleteProject({{ $project->id }})">
                                    <i class="material-icons">delete</i>
                                    {{ __('employee/projects/common.actions.delete') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="project-meta">
                    <div class="meta-item">
                        <span class="meta-label">{{ __('employee/projects/common.labels.type') }}</span>
                        <span class="type-badge type-{{ $project->type }}">{{ $project->type_label }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">{{ __('employee/projects/common.labels.priority') }}</span>
                        <span class="priority-badge priority-{{ $project->priority }}">{{ $project->priority_label }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">{{ __('employee/projects/common.labels.status') }}</span>
                        <span class="status-badge status-{{ $project->status }}">{{ $project->status_label }}</span>
                    </div>
                </div>

                <div class="project-progress">
                    <div class="progress-header">
                        <span>{{ __('employee/projects/common.labels.progress') }}</span>
                        <span class="progress-percentage">{{ number_format($project->progress_percentage, 1) }}%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $project->progress_percentage }}%"></div>
                    </div>
                </div>

                <div class="project-details">
                    <div class="detail-item">
                        <i class="material-icons">person</i>
                        <span>{{ $project->projectManager->name ?? __('employee/projects/common.status.not_assigned') }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="material-icons">account_balance_wallet</i>
                        <span>₹{{ number_format($project->budget / 100000, 2) }}L</span>
                    </div>
                    <div class="detail-item">
                        <i class="material-icons">event</i>
                        <span>{{ $project->expected_end_date->format('M d, Y') }}</span>
                    </div>
                    @if($project->is_overdue)
                        <div class="detail-item overdue">
                            <i class="material-icons">warning</i>
                            <span>{{ abs($project->days_remaining) }} {{ __('employee/projects/common.status.overdue') }}</span>
                        </div>
                    @elseif($project->days_remaining !== null)
                        <div class="detail-item">
                            <i class="material-icons">schedule</i>
                            <span>{{ $project->days_remaining }} {{ __('employee/projects/common.status.left') }}</span>
                        </div>
                    @endif
                </div>

                <div class="project-footer">
                    <div class="client-info">
                        <strong>{{ $project->client_name }}</strong>
                        @if($project->client_contact)
                            <span>{{ $project->client_contact }}</span>
                        @endif
                    </div>
                    <div class="approval-status">
                        @php
                            $approvedSteps = $project->approvalSteps->where('status', 'approved')->count();
                            $totalSteps = $project->approvalSteps->count();
                        @endphp
                        <span class="approval-count">{{ $approvedSteps }}/{{ $totalSteps }} {{ __('employee/projects/index.cards.approved') }}</span>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="pagination-wrapper">
            {{ $projects->withQueryString()->links() }}
        </div>
    @else
        <div class="empty-state">
            <i class="material-icons">engineering</i>
            <h3>{{ __('employee/projects/index.empty.title') }}</h3>
            <p>{{ request()->hasAny(['search', 'status', 'type', 'project_manager']) ? __('employee/projects/index.empty.text_filter') : __('employee/projects/index.empty.text_new') }}</p>
            @if(!request()->hasAny(['search', 'status', 'type', 'project_manager']))
                <a href="{{ route('admin.projects.create') }}" class="btn-primary">
                    <i class="material-icons">add</i>
                    {{ __('employee/projects/index.empty.btn_create') }}
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
        margin-bottom: 24px;
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

    /* Projects Grid */
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 24px;
    }

    .project-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }

    .project-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }

    .project-info h3 {
        margin: 0 0 8px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .project-code {
        background: #e3f2fd;
        color: #1565c0;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin: 0 0 8px 0;
    }

    .project-location {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .dropdown-btn:hover { background: #f5f5f5; }

    .dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 160px;
        z-index: 1000;
        display: none;
    }

    .dropdown:hover .dropdown-menu { display: block; }

    .dropdown-menu a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 16px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
    }

    .dropdown-menu a:hover { background: #f5f5f5; }

    .project-meta {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .meta-label {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    .type-badge, .priority-badge, .status-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }

    /* Type badges */
    .type-residential { background: #e8f5e8; color: #2e7d32; }
    .type-commercial { background: #e3f2fd; color: #1565c0; }
    .type-industrial { background: #fff3e0; color: #f57c00; }
    .type-infrastructure { background: #f3e5f5; color: #7b1fa2; }
    .type-renovation { background: #fce4ec; color: #c2185b; }

    /* Priority badges */
    .priority-low { background: #e8f5e8; color: #2e7d32; }
    .priority-medium { background: #fff3e0; color: #f57c00; }
    .priority-high { background: #ffebee; color: #c62828; }
    .priority-critical { background: #f3e5f5; color: #7b1fa2; }

    /* Status badges */
    .status-draft { background: #f5f5f5; color: #666; }
    .status-planning { background: #fff3e0; color: #f57c00; }
    .status-approval-pending { background: #e3f2fd; color: #1565c0; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-in-progress { background: #e3f2fd; color: #1565c0; }
    .status-on-hold { background: #ffebee; color: #c62828; }
    .status-completed { background: #e8f5e8; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #c62828; }

    .project-progress {
        margin-bottom: 20px;
    }

    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .progress-header span:first-child {
        font-size: 14px;
        font-weight: 500;
        color: #333;
    }

    .progress-percentage {
        font-size: 14px;
        font-weight: 600;
        color: #6750A4;
    }

    .progress-bar {
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #4CAF50, #45a049);
        transition: width 0.3s;
    }

    .project-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }

    .detail-item i {
        font-size: 18px;
        color: #6750A4;
    }

    .detail-item.overdue {
        color: #c62828;
    }

    .detail-item.overdue i {
        color: #c62828;
    }

    .project-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-top: 1px solid #f0f0f0;
        padding-top: 16px;
    }

    .client-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .client-info strong {
        font-size: 14px;
        color: #333;
    }

    .client-info span {
        font-size: 12px;
        color: #666;
    }

    .approval-status {
        text-align: right;
    }

    .approval-count {
        background: #f0f0f0;
        color: #666;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        color: #666;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
        color: #6750A4;
    }

    .empty-state h3 {
        margin: 0 0 8px 0;
        color: #333;
        font-size: 20px;
    }

    .empty-state p { margin: 0 0 24px 0; }

    .pagination-wrapper {
        grid-column: 1 / -1;
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .projects-grid {
            grid-template-columns: 1fr;
        }

        .filters-form { flex-direction: column; }
        .filter-group { width: 100%; min-width: unset; }

        .project-meta { flex-direction: column; gap: 12px; }
        .project-footer { flex-direction: column; gap: 12px; align-items: flex-start; }
    }
</style>
@endpush

@push('scripts')
<script>
    async function deleteProject(projectId) {
        if (!confirm('Are you sure you want to delete this project?\n\nThis will permanently remove:\n• Project information and documents\n• All approval steps and progress\n• Resource allocations\n\nType "DELETE" to confirm.')) {
            return;
        }

        const confirmation = prompt('Type "DELETE" to confirm project deletion:');
        if (confirmation !== 'DELETE') {
            alert('Deletion cancelled. You must type "DELETE" exactly.');
            return;
        }

        try {
            const response = await fetch(`/admin/projects/${projectId}`, {
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
                alert(data.message || 'Failed to delete project');
            }
        } catch (error) {
            alert('Error deleting project: ' + error.message);
            console.error(error);
        }
    }
</script>
@endpush