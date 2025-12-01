@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/planning/show.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/planning/show.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.planning.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/planning/common.actions.back') }}
        </a>
    </div>
    <div class="plan-header">
        <div class="plan-info">
            <h2>Residential Complex - Phase 2</h2>
            <p>PROJ-001 • Foundation Phase Plan</p>
            <div class="plan-timeline">
                <i class="material-icons">schedule</i>
                <span>Oct 2025 - Dec 2025</span>
                <strong>195 {{ __('employee/planning/show.days_total') }}</strong>
            </div>
        </div>
        <div class="plan-status">
            <span class="status-badge status-on-track">{{ __('employee/planning/common.status.on_track') }}</span>
            <div class="plan-progress">
                <div class="progress-circle">
                    <svg viewBox="0 0 36 36">
                        <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                        <path class="circle" stroke-dasharray="85, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                    </svg>
                    <div class="progress-text">85%</div>
                </div>
                <small>{{ __('employee/planning/show.overall_progress') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="plan-details">
    <div class="phases-section">
        <h3>{{ __('employee/planning/show.sections.phases') }}</h3>
        <div class="phases-list">
            <div class="phase-item active completed">
                <div class="phase-header">
                    <div class="phase-icon">
                        <i class="material-icons">foundation</i>
                    </div>
                    <div class="phase-info">
                        <h4>{{ __('employee/planning/common.phases.foundation') }}</h4>
                        <p>{{ __('employee/planning/index.phases.foundation_desc') }}</p>
                    </div>
                    <div class="phase-status">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%"></div>
                        </div>
                        <span>100% {{ __('employee/planning/index.phases.complete') }}</span>
                    </div>
                </div>
                <div class="phase-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.duration') }}:</strong>
                            <span>45 days</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.budget_used') }}:</strong>
                            <span>₹15,00,000 (30%)</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.team_size') }}:</strong>
                            <span>12 workers</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.actual_date') }}:</strong>
                            <span>Nov 15, 2025</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase-item active">
                <div class="phase-header">
                    <div class="phase-icon">
                        <i class="material-icons">apartment</i>
                    </div>
                    <div class="phase-info">
                        <h4>{{ __('employee/planning/common.phases.structure') }}</h4>
                        <p>{{ __('employee/planning/index.phases.structure_desc') }}</p>
                    </div>
                    <div class="phase-status">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 62%"></div>
                        </div>
                        <span>62% {{ __('employee/planning/index.phases.complete') }}</span>
                    </div>
                </div>
                <div class="phase-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.duration') }}:</strong>
                            <span>90 days</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.budget_used') }}:</strong>
                            <span>₹14,00,000 of ₹22,50,000</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.team_size') }}:</strong>
                            <span>25 workers</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.expected_date') }}:</strong>
                            <span>Jan 20, 2026</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="phase-item">
                <div class="phase-header">
                    <div class="phase-icon">
                        <i class="material-icons">palette</i>
                    </div>
                    <div class="phase-info">
                        <h4>{{ __('employee/planning/common.phases.finishing') }}</h4>
                        <p>{{ __('employee/planning/index.phases.finishing_desc') }}</p>
                    </div>
                    <div class="phase-status">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%"></div>
                        </div>
                        <span>{{ __('employee/planning/common.status.not_started') }}</span>
                    </div>
                </div>
                <div class="phase-details">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.duration') }}:</strong>
                            <span>60 days</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.budget_alloc') }}:</strong>
                            <span>₹12,50,000 (25%)</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.team_size') }}:</strong>
                            <span>18 workers</span>
                        </div>
                        <div class="detail-item">
                            <strong>{{ __('employee/planning/common.labels.start_date') }}:</strong>
                            <span>Feb 1, 2026</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="milestones-section">
        <h3>{{ __('employee/planning/show.sections.milestones') }}</h3>
        <div class="timeline">
            <div class="timeline-item completed">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Foundation Inspection</h4>
                    <p>Quality inspection of foundation work completed successfully</p>
                    <div class="timeline-meta">
                        <span class="timeline-date">Nov 15, 2025</span>
                        <span class="timeline-status completed">{{ __('employee/planning/common.status.completed') }}</span>
                    </div>
                </div>
            </div>

            <div class="timeline-item active">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Structural Framework Complete</h4>
                    <p>Main structural framework and load-bearing elements</p>
                    <div class="timeline-meta">
                        <span class="timeline-date">Jan 10, 2026</span>
                        <span class="timeline-status in-progress">{{ __('employee/planning/common.status.in_progress') }}</span>
                    </div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>MEP Installation Complete</h4>
                    <p>Mechanical, Electrical, and Plumbing systems installation</p>
                    <div class="timeline-meta">
                        <span class="timeline-date">Feb 20, 2026</span>
                        <span class="timeline-status pending">{{ __('employee/planning/common.status.pending') }}</span>
                    </div>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-marker"></div>
                <div class="timeline-content">
                    <h4>Final Quality Check</h4>
                    <p>Complete quality assurance and safety inspection</p>
                    <div class="timeline-meta">
                        <span class="timeline-date">Mar 15, 2026</span>
                        <span class="timeline-status pending">{{ __('employee/planning/common.status.pending') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="resource-grid">
        <div class="resource-card">
            <h3>{{ __('employee/planning/show.sections.budget') }}</h3>
            <div class="budget-breakdown">
                <div class="budget-item">
                    <span>{{ __('employee/planning/show.budget.total') }}:</span>
                    <strong>₹50,00,000</strong>
                </div>
                <div class="budget-item">
                    <span>{{ __('employee/planning/show.budget.used') }}:</span>
                    <strong>₹29,00,000</strong>
                </div>
                <div class="budget-item">
                    <span>{{ __('employee/planning/show.budget.remaining') }}:</span>
                    <strong>₹21,00,000</strong>
                </div>
                <div class="budget-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: 58%"></div>
                    </div>
                    <span>58% {{ __('employee/planning/show.budget.utilized') }}</span>
                </div>
            </div>
        </div>

        <div class="resource-card">
            <h3>{{ __('employee/planning/show.sections.team') }}</h3>
            <div class="team-info">
                <div class="team-item">
                    <strong>{{ __('employee/planning/show.team.manager') }}:</strong>
                    <span>Rajesh Kumar</span>
                </div>
                <div class="team-item">
                    <strong>{{ __('employee/planning/show.team.current') }}:</strong>
                    <span>25 workers</span>
                </div>
                <div class="team-item">
                    <strong>{{ __('employee/planning/show.team.peak') }}:</strong>
                    <span>35 workers</span>
                </div>
                <div class="team-item">
                    <strong>{{ __('employee/planning/show.team.priority') }}:</strong>
                    <span class="priority-badge high">High</span>
                </div>
            </div>
        </div>

        <div class="resource-card">
            <h3>{{ __('employee/planning/show.sections.stats') }}</h3>
            <div class="stats-list">
                <div class="stat-item">
                    <div class="stat-value">195</div>
                    <div class="stat-label">{{ __('employee/planning/show.stats.total_days') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">98</div>
                    <div class="stat-label">{{ __('employee/planning/show.stats.elapsed') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">97</div>
                    <div class="stat-label">{{ __('employee/planning/show.stats.remaining') }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">4</div>
                    <div class="stat-label">{{ __('employee/planning/show.stats.milestones') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="description-section">
        <h3>{{ __('employee/planning/show.sections.desc') }}</h3>
        <div class="description-content">
            <p>This is a comprehensive residential complex development project focusing on sustainable construction practices and modern architectural design. The project includes multiple residential towers with integrated amenities and green spaces.</p>

            <div class="description-details">
                <div class="detail-column">
                    <h4>{{ __('employee/planning/show.desc.features') }}</h4>
                    <ul>
                        <li>3 residential towers (15 floors each)</li>
                        <li>Underground parking facility</li>
                        <li>Community center and gymnasium</li>
                        <li>Landscaped gardens and recreational areas</li>
                        <li>Solar panel installation</li>
                    </ul>
                </div>

                <div class="detail-column">
                    <h4>{{ __('employee/planning/show.desc.criteria') }}</h4>
                    <ul>
                        <li>Complete project within budget</li>
                        <li>Achieve LEED Gold certification</li>
                        <li>Zero safety incidents</li>
                        <li>95% customer satisfaction</li>
                        <li>On-time delivery</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="action-section">
        <div class="plan-actions">
            <a href="{{ route('admin.planning.edit', 1) }}" class="btn-primary">
                <i class="material-icons">edit</i>
                {{ __('employee/planning/common.actions.edit') }}
            </a>
            <button class="btn-secondary" onclick="generateReport()">
                <i class="material-icons">assessment</i>
                {{ __('employee/planning/common.actions.generate_report') }}
            </button>
        </div>

        <div class="other-actions">
            <button class="btn-success" onclick="markMilestone()">
                <i class="material-icons">flag</i>
                {{ __('employee/planning/common.actions.mark_milestone') }}
            </button>
            <button class="btn-warning" onclick="updateProgress()">
                <i class="material-icons">update</i>
                {{ __('employee/planning/common.actions.update_progress') }}
            </button>
        </div>
    </div>
</div>

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

    .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .plan-info h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .plan-info p {
        margin: 0 0 12px 0;
        color: #666;
    }

    .plan-timeline {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        font-weight: 500;
    }

    .plan-status {
        text-align: right;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-on-track { background: #e8f5e8; color: #2e7d32; }

    .plan-progress {
        text-align: center;
    }

    .progress-circle {
        position: relative;
        width: 60px;
        height: 60px;
        margin-bottom: 8px;
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

    .plan-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .phases-section, .milestones-section, .description-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .phases-section h3, .milestones-section h3, .description-section h3 {
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .phases-list {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    .phase-item {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        overflow: hidden;
    }

    .phase-item.active {
        border-color: #6750A4;
        background: #fafafa;
    }

    .phase-item.completed {
        border-color: #4CAF50;
        background: #f1f8e9;
    }

    .phase-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: white;
    }

    .phase-item.active .phase-header {
        background: #f8f7ff;
    }

    .phase-item.completed .phase-header {
        background: #f1f8e9;
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

    .phase-item .phase-icon { background: #9e9e9e; }
    .phase-item.active .phase-icon { background: #6750A4; }
    .phase-item.completed .phase-icon { background: #4CAF50; }

    .phase-info {
        flex: 1;
    }

    .phase-info h4 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .phase-info p {
        margin: 0;
        color: #666;
        font-size: 14px;
    }

    .phase-status {
        text-align: right;
        min-width: 140px;
    }

    .progress-bar {
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 8px;
    }

    .progress-fill {
        height: 100%;
        background: #6750A4;
        transition: width 0.3s ease;
    }

    .phase-status span {
        font-size: 12px;
        font-weight: 600;
        color: #666;
    }

    .phase-details {
        padding: 20px;
        background: #fafafa;
        border-top: 1px solid #e0e0e0;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
    }

    .timeline {
        position: relative;
        padding-left: 40px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 32px;
    }

    .timeline-marker {
        position: absolute;
        left: -52px;
        top: 8px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e0e0e0;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .timeline-item.completed .timeline-marker {
        background: #4CAF50;
    }

    .timeline-item.active .timeline-marker {
        background: #6750A4;
    }

    .timeline-content h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .timeline-content p {
        margin: 0 0 12px 0;
        color: #666;
        font-size: 14px;
        line-height: 1.5;
    }

    .timeline-meta {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .timeline-date {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    .timeline-status {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .timeline-status.completed { background: #e8f5e8; color: #2e7d32; }
    .timeline-status.in-progress { background: #e3f2fd; color: #1565c0; }
    .timeline-status.pending { background: #fff3e0; color: #f57c00; }

    .resource-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }

    .resource-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .resource-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .budget-breakdown, .team-info {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .budget-item, .team-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .budget-item:last-child, .team-item:last-child {
        border-bottom: none;
    }

    .budget-progress {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }

    .budget-progress .progress-bar {
        flex: 1;
    }

    .budget-progress span {
        font-size: 12px;
        font-weight: 600;
        color: #6750A4;
        white-space: nowrap;
    }

    .priority-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-badge.high { background: #ffebee; color: #c62828; }

    .stats-list {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .stat-item {
        text-align: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .stat-value {
        font-size: 24px;
        font-weight: 600;
        color: #6750A4;
        margin-bottom: 4px;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        font-weight: 500;
    }

    .description-content p {
        margin: 0 0 20px 0;
        color: #333;
        line-height: 1.6;
    }

    .description-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 32px;
        margin-top: 20px;
    }

    .detail-column h4 {
        margin: 0 0 12px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .detail-column ul {
        margin: 0;
        padding-left: 20px;
        color: #666;
    }

    .detail-column li {
        margin-bottom: 8px;
        line-height: 1.5;
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

    .plan-actions, .other-actions {
        display: flex;
        gap: 16px;
    }

    .btn-primary, .btn-secondary, .btn-success, .btn-warning {
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

    .btn-warning {
        background: #FF9800;
        color: white;
    }

    .btn-warning:hover { background: #f57c00; }

    @media (max-width: 768px) {
        .plan-header { flex-direction: column; gap: 20px; }
        .plan-status { flex-direction: row; justify-content: center; }
        .resource-grid { grid-template-columns: 1fr; }
        .detail-grid { grid-template-columns: 1fr; }
        .description-details { grid-template-columns: 1fr; }
        .action-section { flex-direction: column; }
        .plan-actions, .other-actions { width: 100%; }
        .btn-primary, .btn-secondary, .btn-success, .btn-warning { width: 100%; justify-content: center; }
        .stats-list { grid-template-columns: 1fr; }
    }
</style>
@endpush

@push('scripts')
<script>
function generateReport() {
    alert('Project report generation functionality will be implemented');
}

function markMilestone() {
    alert('Milestone marking functionality will be implemented');
}

function updateProgress() {
    alert('Progress update functionality will be implemented');
}
</script>
@endpush
@endsection