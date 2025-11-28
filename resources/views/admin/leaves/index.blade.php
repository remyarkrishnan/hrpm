@extends('layouts.admin')
@section('title', 'Leave Management - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Leave Management')

@section('content')
<div class="page-header">
    <div>
        <p>Manage employee leave requests and approvals</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('admin.leaves.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            Apply for Leave
        </a>
    </div>
</div>

<!-- Leave Stats -->
<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="material-icons">pending</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalPendingLeaves }}</h3>
            <p>Pending Requests</p>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalApprovedLeaves }}</h3>
            <p>Approved </p>
        </div>
    </div>
   <div class="stat-card balance">
        <div class="stat-icon">
            <i class="material-icons">account_balance</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalRejectedLeaves }}</h3>
            <p>Rejected</p>
        </div>
    </div>


    <div class="stat-card on-leave">
        <div class="stat-icon">
            <i class="material-icons">event_available</i>
        </div>
        <div class="stat-info">
            <h3>{{ $todayLeaves }}</h3>
            <p>On Leave Today</p>
        </div>
    </div>

    
</div>

<!-- Leave Requests Table -->
<div class="leave-table-card">
    <h3>Leave Requests</h3>
    <div class="table-responsive">
        <table class="leave-table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Type</th>
                    <th>Duration</th>
                    <th>Applied Date</th>
                    <th>Assigned Manager </th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                <tr>
                    <td>
                        <div class="employee-info">
                            <div class="employee-avatar">{{ strtoupper(substr($leave->user->name, 0, 1)) }}</div>
                            <div>
                                <strong>{{ $leave->user->name }}</strong>
                                <small>{{ $leave->user->employee_code }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="leave-type">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</span>
                    </td>
                    <td>
                        <div class="leave-duration">
                            <strong>{{ round($leave->total_days) }} days</strong>
                            <small>{{ $leave->from_date->format('d-m-Y') }} to {{ $leave->to_date->format('d-m-Y') }}</small>
                        </div>
                    </td>
                    <td>{{ $leave->created_at->format('d-m-Y') }}</td>
                    <td>
                    @if($leave->assignedManager)
                        <span class="badge bg-success">{{ $leave->assignedManager->name ?? 'N/A' }}</span>
                    @elseif($leave->status === 'pending')
                        <select class="form-select form-select-sm manager-select" data-leave-id="{{ $leave->id }}">
                            <option value="">Select Manager</option>
                            @foreach($projectManagers as $manager)
                                <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                       <button class="btn-action btn-assign" title="Assign Manager" onclick="assignManager({{ $leave->id }})"><i class="material-icons">person_add</i></button>
                    @endif
                    </td>
                    <td>
                        <span class="status-badge status-{{ $leave->status }}">
                            {{ ucfirst(str_replace('_', ' ', $leave->status)) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.leaves.show', $leave->id) }}" class="btn-action">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($leave->status === 'approved_by_manager'|| $leave->status === 'rejected_by_manager')
                                <button class="btn-action btn-approve" onclick="approveLeave({{ $leave->id }})">
                                    <i class="material-icons">check</i>
                                </button>
                                <button class="btn-action btn-reject" onclick="rejectLeave({{ $leave->id }})">
                                    <i class="material-icons">close</i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No leave requests found</td>
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

    .stat-card.pending .stat-icon { background: #FF9800; }
    .stat-card.approved .stat-icon { background: #4CAF50; }
    .stat-card.on-leave .stat-icon { background: #2196F3; }
    .stat-card.balance .stat-icon { background: #9C27B0; }

    .stat-info h3 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
    }

    .stat-info p {
        margin: 4px 0 0 0;
        color: #666;
        font-size: 14px;
    }

    .leave-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .leave-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .leave-table {
        width: 100%;
        border-collapse: collapse;
    }

    .leave-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }

    .leave-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .employee-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .employee-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .leave-type {
        padding: 4px 12px;
        background: #f0f0f0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .leave-duration strong {
        display: block;
        margin-bottom: 2px;
    }

    .leave-duration small {
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

    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }

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
</style>
@endpush

@push('scripts')
<script>
function approveLeave(id) {
        const reason = prompt('Please enter remarks if any:');

        fetch(`/admin/leaves/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
             body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                 alert('Leave request approved successfully');
                location.reload();
            } else {
                alert('Error approving leave request');
            }
        });
  
}

function rejectLeave(id) {
    const reason = prompt('Please enter rejection reason:');
    if (reason) {
        fetch(`/admin/leaves/${id}/reject`, {
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
                 alert('Leave request rejected successfully');
                location.reload();
            } else {
                alert('Error rejecting leave request');
            }
        });
    }
}
</script>

<script>
function assignManager(leaveId) {
    const select = document.querySelector(`.manager-select[data-leave-id="${leaveId}"]`);
    const managerId = select.value;
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!managerId) {
        alert('Please select a manager before assigning.');
        return;
    }

    if (!confirm('Are you sure you want to assign this manager?')) {
        return;
    }

    fetch(`/admin/leaves/${leaveId}/assign-manager`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
        },
        body: JSON.stringify({ manager_id: managerId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Manager assigned successfully!');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(() => alert('An error occurred while assigning the manager.'));
}
</script>

@endpush
