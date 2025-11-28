@extends('layouts.employee')

@section('title', 'Leave Request Details - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee dashboard')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.leaves.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Leaves
        </a>
    </div>
  
</div>

<div class="leave-details">
    <!-- Leave Information -->
    <div class="detail-grid">
        <div class="detail-card">
            <h3>Leave Information</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>Leave Type:</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $leave->leave_type ?? 'sick_leave')) }}</span>
                </div>
                <div class="info-item">
                    <strong>Applied Date:</strong>
                    <span>{{ $leave->created_at->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>Start Date:</strong>
                    <span>{{ $leave->from_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>End Date:</strong>
                    <span>{{ $leave->to_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>Total Days:</strong>
                    <span>{{ round($leave->total_days) ?? 0}} days</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>Approval Information</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>Current Status:</strong>
                    <span class="status-badge status-{{ $leave->status ?? 'pending' }}">
                        {{ ucfirst($leave->status ?? 'Pending') }}
                    </span>
                </div>
                @if(isset($leave->approved_by))
                <div class="info-item">
                    <strong>@if($leave->status == 'approved') Approved @else Rejected @endif By:</strong>
                    <span>{{ $leave->approver->name }}</span>
                </div>
                @endif
                @if(isset($leave->approved_at))
                <div class="info-item">
                    <strong>@if($leave->status == 'approved') Approved @else Rejected @endif Date:</strong>
                    <span>{{ $leave->approved_at->format('d-m-Y') }}</span>
                </div>
                @endif
                 @if(isset($leave->remarks)&&$leave->status == 'rejected')
                <div class="info-item">
                    <strong>Rejection Reason:</strong>
                    <span class="rejection-reason">{{ $leave->remarks}}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Leave Reason -->
    <div class="reason-section">
        <h3>Leave Reason</h3>
        <div class="reason-content">
            <p>{{ $leave->reason ?? '' }}</p>
        </div>
    </div>

    <!-- Supporting Documents -->
    @php
    $documents = json_decode($leave->supporting_document ?? '[]', true);
    @endphp
    @if(isset($documents) && count($documents) > 0)
    <div class="documents-section">
        <h3>Supporting Documents</h3>
        <div class="documents-grid">
            @foreach($documents as $document)
            <div class="document-item">
                <div class="document-icon">
                    <i class="material-icons">description</i>
                </div>
                <div class="document-info">
                   
                    <small>Supporting Document</small>
                </div>
                <div class="document-actions">
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" target="_blank">
                        <i class="material-icons">open_in_new</i>
                    </a>
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" download>
                        <i class="material-icons">download</i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Leave Balance Impact -->
    <div class="balance-impact">
        <h3>Leave Balance Impact</h3>
        <div class="balance-grid">
            <div class="balance-item">
                <h4>Before Leave</h4>
                <div class="balance-value">8 days</div>
                <small>Available Balance</small>
            </div>
            <div class="balance-arrow">
                <i class="material-icons">arrow_forward</i>
            </div>
            <div class="balance-item">
                <h4>After Leave</h4>
                <div class="balance-value">5 days</div>
                <small>Remaining Balance</small>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if(($leave->status ?? 'pending') === 'pending')
    <div class="action-section">
      

        <div class="other-actions">
            <a href="{{ route('employee.leaves.edit', $leave->id ) }}" class="btn-secondary">
                <i class="material-icons">edit</i>
                Edit Request
            </a>
            <button class="btn-danger" onclick="deleteLeave()">
                <i class="material-icons">delete</i>
                Delete Request
            </button>
        </div>
    </div>
    @endif
</div>
@endsection

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

    .leave-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .leave-info h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .leave-info p {
        margin: 0 0 12px 0;
        color: #666;
    }

    .leave-duration {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        font-weight: 500;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }

    .leave-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .detail-card, .reason-section, .documents-section, .balance-impact {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .detail-card h3, .reason-section h3, .documents-section h3, .balance-impact h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
        color: #1C1B1F;
    }

    .info-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-item:last-child { border-bottom: none; }

    .rejection-reason {
        color: #d32f2f;
        font-style: italic;
    }

    .reason-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #6750A4;
    }

    .reason-content p {
        margin: 0;
        line-height: 1.6;
        color: #333;
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
        margin-bottom: 2px;
    }

    .document-info small {
        color: #666;
        font-size: 12px;
    }

    .document-actions {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        width: 36px;
        height: 36px;
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

    .balance-grid {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 32px;
        flex-wrap: wrap;
    }

    .balance-item {
        text-align: center;
        padding: 24px;
        background: #f8f9fa;
        border-radius: 12px;
        min-width: 150px;
    }

    .balance-item h4 {
        margin: 0 0 12px 0;
        font-size: 16px;
        color: #666;
    }

    .balance-value {
        font-size: 32px;
        font-weight: 600;
        color: #6750A4;
        margin-bottom: 8px;
    }

    .balance-arrow {
        color: #6750A4;
        font-size: 32px;
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

    .approval-actions, .other-actions {
        display: flex;
        gap: 16px;
    }

    .btn-approve, .btn-reject, .btn-secondary, .btn-danger {
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

    .btn-approve {
        background: #4CAF50;
        color: white;
    }

    .btn-approve:hover { background: #45a049; }

    .btn-reject {
        background: #f44336;
        color: white;
    }

    .btn-reject:hover { background: #d32f2f; }

    .btn-secondary {
        background: #f5f5f5;
        color: #666;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
        color: #333;
    }

    .btn-danger {
        background: #ffebee;
        color: #d32f2f;
    }

    .btn-danger:hover {
        background: #d32f2f;
        color: white;
    }

    @media (max-width: 768px) {
        .leave-header { flex-direction: column; gap: 20px; }
        .detail-grid { grid-template-columns: 1fr; }
        .balance-grid { flex-direction: column; }
        .action-section { flex-direction: column; }
        .approval-actions, .other-actions { width: 100%; }
        .btn-approve, .btn-reject, .btn-secondary, .btn-danger { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
function approveLeave() {
    const remarks = prompt('Add approval remarks (optional):');

    fetch(`/employee/leaves/{{ $leave->id ?? 1 }}/approve`, {
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
            alert('Leave request approved successfully');
            location.reload();
        } else {
            alert('Error approving leave request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error approving leave request');
    });
}

function rejectLeave() {
    const reason = prompt('Please enter rejection reason:');
    if (!reason || reason.trim() === '') {
        alert('Rejection reason is required');
        return;
    }

    fetch(`/employee/leaves/{{ $leave->id ?? 1 }}/reject`, {
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
            alert('Leave request rejected');
            location.reload();
        } else {
            alert('Error rejecting leave request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rejecting leave request');
    });
}

function deleteLeave() {
    if (confirm('Are you sure you want to delete this leave request?\n\nThis action cannot be undone.')) {
        fetch(`/employee/leaves/{{ $leave->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Leave request deleted successfully');
                window.location.href = '/employee/leaves';
            } else {
                alert('Error deleting leave request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting leave request');
        });
    }
}
</script>
@endpush
