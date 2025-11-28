@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('approval.index.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('approval.index.page_title'))

@section('content')
<div class="page-header">
    <div>
      
        <p class="text-muted">{{ __('approval.index.description') }}</p>
    </div>
    <div class="header-actions" style="display:none">
        <div class="filter-dropdown">
            <select class="filter-select">
                <option value="">{{ __('approval.index.filter_all_projects') }}</option>
                <option value="residential">{{ __('approval.index.projects.residential') }}</option>
                <option value="commercial">{{ __('approval.index.projects.commercial') }}</option>
                <option value="infrastructure">{{ __('approval.index.projects.infrastructure') }}</option>
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
            <h3>{{ $approvals->where('status', 'pending')->count() }}</h3>
            <p>{{ __('approval.index.stats_pending') }}</p>
           
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $approvals->where('status', 'approved_by_account_and_hr')->count() }}</h3>
            <p>{{ __('approval.index.stats_approved') }}</p>
           
        </div>
    </div>


    <div class="stat-card projects">
        <div class="stat-icon">
            <i class="material-icons">cancel</i>
        </div>
        <div class="stat-info">
            <h3>{{ $approvals->where('status', 'rejected_by_account_and_hr')->count() }}</h3>
            <p>{{ __('approval.index.stats_rejected') }}</p>
        </div>
    </div>
</div>

<!-- Current Approvals Table -->
<div class="approvals-table-card">
    <h3>{{ __('approval.index.current_requests') }}</h3>
    <div class="table-responsive">
        <table class="approvals-table">
            <thead>
                <tr>
                    <th>{{ __('approval.index.table.employee') }}</th>
                    <th>{{ __('approval.index.table.type') }}</th>
                    <th>{{ __('approval.index.table.applied_on') }}</th>
                    <th>{{ __('approval.index.table.details') }}</th>
                    <th>{{ __('approval.index.table.manager_assigned') }}</th>
                    <th>{{ __('approval.index.table.status') }}</th>
                    <th>{{ __('approval.index.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($approvals as $approval)
                <tr>
                    <td>
                         <div class="employee-info">
                            
                            <div>
                                <strong>{{ $approval->employee }}</strong>
                            </div>
                        </div>

                    </td>
                    <td>
                        {{ $approval->type }}
                    </td>
                    <td>
                       
                        <span class="consultancy-type">{{ $approval->applied_on }}</span>
                    </td>
                    <td> {{ $approval->details }}</td>
                    <td>
                    @if($approval->manager)
                        <span class="badge bg-success">{{ $approval->manager ?? __('approval.index.na') }}</span>
                    @elseif($approval->status === 'pending')
                        <select class="form-select form-select-sm manager-select" data-approval-id="{{ $approval->id }}">
                            <option value="">{{ __('approval.index.select_manager') }}</option>
                            @foreach($projectManagers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn-action btn-assign" title="{{ __('approval.index.assign_manager') }}" onclick="assignManager('{{ $approval->type }}',{{ $approval->id }})"><i class="material-icons">person_add</i></button>
                    @endif
                    </td>
                     <td>
                            <span class="status-badge status-{{ strtolower($approval->status) }}">
                                {{ ucfirst(str_replace('_', ' ', $approval->status)) }}
                            </span>
                        </td>
                    <td>
                        <div class="action-buttons">
                            <a href="javascript:void(0)" class="btn-action" onclick="viewApproval('{{ $approval->type }}', {{ $approval->id }})">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($approval->status === 'approved_by_manager'|| $approval->status === 'rejected_by_manager')
                                <button class="btn-action btn-approve" onclick="approveStep('{{ $approval->type }}',{{ $approval->id }})">
                                    <i class="material-icons">check</i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectStep('{{ $approval->type }}',{{ $approval->id }})">
                                    <i class="material-icons">close</i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">{{ __('approval.index.no_requests') }}</td>
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
        margin-bottom: 32px;
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
function approveStep(type,approvalId) {
    if (!confirm(@json(__('approval.index.approve_confirm')))) {
        return; // Exit if user cancels
    }
    const reason = prompt(@json(__('approval.index.approve_prompt')));
    fetch(`/admin/approvals/${approvalId}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ remarks: reason,type: type })
    })
    .then(res => res.json())
    .then data => {
        alert(data.message);
        if (data.success) location.reload();
    })
    .catch(err => {
        console.error('Error approving request:', err);
        alert(@json(__('approval.index.approve_error')));
    });
}


function rejectStep(type,approvalId) {
    const reason = prompt(@json(__('approval.index.reject_prompt')));
    if (!reason) { alert(@json(__('approval.index.reject_required'))); return; }

    fetch(`/admin/approvals/${approvalId}/reject`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ rejection_reason: reason ,type: type})
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    });
}

function viewApproval(type, id) {
    if(type === 'Leave') {
        window.location.href = '/admin/approvals/leave/' + id;
    } else if(type === 'Loan') {
        window.location.href = '/admin/approvals/loan/' + id;
    }else if(type === 'Training Request') {
        window.location.href = '/admin/approvals/training/' + id;
    }else if(type === 'Document Request') {
        window.location.href = '/admin/approvals/document/' + id;
    } else {
        alert(@json(__('approval.index.unknown_type')));
    }
}

function assignManager(type,approvalId) {
    const select = document.querySelector(`.manager-select[data-approval-id="${approvalId}"]`);
    const managerId = select.value;
   
    if (!managerId) {
        alert(@json(__('approval.index.select_manager_required')));
        return;
    }

    if (!confirm(@json(__('approval.index.assign_manager_confirm')))) {
        return;
    }
     fetch(`/admin/approvals/${approvalId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({  manager_id: managerId, type: type })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(@json(__('approval.index.manager_assigned_success')));
             location.reload();
         } else {
            alert(data.message);
         }
     })
     .catch(() => alert(@json(__('approval.index.assign_error'))));
}

// Employee filter
document.querySelector('.filter-select').addEventListener('change', function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll('.approvals-table tbody tr').forEach(tr => {
        const emp = tr.querySelector('td:nth-child(2)').innerText.toLowerCase();
        tr.style.display = emp.includes(value) || value === '' ? '' : 'none';
    });
});
</script>
@endpush
