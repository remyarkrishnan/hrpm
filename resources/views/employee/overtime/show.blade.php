@extends('layouts.admin')

@section('title', 'Overtime Details - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Overtime Details')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.overtime.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Overtime
        </a>
    </div>
    <div class="overtime-header">
        <div class="overtime-info">
            <h2>{{ $overtime->employee_name ?? 'Rajesh Kumar' }}</h2>
            <p>{{ $overtime->employee_code ?? 'EMP-001' }} • Overtime Request</p>
            <div class="overtime-date">
                <i class="material-icons">date_range</i>
                <span>{{ $overtime->date ?? '2025-10-07' }}</span>
                <strong>{{ $overtime->hours ?? '2.5' }} hours</strong>
            </div>
        </div>
        <div class="overtime-status">
            <span class="status-badge status-{{ $overtime->status ?? 'approved' }}">
                {{ ucfirst($overtime->status ?? 'Approved') }}
            </span>
            <div class="overtime-amount">
                <strong>₹{{ number_format(($overtime->hours ?? 2.5) * 750, 2) }}</strong>
                <small>Estimated Pay</small>
            </div>
        </div>
    </div>
</div>

<div class="overtime-details">
    <!-- Overtime Information -->
    <div class="detail-grid">
        <div class="detail-card">
            <h3>Overtime Information</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>Date:</strong>
                    <span>{{ $overtime->date ?? '2025-10-07' }}</span>
                </div>
                <div class="info-item">
                    <strong>Hours:</strong>
                    <span>{{ $overtime->hours ?? '2.5' }} hours</span>
                </div>
                <div class="info-item">
                    <strong>Start Time:</strong>
                    <span>{{ $overtime->start_time ?? '18:00' }}</span>
                </div>
                <div class="info-item">
                    <strong>End Time:</strong>
                    <span>{{ $overtime->end_time ?? '20:30' }}</span>
                </div>
                <div class="info-item">
                    <strong>Project:</strong>
                    <span>{{ $overtime->project ?? 'Residential Complex - Phase 2' }}</span>
                </div>
                <div class="info-item">
                    <strong>Urgency Level:</strong>
                    <span class="urgency-badge urgency-{{ $overtime->urgency_level ?? 'urgent' }}">
                        {{ ucfirst($overtime->urgency_level ?? 'Urgent') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>Payment Calculation</h3>
            <div class="payment-breakdown">
                <div class="payment-item">
                    <span>Base Hourly Rate:</span>
                    <strong>₹500.00</strong>
                </div>
                <div class="payment-item">
                    <span>Overtime Multiplier:</span>
                    <strong>1.5x</strong>
                </div>
                <div class="payment-item">
                    <span>Overtime Rate:</span>
                    <strong>₹750.00/hr</strong>
                </div>
                <div class="payment-item">
                    <span>Total Hours:</span>
                    <strong>{{ $overtime->hours ?? '2.5' }} hrs</strong>
                </div>
                <div class="payment-item total">
                    <span>Total Amount:</span>
                    <strong>₹{{ number_format(($overtime->hours ?? 2.5) * 750, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Work Description -->
    <div class="description-section">
        <h3>Reason & Work Description</h3>
        <div class="description-content">
            <div class="reason-card">
                <h4>Reason for Overtime</h4>
                <p>{{ $overtime->reason ?? 'Project deadline approaching. Additional foundation work required to meet construction schedule. Critical phase completion needed.' }}</p>
            </div>

            @if(isset($overtime->work_description))
            <div class="work-card">
                <h4>Work Description</h4>
                <p>{{ $overtime->work_description }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Approval Information -->
    @if(isset($overtime->approved_by) || ($overtime->status ?? 'pending') !== 'pending')
    <div class="approval-section">
        <h3>Approval Information</h3>
        <div class="approval-details">
            <div class="approval-item">
                <strong>Status:</strong>
                <span class="status-badge status-{{ $overtime->status ?? 'approved' }}">
                    {{ ucfirst($overtime->status ?? 'Approved') }}
                </span>
            </div>

            @if(isset($overtime->approved_by))
            <div class="approval-item">
                <strong>Approved By:</strong>
                <span>{{ $overtime->approved_by ?? 'Project Manager Singh' }}</span>
            </div>
            @endif

            @if(isset($overtime->approved_at))
            <div class="approval-item">
                <strong>Approved Date:</strong>
                <span>{{ $overtime->approved_at ?? '2025-10-08 09:30 AM' }}</span>
            </div>
            @endif

            @if(isset($overtime->remarks))
            <div class="approval-item full-width">
                <strong>Remarks:</strong>
                <p>{{ $overtime->remarks }}</p>
            </div>
            @endif

            @if(isset($overtime->rejection_reason))
            <div class="approval-item full-width">
                <strong>Rejection Reason:</strong>
                <p class="rejection-reason">{{ $overtime->rejection_reason }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Time Tracking -->
    <div class="tracking-section">
        <h3>Time Tracking Details</h3>
        <div class="tracking-grid">
            <div class="tracking-card">
                <div class="tracking-icon">
                    <i class="material-icons">schedule</i>
                </div>
                <div class="tracking-info">
                    <h4>Regular Hours</h4>
                    <div class="tracking-value">8h 00m</div>
                    <small>09:00 AM - 06:00 PM</small>
                </div>
            </div>

            <div class="tracking-card overtime-card">
                <div class="tracking-icon">
                    <i class="material-icons">more_time</i>
                </div>
                <div class="tracking-info">
                    <h4>Overtime Hours</h4>
                    <div class="tracking-value">{{ $overtime->hours ?? '2.5' }}h</div>
                    <small>{{ $overtime->start_time ?? '18:00' }} - {{ $overtime->end_time ?? '20:30' }}</small>
                </div>
            </div>

            <div class="tracking-card">
                <div class="tracking-icon">
                    <i class="material-icons">today</i>
                </div>
                <div class="tracking-info">
                    <h4>Total Working</h4>
                    <div class="tracking-value">{{ 8 + ($overtime->hours ?? 2.5) }}h</div>
                    <small>Including overtime</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    @if(($overtime->status ?? 'pending') === 'pending')
    <div class="action-section">
        <div class="approval-actions">
            <button class="btn-approve" onclick="approveOvertime()">
                <i class="material-icons">check_circle</i>
                Approve Overtime
            </button>
            <button class="btn-reject" onclick="rejectOvertime()">
                <i class="material-icons">cancel</i>
                Reject Overtime
            </button>
        </div>

        <div class="other-actions">
            <a href="{{ route('admin.overtime.edit', $overtime->id ?? 1) }}" class="btn-secondary">
                <i class="material-icons">edit</i>
                Edit Request
            </a>
            <button class="btn-danger" onclick="deleteOvertime()">
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

    .overtime-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        background: white;
        padding: 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 32px;
    }

    .overtime-info h2 {
        margin: 0 0 8px 0;
        font-size: 24px;
        font-weight: 500;
    }

    .overtime-info p {
        margin: 0 0 12px 0;
        color: #666;
    }

    .overtime-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6750A4;
        font-weight: 500;
    }

    .overtime-status {
        text-align: right;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 16px;
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 12px;
    }

    .status-pending { background: #fff3e0; color: #f57c00; }
    .status-approved { background: #e8f5e8; color: #2e7d32; }
    .status-rejected { background: #ffebee; color: #c62828; }

    .urgency-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .urgency-normal { background: #e0e0e0; color: #666; }
    .urgency-urgent { background: #fff3e0; color: #f57c00; }
    .urgency-critical { background: #ffebee; color: #c62828; }

    .overtime-amount strong {
        display: block;
        font-size: 20px;
        color: #1C1B1F;
    }

    .overtime-amount small {
        color: #666;
        font-size: 12px;
    }

    .overtime-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }

    .detail-card, .description-section, .approval-section, .tracking-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .detail-card h3, .description-section h3, .approval-section h3, .tracking-section h3 {
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

    .payment-breakdown {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .payment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .payment-item:last-child { border-bottom: none; }

    .payment-item.total {
        background: #f8f9fa;
        padding: 16px;
        border-radius: 8px;
        margin-top: 8px;
        border: none;
    }

    .payment-item.total strong {
        color: #6750A4;
        font-size: 18px;
    }

    .description-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .reason-card, .work-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #6750A4;
    }

    .reason-card h4, .work-card h4 {
        margin: 0 0 12px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .reason-card p, .work-card p {
        margin: 0;
        line-height: 1.6;
        color: #333;
    }

    .approval-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 16px;
    }

    .approval-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .approval-item.full-width {
        grid-column: 1 / -1;
    }

    .rejection-reason {
        color: #d32f2f;
        font-style: italic;
    }

    .tracking-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .tracking-card {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .tracking-card.overtime-card {
        background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
        border-color: #6750A4;
    }

    .tracking-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .tracking-info h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .tracking-value {
        font-size: 24px;
        font-weight: 600;
        color: #6750A4;
        margin-bottom: 4px;
    }

    .tracking-info small {
        color: #666;
        font-size: 12px;
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
        .overtime-header { flex-direction: column; gap: 20px; }
        .detail-grid, .approval-details, .tracking-grid { grid-template-columns: 1fr; }
        .action-section { flex-direction: column; }
        .approval-actions, .other-actions { width: 100%; }
        .btn-approve, .btn-reject, .btn-secondary, .btn-danger { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
function approveOvertime() {
    const remarks = prompt('Add approval remarks (optional):');

    fetch(`/admin/overtime/{{ $overtime->id ?? 1 }}/approve`, {
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
            alert('Overtime request approved successfully');
            location.reload();
        } else {
            alert('Error approving overtime request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error approving overtime request');
    });
}

function rejectOvertime() {
    const reason = prompt('Please enter rejection reason:');
    if (!reason || reason.trim() === '') {
        alert('Rejection reason is required');
        return;
    }

    fetch(`/admin/overtime/{{ $overtime->id ?? 1 }}/reject`, {
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
            alert('Overtime request rejected');
            location.reload();
        } else {
            alert('Error rejecting overtime request');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error rejecting overtime request');
    });
}

function deleteOvertime() {
    if (confirm('Are you sure you want to delete this overtime request?\n\nThis action cannot be undone.')) {
        fetch(`/admin/overtime/{{ $overtime->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Overtime request deleted successfully');
                window.location.href = '/admin/overtime';
            } else {
                alert('Error deleting overtime request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting overtime request');
        });
    }
}
</script>
@endpush