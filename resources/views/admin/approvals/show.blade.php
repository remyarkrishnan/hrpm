@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('approval.show.title', ['type' => $type, 'company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('approval.show.page_title', ['type' => $type]))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.approvals.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('approval.show.back_to_approvals') }}
        </a>
    </div>
    <div class="leave-header">
        <div class="leave-info">
            <h2>{{ $approval->user->name  }}</h2>
            @if($type == 'Leave') 
            <p>{{ $approval->user->employee_id  }} • {{ __('approval.show.leave') }}</p>
            <div class="leave-duration">
                <i class="material-icons">date_range</i>
                <span>{{ $approval->from_date->format('d-m-Y') ?? '' }} {{ __('approval.show.to') }} {{ $approval->to_date->format('d-m-Y') ?? ''  }}</span>
                <strong>({{ round($approval->total_days) ?? '' }} {{ __('approval.show.days') }})</strong>
            </div>
         @elseif($type == 'Loan')  
          <p>{{ $approval->user->employee_id  }} • {{ __('approval.show.loan') }}</p>
            <div class="leave-duration">
                <i class="material-icons">date_range</i>
                <span>{{ __('approval.show.currency_symbol') }} {{ number_format($approval->amount, 2) }}  / {{ str_replace('_',' ',$approval->repayment_duration) }}</span>
                <strong>{{ str_replace('_',' ',ucfirst($approval->purpose)) }}</strong>
            </div>
            @elseif($type == 'Document Request')  
          <p>{{ $approval->user->employee_id  }} • {{ __('approval.show.document_request') }}</p>
            
            @elseif($type == 'Training Request')  
          <p>{{ $approval->user->employee_id  }} • {{ __('approval.show.training_request') }}</p>
            
         @endif

        </div>
        <div class="leave-status">
            <span class="status-badge status-{{ $approval->status ?? 'pending' }}">
            {{ ucfirst(str_replace('_', ' ', $approval->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="leave-details">
    <!-- Leave Information -->
    <div class="detail-grid">
        @if($type == 'Leave') 
        <div class="detail-card">
            <h3>{{ __('approval.show.leave_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.leave_type') }}</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $approval->leave_type ?? '')) }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.applied_date') }}</strong>
                    <span>{{ $approval->created_at->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.start_date') }}</strong>
                    <span>{{ $approval->from_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.end_date') }}</strong>
                    <span>{{ $approval->to_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.total_days') }}</strong>
                    <span>{{ round($approval->total_days) ?? '' }} {{ __('approval.show.days') }}</span>
                </div>
            </div>
        </div>
     @elseif($type == 'Loan') 
<div class="detail-card">
            <h3>{{ __('approval.show.loan_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.loan_amount') }}:</strong>
                    <span>{{ __('approval.show.currency_symbol') }}{{ $approval->amount ?? ''}}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.purpose') }}:</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->purpose)) }}</span>
                </div>
                <div class="info-item">
                     <strong>{{ __('approval.show.labels.repayment_duration') }}:</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->repayment_duration)) }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.applied_date') }}:</strong>
                    <span>{{ $approval->created_at->format('d-m-Y') }}</span>
                </div>
                
            </div>
        </div>
        @elseif($type == 'Training Request') 
        <div class="detail-card">
            <h3>{{ __('approval.show.request_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.name') }}</strong>
                    <span>{{ $approval->name ?? ''}}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.location') }}</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->location)) }}</span>
                </div>
                <div class="info-item">
                     <strong>{{ __('approval.show.labels.duration') }}</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->duration)) }}</span>
                </div>
                <div class="info-item">
                     <strong>{{ __('approval.show.labels.benefit') }}</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->benefit)) }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.applied_date') }}:</strong>
                    <span>{{ $approval->created_at->format('d-m-Y') }}</span>
                </div>
                
            </div>
        </div>
       @elseif($type == 'Document Request') 
        <div class="detail-card">
            <h3>{{ __('approval.show.request_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.type') }}</strong>
                    <span>{{ ucwords(str_replace('_', ' ', $approval->type)) }}</span>
                </div>
             
                <div class="info-item">
                    <strong>{{ __('approval.show.labels.applied_date') }}:</strong>
                    <span>{{ $approval->created_at->format('d-m-Y') }}</span>
                </div>
                
            </div>
        </div>
       @endif
        <div class="detail-card">
            <h3>{{ __('approval.show.approval_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('approval.show.current_status') }}</strong>
                    <span class="status-badge status-{{ $leave->status ?? 'pending' }}">
                        {{ ucfirst(str_replace('_', ' ', $approval->status)) }}
                    </span>
                </div>
                @if($approval->assignedManager)
                <div class="info-item">
                    <strong>{{ __('approval.show.manager_assigned') }}</strong>
                    <span>{{ $approval->assignedManager->name ?? __('approval.show.na') }}</span>
                </div>
                @endif
                @if($approval->manager_remarks)
                <div class="info-item">
                    <strong>{{ __('approval.show.manager_remarks') }}</strong>
                    <span>{{ $approval->manager_remarks ?? __('approval.show.na') }}</span>
                </div>
                @endif
               @if(isset($approval->approved_by_account_and_hr))
                <div class="info-item">
                    <strong>{{ __('approval.show.approved_by_label') }}</strong>
                    <span>{{ $approval->approver->name }}</span>
                </div>
                @endif
                @if(isset($loan->approved_at)&&$loan->status == 'approved_by_account_and_hr')
                <div class="info-item">
                    <strong>{{ __('approval.show.approved_date_label') }}</strong>
                    <span>{{ $approval->approved_at->format('d-m-Y') }}</span>
                </div>
                @endif
                @if(isset($approval->accounts_and_hr_remarks))
                <div class="info-item">
                    <strong>{{ __('approval.show.accounts_hr_remarks') }}</strong>
                    <span class="rejection-reason">{{ $approval->accounts_and_hr_remarks }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>
    @if($type == 'Leave' || $type == 'Loan' )
    <!-- Leave Reason -->
    <div class="reason-section">
        <h3>@if($type == 'Leave') {{ __('approval.show.leave_reason') }} @else {{ __('approval.show.loan_reason') }} @endif</h3>
        <div class="reason-content">
            <p>{{ $approval->reason ?? '' }}</p>
        </div>
    </div>
    @endif 
 <!-- Supporting Documents -->
    @php
    $documents = json_decode($approval->supporting_document ?? '[]', true);
    @endphp
    @if(isset($documents) && count($documents) > 0)
    <div class="documents-section">
        <h3>{{ __('approval.show.supporting_documents') }}</h3>
        <div class="documents-grid">
            @foreach($documents as $document)
            <div class="document-item">
                <div class="document-icon">
                    <i class="material-icons">description</i>
                </div>
                <div class="document-info">
                   
                    <small>{{ __('approval.show.supporting_document') }}</small>
                </div>
                <div class="document-actions">
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" target="_blank" title="{{ __('approval.show.open') }}">
                        <i class="material-icons">open_in_new</i>
                    </a>
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" download title="{{ __('approval.show.download') }}">
                        <i class="material-icons">download</i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

  

  @if($type == 'Leave') 
    <!-- Leave Balance Impact -->
    <div class="balance-impact" style="display:none">
        <h3>{{ __('approval.show.leave_balance_impact') }}</h3>
        <div class="balance-grid">
            <div class="balance-item">
                <h4>{{ __('approval.show.before_leave') }}</h4>
                <div class="balance-value">8 {{ __('approval.show.days') }}</div>
                <small>{{ __('approval.show.available_balance') }}</small>
            </div>
            <div class="balance-arrow">
                <i class="material-icons">arrow_forward</i>
            </div>
            <div class="balance-item">
                <h4>{{ __('approval.show.after_leave') }}</h4>
                <div class="balance-value">5 {{ __('approval.show.days') }}</div>
                <small>{{ __('approval.show.remaining_balance') }}</small>
            </div>
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
    const remarks = prompt(@json(__('approval.show.approve_prompt')));

    fetch(`/admin/leaves/{{ $leave->id ?? 1 }}/approve`, {
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
            alert(@json(__('approval.show.approve_success')));
            location.reload();
        } else {
            alert(@json(__('approval.show.approve_error')));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(@json(__('approval.show.approve_error')));
    });
}

function rejectLeave() {
    const reason = prompt(@json(__('approval.show.reject_prompt')));
    if (!reason || reason.trim() === '') {
        alert(@json(__('approval.show.reject_required')));
        return;
    }

    fetch(`/admin/leaves/{{ $leave->id ?? 1 }}/reject`, {
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
            alert(@json(__('approval.show.reject_success')));
            location.reload();
        } else {
            alert(@json(__('approval.show.reject_error')));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(@json(__('approval.show.reject_error')));
    });
}

function deleteLeave() {
    if (confirm(@json(__('approval.show.delete_confirm')))) {
        fetch(`/admin/leaves/{{ $leave->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(@json(__('approval.show.delete_success')));
                window.location.href = '/admin/leaves';
            } else {
                alert(@json(__('approval.show.delete_error')));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert(@json(__('approval.show.delete_error')));
        });
    }
}
</script>
@endpush
