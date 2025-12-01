@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee.approvals.index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee.approvals.index.page_title'))

@section('content')
<div class="page-header">
    <div>
        <h2>{{ __('employee.approvals.index.header_title') }}</h2>
        <p>{{ __('employee.approvals.index.header_description') }}</p>
    </div>
    <div class="header-actions">
        <div class="filter-dropdown">
            <select class="filter-select">
                <option value="">{{ __('employee.approvals.index.filters.all_projects') }}</option>
                <option value="residential">{{ __('employee.approvals.index.filters.residential') }}</option>
                <option value="commercial">{{ __('employee.approvals.index.filters.commercial') }}</option>
                <option value="infrastructure">{{ __('employee.approvals.index.filters.infrastructure') }}</option>
            </select>
        </div>
    </div>
</div>

<!-- Approval Stats -->
<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="material-icons">pending_actions</i>
        </div>
        <div class="stat-info">
            <h3>24</h3>
            <p>{{ __('employee.approvals.index.stats.pending_approvals') }}</p>
            <small>{{ __('employee.approvals.index.stats.pending_description') }}</small>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>156</h3>
            <p>{{ __('employee.approvals.index.stats.approved_steps') }}</p>
            <small>{{ __('employee.approvals.index.stats.approved_description') }}</small>
        </div>
    </div>

    <div class="stat-card in-progress">
        <div class="stat-icon">
            <i class="material-icons">hourglass_empty</i>
        </div>
        <div class="stat-info">
            <h3>8</h3>
            <p>{{ __('employee.approvals.index.stats.in_progress') }}</p>
            <small>{{ __('employee.approvals.index.stats.in_progress_description') }}</small>
        </div>
    </div>

    <div class="stat-card projects">
        <div class="stat-icon">
            <i class="material-icons">business</i>
        </div>
        <div class="stat-info">
            <h3>12</h3>
            <p>{{ __('employee.approvals.index.stats.active_projects') }}</p>
            <small>{{ __('employee.approvals.index.stats.active_projects_description') }}</small>
        </div>
    </div>
</div>

<!-- 12-Step Workflow Overview -->
<div class="workflow-overview">
    <h3>{{ __('employee.approvals.index.workflow.title') }}</h3>
    <div class="workflow-steps">
        <div class="workflow-step">
            <div class="step-number">1</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.1.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.1.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">2</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.2.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.2.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">3</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.3.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.3.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">4</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.4.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.4.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">5</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.5.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.5.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">6</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.6.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.6.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">7</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.7.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.7.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">8</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.8.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.8.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">9</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.9.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.9.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">10</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.10.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.10.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">11</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.11.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.11.description') }}</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">12</div>
            <div class="step-info">
                <h4>{{ __('employee.approvals.index.workflow.steps.12.title') }}</h4>
                <small>{{ __('employee.approvals.index.workflow.steps.12.description') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Current Approvals Table -->
<div class="approvals-table-card">
    <h3>{{ __('employee.approvals.index.table.title') }}</h3>
    <div class="table-responsive">
        <table class="approvals-table">
            <thead>
                <tr>
                    <th>{{ __('employee.approvals.index.table.columns.project') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.current_step') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.consultancy_type') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.due_date') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.status') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.responsible') }}</th>
                    <th>{{ __('employee.approvals.index.table.columns.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvals as $approval)
                <tr>
                    <td>
                        <div class="project-info">
                            <strong>{{ $approval->project_name }}</strong>
                            <small>{{ $approval->project_code }}</small>
                        </div>
                    </td>
                    <td>
                        <div class="step-info">
                            <span class="step-badge">{{ __('employee.approvals.index.table.step') }} {{ $approval->step_order }}</span>
                            <small>{{ $approval->step_name }}</small>
                        </div>
                    </td>
                    <td>
                        <span class="consultancy-type">{{ $approval->consultancy_type }}</span>
                    </td>
                    <td>{{ $approval->due_date }}</td>
                    <td>
                        <span class="status-badge status-{{ $approval->status }}">
                            {{ ucfirst(str_replace('_', ' ', $approval->status)) }}
                        </span>
                    </td>
                    <td>{{ $approval->responsible_person }}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.approvals.show', $approval->id) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($approval->status === 'pending')
                                <button class="btn-action btn-approve" onclick="approveStep({{ $approval->id }})">
                                    <i class="material-icons">check</i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectStep({{ $approval->id }})">
                                    <i class="material-icons">close</i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('employee.approvals.index.table.no_requests') }}</td>
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

    .filter-select {
        padding: 8px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        font-size: 14px;
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

    .stat-card.pending .stat-icon { background: #FF9800; }
    .stat-card.approved .stat-icon { background: #4CAF50; }
    .stat-card.in-progress .stat-icon { background: #2196F3; }
    .stat-card.projects .stat-icon { background: #9C27B0; }

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

    .workflow-overview {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .workflow-overview h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .workflow-steps {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .workflow-step {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
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
        font-size: 14px;
        flex-shrink: 0;
    }

    .step-info h4 {
        margin: 0 0 2px 0;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .step-info small {
        color: #666;
        font-size: 12px;
    }

    .approvals-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .approvals-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .approvals-table {
        width: 100%;
        border-collapse: collapse;
    }

    .approvals-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .approvals-table td {
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

    .step-badge {
        background: #6750A4;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 2px;
    }

    .step-info small {
        color: #666;
        font-size: 12px;
        display: block;
    }

    .consultancy-type {
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

    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }
    .status-in-progress { background: #e3f2fd; color: #1565c0; }

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

    .btn-approve:hover { background: #4CAF50; }
    .btn-reject:hover { background: #f44336; }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    @media (max-width: 768px) {
        .stats-grid, .workflow-steps { grid-template-columns: 1fr; }
        .workflow-steps { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; gap: 16px; }
    }
</style>
@endpush

@push('scripts')
<script>
function approveStep(approvalId) {
    const remarks = prompt('{{ __('employee.approvals.index.js.approve_prompt') }}');

    fetch(`/admin/approvals/${approvalId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ remarks: remarks })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('{{ __('employee.approvals.index.js.approve_success') }}');
            location.reload();
        } else {
            alert('{{ __('employee.approvals.index.js.approve_error') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __('employee.approvals.index.js.approve_error') }}');
    });
}

function rejectStep(approvalId) {
    const reason = prompt('{{ __('employee.approvals.index.js.reject_prompt') }}');
    if (!reason || reason.trim() === '') {
        alert('{{ __('employee.approvals.index.js.reject_required') }}');
        return;
    }

    fetch(`/admin/approvals/${approvalId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rejection_reason: reason })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('{{ __('employee.approvals.index.js.reject_success') }}');
            location.reload();
        } else {
            alert('{{ __('employee.approvals.index.js.reject_error') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ __('employee.approvals.index.js.reject_error') }}');
    });
}
</script>
@endpush