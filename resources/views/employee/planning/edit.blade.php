@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/planning/edit.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/planning/edit.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.planning.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/planning/common.actions.back') }}
        </a>
        <a href="{{ route('admin.planning.show', 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('employee/planning/common.actions.view') }}
        </a>
    </div>
    <h2>{{ __('employee/planning/edit.title') }}</h2>
    <p>{{ __('employee/planning/edit.subtitle') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.planning.update', 1) }}" method="POST" class="planning-form">
        @csrf
        @method('PUT')

        <div class="current-status">
            <div class="status-info">
                <div class="project-icon">
                    <i class="material-icons">engineering</i>
                </div>
                <div>
                    <h3>Residential Complex - Phase 2</h3>
                    <p>PROJ-001 • 85% {{ __('employee/planning/common.status.completed') }} • {{ __('employee/planning/common.status.on_track') }}</p>
                </div>
            </div>
            <div class="current-progress">
                <div class="progress-circle">
                    <svg viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    </svg>
                    <div class="progress-text">85%</div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                {{ __('employee/planning/edit.sections.info') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="plan_name">{{ __('employee/planning/create.form.plan_name') }} *</label>
                    <input type="text" id="plan_name" name="plan_name" value="Foundation Phase Plan" required>
                    @error('plan_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="current_status">{{ __('employee/planning/edit.labels.current_status') }}</label>
                    <select id="current_status" name="current_status">
                        <option value="planning">{{ __('employee/planning/common.status.planning') }}</option>
                        <option value="in_progress" selected>{{ __('employee/planning/common.status.in_progress') }}</option>
                        <option value="on_hold">{{ __('employee/planning/common.status.on_hold') }}</option>
                        <option value="completed">{{ __('employee/planning/common.status.completed') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">{{ __('employee/planning/create.form.start_date') }} *</label>
                    <input type="date" id="start_date" name="start_date" value="2025-10-01" required>
                </div>

                <div class="form-group">
                    <label for="end_date">{{ __('employee/planning/create.form.end_date') }} *</label>
                    <input type="date" id="end_date" name="end_date" value="2025-12-31" required>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">timeline</i>
                {{ __('employee/planning/edit.sections.phases') }}
            </h3>

            <div class="phases-container">
                <div class="phase-item completed">
                    <div class="phase-header">
                        <h4>Phase 1: {{ __('employee/planning/common.phases.foundation') }}</h4>
                        <div class="phase-controls">
                            <select name="foundation_status">
                                <option value="not_started">{{ __('employee/planning/common.status.not_started') }}</option>
                                <option value="in_progress">{{ __('employee/planning/common.status.in_progress') }}</option>
                                <option value="completed" selected>{{ __('employee/planning/common.status.completed') }}</option>
                                <option value="on_hold">{{ __('employee/planning/common.status.on_hold') }}</option>
                            </select>
                            <input type="checkbox" name="phases[]" value="foundation" checked>
                        </div>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="foundation_duration" value="45" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_used') }} (%)</label>
                                <input type="number" name="foundation_budget" value="30" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.progress') }} (%)</label>
                                <input type="number" name="foundation_progress" value="100" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.team_size') }}</label>
                                <input type="number" name="foundation_team" value="12" min="1">
                            </div>
                        </div>
                        <textarea name="foundation_description" rows="2">Site preparation, excavation, foundation laying - COMPLETED</textarea>
                    </div>
                </div>

                <div class="phase-item active">
                    <div class="phase-header">
                        <h4>Phase 2: {{ __('employee/planning/common.phases.structure') }}</h4>
                        <div class="phase-controls">
                            <select name="structure_status">
                                <option value="not_started">{{ __('employee/planning/common.status.not_started') }}</option>
                                <option value="in_progress" selected>{{ __('employee/planning/common.status.in_progress') }}</option>
                                <option value="completed">{{ __('employee/planning/common.status.completed') }}</option>
                                <option value="on_hold">{{ __('employee/planning/common.status.on_hold') }}</option>
                            </select>
                            <input type="checkbox" name="phases[]" value="structure" checked>
                        </div>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="structure_duration" value="90" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_used') }} (%)</label>
                                <input type="number" name="structure_budget" value="28" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.progress') }} (%)</label>
                                <input type="number" name="structure_progress" value="62" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.team_size') }}</label>
                                <input type="number" name="structure_team" value="25" min="1">
                            </div>
                        </div>
                        <textarea name="structure_description" rows="2">Framework, walls, structural elements - IN PROGRESS</textarea>
                    </div>
                </div>

                <div class="phase-item">
                    <div class="phase-header">
                        <h4>Phase 3: {{ __('employee/planning/common.phases.finishing') }}</h4>
                        <div class="phase-controls">
                            <select name="finishing_status">
                                <option value="not_started" selected>{{ __('employee/planning/common.status.not_started') }}</option>
                                <option value="in_progress">{{ __('employee/planning/common.status.in_progress') }}</option>
                                <option value="completed">{{ __('employee/planning/common.status.completed') }}</option>
                                <option value="on_hold">{{ __('employee/planning/common.status.on_hold') }}</option>
                            </select>
                            <input type="checkbox" name="phases[]" value="finishing" checked>
                        </div>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="finishing_duration" value="60" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_alloc') }}</label>
                                <input type="number" name="finishing_budget" value="25" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.progress') }} (%)</label>
                                <input type="number" name="finishing_progress" value="0" min="0" max="100">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.team_size') }}</label>
                                <input type="number" name="finishing_team" value="18" min="1">
                            </div>
                        </div>
                        <textarea name="finishing_description" rows="2">Interior work, painting, final touches - PLANNED</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">flag</i>
                {{ __('employee/planning/edit.sections.milestones') }}
            </h3>

            <div class="milestones-container">
                <div class="milestone-item completed">
                    <div class="milestone-header">
                        <h4>Foundation Inspection</h4>
                        <select name="milestone_1_status">
                            <option value="pending">{{ __('employee/planning/common.status.pending') }}</option>
                            <option value="in_progress">{{ __('employee/planning/common.status.in_progress') }}</option>
                            <option value="completed" selected>{{ __('employee/planning/common.status.completed') }}</option>
                            <option value="overdue">{{ __('employee/planning/common.status.overdue') }}</option>
                        </select>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                            <input type="text" name="milestone_1_name" value="Foundation Inspection">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                            <input type="date" name="milestone_1_date" value="2025-11-15">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.actual_date') }}</label>
                            <input type="date" name="milestone_1_actual" value="2025-11-15">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.phase') }}</label>
                            <select name="milestone_1_phase">
                                <option value="foundation" selected>{{ __('employee/planning/common.phases.foundation') }}</option>
                                <option value="structure">{{ __('employee/planning/common.phases.structure') }}</option>
                                <option value="finishing">{{ __('employee/planning/common.phases.finishing') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="milestone-item active">
                    <div class="milestone-header">
                        <h4>Structural Framework Complete</h4>
                        <select name="milestone_2_status">
                            <option value="pending">{{ __('employee/planning/common.status.pending') }}</option>
                            <option value="in_progress" selected>{{ __('employee/planning/common.status.in_progress') }}</option>
                            <option value="completed">{{ __('employee/planning/common.status.completed') }}</option>
                            <option value="overdue">{{ __('employee/planning/common.status.overdue') }}</option>
                        </select>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                            <input type="text" name="milestone_2_name" value="Structural Framework Complete">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                            <input type="date" name="milestone_2_date" value="2026-01-10">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.expected_date') }}</label>
                            <input type="date" name="milestone_2_expected" value="2026-01-12">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.phase') }}</label>
                            <select name="milestone_2_phase">
                                <option value="foundation">{{ __('employee/planning/common.phases.foundation') }}</option>
                                <option value="structure" selected>{{ __('employee/planning/common.phases.structure') }}</option>
                                <option value="finishing">{{ __('employee/planning/common.phases.finishing') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">groups</i>
                {{ __('employee/planning/edit.sections.resources') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="project_manager">{{ __('employee/planning/create.form.manager') }}</label>
                    <select id="project_manager" name="project_manager">
                        <option value="1" selected>Rajesh Kumar - Senior PM</option>
                        <option value="2">Priya Singh - Construction Manager</option>
                        <option value="3">Amit Sharma - Site Engineer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="current_team_size">{{ __('employee/planning/edit.labels.current_team') }}</label>
                    <input type="number" id="current_team_size" name="current_team_size" value="25" min="1" max="200">
                </div>

                <div class="form-group">
                    <label for="budget_used">{{ __('employee/planning/edit.labels.budget_used') }} (₹)</label>
                    <input type="number" id="budget_used" name="budget_used" value="2900000" min="0" step="10000">
                </div>

                <div class="form-group">
                    <label for="total_budget">{{ __('employee/planning/edit.labels.total_budget') }} (₹)</label>
                    <input type="number" id="total_budget" name="total_budget" value="5000000" min="0" step="10000">
                </div>

                <div class="form-group">
                    <label for="priority">{{ __('employee/planning/create.form.priority') }}</label>
                    <select id="priority" name="priority">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high" selected>High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="overall_progress">{{ __('employee/planning/edit.labels.overall_progress') }}</label>
                    <input type="number" id="overall_progress" name="overall_progress" value="85" min="0" max="100">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                {{ __('employee/planning/edit.sections.details') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="description">{{ __('employee/planning/create.form.desc') }}</label>
                    <textarea id="description" name="description" rows="4">This is a comprehensive residential complex development project focusing on sustainable construction practices and modern architectural design. The project includes multiple residential towers with integrated amenities and green spaces.</textarea>
                </div>

                <div class="form-group">
                    <label for="risks">{{ __('employee/planning/edit.labels.updated_risk') }}</label>
                    <textarea id="risks" name="risks" rows="3">Weather delays possible in winter months. Material cost fluctuations. Labor shortage during peak season.</textarea>
                </div>

                <div class="form-group">
                    <label for="success_criteria">{{ __('employee/planning/edit.labels.criteria_update') }}</label>
                    <textarea id="success_criteria" name="success_criteria" rows="3">Complete project within budget. Achieve LEED Gold certification. Zero safety incidents. 95% customer satisfaction rating.</textarea>
                </div>
            </div>
        </div>

        <div class="audit-section">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                {{ __('employee/planning/edit.sections.history') }}
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">Nov 15, 2025 - 10:30 AM</div>
                    <div class="audit-action">Foundation phase marked as completed</div>
                    <div class="audit-user">Rajesh Kumar</div>
                </div>

                <div class="audit-item">
                    <div class="audit-time">Dec 01, 2025 - 02:15 PM</div>
                    <div class="audit-action">Structure phase progress updated to 62%</div>
                    <div class="audit-user">Site Engineer</div>
                </div>

                <div class="audit-item current">
                    <div class="audit-time">{{ now()->format('M d, Y - h:i A') }}</div>
                    <div class="audit-action">Project plan being modified</div>
                    <div class="audit-user">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('employee/planning/common.actions.update') }}
            </button>
            <a href="{{ route('admin.planning.show', 1) }}" class="btn-cancel">
                {{ __('employee/planning/common.actions.cancel') }}
            </a>
            <button type="button" class="btn-warning" onclick="exportPlan()">
                <i class="material-icons">download</i>
                {{ __('employee/planning/common.actions.export') }}
            </button>
        </div>
    </form>
</div>

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }

    .page-nav {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .btn-back, .btn-secondary {
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

    .btn-back:hover, .btn-secondary:hover { background: rgba(103, 80, 164, 0.08); }

    .form-container {
        max-width: 1200px;
    }

    .planning-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .current-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .status-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .project-icon {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .status-info h3 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 500;
    }

    .status-info p {
        margin: 0;
        color: #666;
    }

    .current-progress {
        text-align: center;
    }

    .progress-circle {
        position: relative;
        width: 60px;
        height: 60px;
    }

    .progress-circle svg {
        width: 100%;
        height: 100%;
    }

    .circle-bg {
        fill: none;
        stroke: #e0e0e0;
        stroke-width: 2;
    }

    .circle {
        fill: none;
        stroke: #6750A4;
        stroke-width: 3;
        stroke-linecap: round;
        animation: progress 1s ease-in-out;
    }

    .progress-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 14px;
        font-weight: 600;
        color: #6750A4;
    }

    @keyframes progress {
        0% { stroke-dasharray: 0 100; }
    }

    .form-section, .audit-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .section-title i { color: #6750A4; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width { grid-column: 1 / -1; }

    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
        background: white;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6750A4;
    }

    .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .phases-container, .milestones-container {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .phase-item, .milestone-item {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }

    .phase-item.completed {
        border-color: #4CAF50;
        background: #f1f8e9;
    }

    .phase-item.active {
        border-color: #6750A4;
        background: #f8f7ff;
    }

    .milestone-item.completed {
        border-color: #4CAF50;
    }

    .milestone-item.active {
        border-color: #6750A4;
    }

    .phase-header, .milestone-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
    }

    .phase-item.completed .phase-header {
        background: #f1f8e9;
    }

    .phase-item.active .phase-header {
        background: #f8f7ff;
    }

    .phase-header h4, .milestone-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .phase-controls {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .phase-controls select {
        padding: 6px 12px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        font-size: 12px;
    }

    .phase-content {
        padding: 20px;
        background: #fafafa;
        border-top: 1px solid #e0e0e0;
    }

    .phase-content textarea {
        width: 100%;
        margin-top: 16px;
        resize: vertical;
    }

    .audit-trail {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .audit-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #6750A4;
    }

    .audit-item.current {
        background: #e3f2fd;
        border-left-color: #2196F3;
    }

    .audit-time {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }

    .audit-action {
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }

    .audit-user {
        font-size: 12px;
        color: #6750A4;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }

    .btn-primary, .btn-cancel, .btn-warning {
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

    .btn-cancel {
        border: 2px solid #e0e0e0;
        color: #666;
        background: white;
    }

    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }

    .btn-warning {
        background: #FF9800;
        color: white;
    }

    .btn-warning:hover { background: #f57c00; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .current-status { flex-direction: column; gap: 16px; text-align: center; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-warning { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
        .phase-controls { flex-direction: column; gap: 8px; }
    }
</style>
@endpush

@push('scripts')
<script>
function exportPlan() {
    alert('Project plan export functionality will be implemented');
}

// Update progress circle based on overall progress input
document.addEventListener('DOMContentLoaded', function() {
    const progressInput = document.getElementById('overall_progress');
    const circle = document.querySelector('.circle');
    const progressText = document.querySelector('.progress-text');

    function updateProgressCircle() {
        const value = parseInt(progressInput.value) || 0;
        circle.style.strokeDasharray = `${value}, 100`;
        progressText.textContent = `${value}%`;
    }

    progressInput.addEventListener('input', updateProgressCircle);

    // Budget validation
    const budgetUsed = document.getElementById('budget_used');
    const totalBudget = document.getElementById('total_budget');

    function validateBudget() {
        const used = parseInt(budgetUsed.value) || 0;
        const total = parseInt(totalBudget.value) || 0;

        if (used > total) {
            budgetUsed.style.borderColor = '#f44336';
            totalBudget.style.borderColor = '#f44336';
        } else {
            budgetUsed.style.borderColor = '#e0e0e0';
            totalBudget.style.borderColor = '#e0e0e0';
        }
    }

    budgetUsed.addEventListener('input', validateBudget);
    totalBudget.addEventListener('input', validateBudget);

    // Phase status change handling
    document.querySelectorAll('select[name$="_status"]').forEach(select => {
        select.addEventListener('change', function() {
            const phaseItem = this.closest('.phase-item');
            phaseItem.className = 'phase-item';

            if (this.value === 'completed') {
                phaseItem.classList.add('completed');
            } else if (this.value === 'in_progress') {
                phaseItem.classList.add('active');
            }
        });
    });
});
</script>
@endpush
@endsection