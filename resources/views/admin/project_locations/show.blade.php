@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.admin')

@section('title', __('project_locations.show.title', ['project' => $project->name, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('project_locations.show.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('project_locations.show.back_button') }}
        </a>
    </div>
    <div class="project-header">
        <div class="project-info">
            <h2>{{ $project->name }}</h2>
            <p>{{ $project->project_code }} • {{ $project->type_label }}</p>
            <div class="project-badges">
                <span class="status-badge status-{{ $project->status }}">
                    {{ $project->status_label }}
                </span>
                <span class="priority-badge priority-{{ $project->priority }}">
                    {{ $project->priority_label }}
                </span>
            </div>
        </div>
        <div class="project-actions">
            <a href="{{ route('admin.projects.edit', $project) }}" class="btn-primary">
                <i class="material-icons">edit</i>
                {{ __('project_locations.show.edit_button') }}
            </a>
        </div>
    </div>
</div>

<div class="project-content">
    <!-- Project Overview -->
    <div class="overview-grid">
        <div class="overview-card">
            <h3>{{ __('project_locations.show.progress_title') }}</h3>
            <div class="progress-display">
                <div class="progress-circle">
                    <span class="progress-value">{{ number_format($project->progress_percentage, 1) }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: {{ $project->progress_percentage }}%"></div>
                </div>
            </div>
        </div>

        <div class="overview-card">
            <h3>{{ __('project_locations.show.timeline_title') }}</h3>
            <div class="timeline-info">
                <div class="timeline-item">
                    <strong>{{ __('project_locations.show.start_date') }}:</strong>
                    <span>{{ $project->start_date?->format('M d, Y') ?? __('project_locations.show.not_set') }}</span>
                </div>
                <div class="timeline-item">
                    <strong>{{ __('project_locations.show.expected_end') }}:</strong>
                    <span>{{ $project->expected_end_date?->format('M d, Y') ?? __('project_locations.show.not_set') }}</span>
                </div>
                @if($project->actual_end_date)
                    <div class="timeline-item">
                        <strong>{{ __('project_locations.show.actual_end') }}:</strong>
                        <span>{{ $project->actual_end_date->format('M d, Y') }}</span>
                    </div>
                @endif
                @if($project->days_remaining !== null)
                    <div class="timeline-item {{ $project->is_overdue ? 'overdue' : '' }}">
                        <strong>{{ __('project_locations.show.status_label') }}:</strong>
                        <span>
                            @if($project->is_overdue)
                                {{ abs($project->days_remaining) }} {{ __('project_locations.show.days_overdue') }}
                            @else
                                {{ $project->days_remaining }} {{ __('project_locations.show.days_remaining') }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <div class="overview-card">
            <h3>{{ __('project_locations.show.budget_title') }}</h3>
            <div class="budget-info">
                <div class="budget-amount">₹{{ number_format($project->budget) }}</div>
                <div class="budget-label">{{ __('project_locations.show.total_budget') }}</div>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="details-grid">
        <div class="details-card">
            <h3>{{ __('project_locations.show.info_title') }}</h3>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.description_label') }}:</strong>
                <p>{{ $project->description }}</p>
            </div>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.location_label') }}:</strong>
                <span>{{ $project->location }}</span>
            </div>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.client_label') }}:</strong>
                <span>{{ $project->client_name }}</span>
                @if($project->client_contact)
                    <small>({{ $project->client_contact }})</small>
                @endif
            </div>
        </div>

        <div class="details-card">
            <h3>{{ __('project_locations.show.team_title') }}</h3>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.project_manager') }}:</strong>
                <span>{{ $project->projectManager?->name ?? __('project_locations.show.not_assigned') }}</span>
            </div>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.created_by') }}:</strong>
                <span>{{ $project->creator?->name ?? __('project_locations.show.unknown') }}</span>
            </div>
            <div class="detail-item">
                <strong>{{ __('project_locations.show.created_on') }}:</strong>
                <span>{{ $project->created_at->format('M d, Y \a\t g:i A') }}</span>
            </div>
        </div>
    </div>

    <!-- 12-Step Approval Workflow -->
    <div class="approval-workflow">
        <h3>{{ __('project_locations.show.workflow_title') }}</h3>
        <div class="workflow-steps">
            @foreach($project->approvalSteps as $step)
                <div class="workflow-step step-{{ $step->status }}">
                    <div class="step-header">
                        <div class="step-number">{{ $step->step_order }}</div>
                        <div class="step-info">
                            <h4>{{ $step->step_name }}</h4>
                            <p>{{ $step->consultancy_type_label }}</p>
                        </div>
                        <div class="step-status">
                            <span class="status-badge status-{{ $step->status }}">
                                {{ $step->status_label }}
                            </span>
                        </div>
                    </div>

                    @if($step->due_date)
                        <div class="step-timeline">
                            <strong>{{ __('project_locations.show.due_date') }}:</strong> {{ $step->due_date->format('M d, Y') }}
                            @if($step->approved_at)
                                <br><strong>{{ __('project_locations.show.approved_date') }}:</strong> {{ $step->approved_at->format('M d, Y \a\t g:i A') }}
                            @endif
                        </div>
                    @endif

                    @if($step->responsible_person)
                        <div class="step-responsible">
                            <strong>{{ __('project_locations.show.responsible') }}:</strong> {{ $step->responsible_person->name }}
                        </div>
                    @endif

                    @if($step->remarks)
                        <div class="step-remarks">
                            <strong>{{ __('project_locations.show.remarks') }}:</strong> {{ $step->remarks }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Project Documents -->
    @if($project->documents && count($project->documents) > 0)
        <div class="documents-section">
            <h3>{{ __('project_locations.show.documents_title') }}</h3>
            <div class="documents-grid">
                @foreach($project->documents as $document)
                    <div class="document-item">
                        <div class="document-icon">
                            <i class="material-icons">description</i>
                        </div>
                        <div class="document-info">
                            <strong>{{ $document }}</strong>
                            <p>{{ __('project_locations.show.project_document') }}</p>
                        </div>
                        <div class="document-actions">
                            <a href="{{ Storage::url('project-documents/' . $document) }}" 
                               target="_blank" class="btn-action" title="{{ __('project_locations.show.view_document') }}">
                                <i class="material-icons">open_in_new</i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-nav { margin-bottom: 24px; }
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
    .project-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }
    .project-info h2 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 500;
        color: #1C1B1F;
    }
    .project-info p {
        margin: 0 0 16px 0;
        color: #666;
        font-size: 16px;
    }
    .project-badges {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
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
    .project-content {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }
    .overview-card, .details-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .overview-card h3, .details-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }
    .progress-display { text-align: center; }
    .progress-circle {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(#6750A4 0deg, #e0e0e0 0deg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        position: relative;
    }
    .progress-value {
        font-size: 24px;
        font-weight: 600;
        color: #1C1B1F;
    }
    .progress-bar {
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #6750A4, #7B68C8);
        transition: width 0.3s;
    }
    .timeline-info, .budget-info {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .timeline-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .timeline-item:last-child { border-bottom: none; }
    .timeline-item.overdue span { color: #d32f2f; }
    .budget-amount {
        font-size: 32px;
        font-weight: 600;
        color: #2e7d32;
        margin-bottom: 4px;
    }
    .budget-label {
        color: #666;
        font-size: 14px;
    }
    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }
    .detail-item {
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid #f0f0f0;
    }
    .detail-item:last-child { border-bottom: none; margin-bottom: 0; }
    .detail-item strong {
        display: block;
        margin-bottom: 6px;
        color: #333;
        font-weight: 500;
    }
    .detail-item p {
        margin: 0;
        line-height: 1.5;
        color: #666;
    }
    .detail-item small {
        color: #999;
        font-size: 12px;
        margin-left: 8px;
    }
    /* Status and Priority Badges */
    .status-badge, .priority-badge {
        padding: 6px 16px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    /* Status colors */
    .status-draft { background: #f5f5f5; color: #666; }
    .status-planning { background: #fff3e0; color: #f57c00; }
    .status-approval-pending { background: #e3f2fd; color: #1565c0; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-in-progress { background: #e3f2fd; color: #1565c0; }
    .status-on-hold { background: #ffebee; color: #c62828; }
    .status-completed { background: #e8f5e8; color: #2e7d32; }
    .status-cancelled { background: #ffebee; color: #c62828; }
    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-rejected { background: #ffebee; color: #c62828; }
    /* Priority colors */
    .priority-low { background: #e8f5e8; color: #2e7d32; }
    .priority-medium { background: #fff3e0; color: #f57c00; }
    .priority-high { background: #ffebee; color: #c62828; }
    .priority-critical { background: #f3e5f5; color: #7b1fa2; }
    /* Workflow Styles */
    .approval-workflow {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .approval-workflow h3 {
        margin: 0 0 24px 0;
        font-size: 20px;
        font-weight: 600;
        color: #1C1B1F;
    }
    .workflow-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 20px;
    }
    .workflow-step {
        padding: 20px;
        border-radius: 12px;
        border: 2px solid #f0f0f0;
        transition: all 0.2s;
    }
    .workflow-step.step-approved {
        border-color: #4caf50;
        background: #f1f8e9;
    }
    .workflow-step.step-in-progress {
        border-color: #2196f3;
        background: #e3f2fd;
    }
    .workflow-step.step-rejected {
        border-color: #f44336;
        background: #ffebee;
    }
    .step-header {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 12px;
    }
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        flex-shrink: 0;
    }
    .step-info {
        flex: 1;
    }
    .step-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #1C1B1F;
    }
    .step-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }
    .step-timeline, .step-responsible, .step-remarks {
        margin-top: 8px;
        font-size: 14px;
        color: #666;
    }
    /* Documents Section */
    .documents-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .documents-section h3 {
        margin: 0 0 24px 0;
        font-size: 20px;
        font-weight: 600;
        color: #1C1B1F;
    }
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }
    .document-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
    .document-icon i {
        color: #6750A4;
        font-size: 32px;
    }
    .document-info {
        flex: 1;
    }
    .document-info strong {
        display: block;
        font-size: 14px;
        color: #333;
        margin-bottom: 2px;
    }
    .document-info p {
        margin: 0;
        color: #666;
        font-size: 12px;
    }
    .btn-action {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-action:hover { background: #5A4A94; }
    @media (max-width: 768px) {
        .project-header { flex-direction: column; gap: 20px; }
        .overview-grid, .details-grid { grid-template-columns: 1fr; }
        .workflow-steps { grid-template-columns: 1fr; }
        .documents-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush
