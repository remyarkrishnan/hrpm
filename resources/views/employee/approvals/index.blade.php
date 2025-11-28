@extends('layouts.admin')

@section('title', 'Project Approvals - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Project Approvals')

@section('content')
<div class="page-header">
    <div>
        <h2>Project Approvals</h2>
        <p>Manage 12-step approval workflow for construction projects</p>
    </div>
    <div class="header-actions">
        <div class="filter-dropdown">
            <select class="filter-select">
                <option value="">All Projects</option>
                <option value="residential">Residential</option>
                <option value="commercial">Commercial</option>
                <option value="infrastructure">Infrastructure</option>
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
            <p>Pending Approvals</p>
            <small>Across all projects</small>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>156</h3>
            <p>Approved Steps</p>
            <small>This month</small>
        </div>
    </div>

    <div class="stat-card in-progress">
        <div class="stat-icon">
            <i class="material-icons">hourglass_empty</i>
        </div>
        <div class="stat-info">
            <h3>8</h3>
            <p>In Progress</p>
            <small>Currently reviewing</small>
        </div>
    </div>

    <div class="stat-card projects">
        <div class="stat-icon">
            <i class="material-icons">business</i>
        </div>
        <div class="stat-info">
            <h3>12</h3>
            <p>Active Projects</p>
            <small>Requiring approvals</small>
        </div>
    </div>
</div>

<!-- 12-Step Workflow Overview -->
<div class="workflow-overview">
    <h3>Standard 12-Step Approval Process</h3>
    <div class="workflow-steps">
        <div class="workflow-step">
            <div class="step-number">1</div>
            <div class="step-info">
                <h4>Design Review</h4>
                <small>Architectural plans verification</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">2</div>
            <div class="step-info">
                <h4>Environmental Assessment</h4>
                <small>Environmental impact study</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">3</div>
            <div class="step-info">
                <h4>Safety Planning</h4>
                <small>Safety protocols approval</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">4</div>
            <div class="step-info">
                <h4>Structural Analysis</h4>
                <small>Engineering calculations review</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">5</div>
            <div class="step-info">
                <h4>Electrical Planning</h4>
                <small>Electrical systems design</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">6</div>
            <div class="step-info">
                <h4>Plumbing & HVAC</h4>
                <small>MEP systems approval</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">7</div>
            <div class="step-info">
                <h4>Financial Approval</h4>
                <small>Budget and cost approval</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">8</div>
            <div class="step-info">
                <h4>Legal Compliance</h4>
                <small>Legal requirements check</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">9</div>
            <div class="step-info">
                <h4>Municipal Permits</h4>
                <small>Government approvals</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">10</div>
            <div class="step-info">
                <h4>Fire Safety Clearance</h4>
                <small>Fire department approval</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">11</div>
            <div class="step-info">
                <h4>Quality Assurance</h4>
                <small>QA standards verification</small>
            </div>
        </div>
        <div class="workflow-step">
            <div class="step-number">12</div>
            <div class="step-info">
                <h4>Final Approval</h4>
                <small>Project commencement clearance</small>
            </div>
        </div>
    </div>
</div>

<!-- Current Approvals Table -->
<div class="approvals-table-card">
    <h3>Current Approval Requests</h3>
    <div class="table-responsive">
        <table class="approvals-table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Current Step</th>
                    <th>Consultancy Type</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Responsible</th>
                    <th>Actions</th>
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
                            <span class="step-badge">Step {{ $approval->step_order }}</span>
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
                    <td colspan="7" class="text-center">No approval requests found</td>
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
    const remarks = prompt('Add approval remarks (optional):');

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
            alert('Approval step approved successfully');
            location.reload();
        } else {
            alert('Error approving step');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error approving step');
    });
}

function rejectStep(approvalId) {
    const reason = prompt('Please enter rejection reason:');
    if (!reason || reason.trim() === '') {
        alert('Rejection reason is required');
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
            alert('Approval step rejected');
            location.reload();
        } else {
            alert('Error rejecting step');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rejecting step');
    });
}

// Filter functionality
document.querySelector('.filter-select').addEventListener('change', function() {
    // TODO: Implement filtering functionality
    console.log('Filter changed to:', this.value);
});
</script>
@endpush