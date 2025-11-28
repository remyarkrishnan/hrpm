@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('leave.show.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('leave.show.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.leaves.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('leave.show.back') }}
        </a>
    </div>
    <div class="leave-header">
        <div class="leave-info">
            <h2>{{ $leave->user->name  }}</h2>
            <p>{{ $leave->user->employee_code  }} • {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</p>
            <div class="leave-duration">
                <i class="material-icons">date_range</i>
                <span>{{ $leave->from_date->format('d-m-Y') ?? '' }} {{ __('leave.show.to') }} {{ $leave->to_date->format('d-m-Y') ?? '' }}</span>
                <strong>({{ round($leave->total_days) }} {{ __('leave.show.days') }})</strong>
            </div>
        </div>
        <div class="leave-status">
            <span class="status-badge status-{{ $leave->status ?? 'pending' }}">
               {{ ucfirst(str_replace('_', ' ', $leave->status)) }}
            </span>
        </div>
    </div>
</div>

<div class="leave-details">
    <div class="detail-grid">
        <div class="detail-card">
            <h3>{{ __('leave.show.leave_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('leave.show.labels.leave_type') }}</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $leave->leave_type ?? '')) }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('leave.show.labels.applied_date') }}</strong>
                    <span>{{ $leave->created_at->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('leave.show.labels.start_date') }}</strong>
                    <span>{{ $leave->from_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('leave.show.labels.end_date') }}</strong>
                    <span>{{ $leave->to_date->format('d-m-Y') ?? '' }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('leave.show.labels.total_days') }}</strong>
                    <span>{{ round($leave->total_days) ?? '' }} {{ __('leave.show.days') }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>{{ __('leave.show.approval_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('leave.show.current_status') }}</strong>
                    <span class="status-badge status-{{ $leave->status ?? 'pending' }}">
                        {{ ucfirst(str_replace('_', ' ', $leave->status)) }}
                    </span>
                </div>
                @if($leave->assignedManager)
                <div class="info-item">
                    <strong>{{ __('leave.show.manager_assigned') }}</strong>
                    <span>{{ $leave->assignedManager->name ?? __('leave.show.na') }}</span>
                </div>
                @endif
                @if($leave->manager_remarks)
                <div class="info-item">
                    <strong>{{ __('leave.show.manager_remarks') }}</strong>
                    <span>{{ $leave->manager_remarks ?? __('leave.show.na') }}</span>
                </div>
                @endif
               @if(isset($leave->approved_by_account_and_hr))
                <div class="info-item">
                    <strong>{{ __('leave.show.approved_by_label') }}</strong>
                    <span>{{ $leave->approver->name }}</span>
                </div>
                @endif
                @if(isset($leave->approved_at))
                <div class="info-item">
                    <strong>{{ __('leave.show.approved_date_label') }}</strong>
                    <span>{{ $leave->approved_at->format('d-m-Y') }}</span>
                </div>
                @endif
                @if(isset($leave->accounts_and_hr_remarks))
                <div class="info-item">
                    <strong>{{ __('leave.show.accounts_hr_remarks') }}</strong>
                    <span class="rejection-reason">{{ $leave->accounts_and_hr_remarks }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="reason-section">
        <h3>{{ __('leave.show.leave_reason') }}</h3>
        <div class="reason-content">
            <p>{{ $leave->reason ?? '' }}</p>
        </div>
    </div>

    @php $documents = json_decode($leave->supporting_document ?? '[]', true); @endphp
    @if(isset($documents) && count($documents) > 0)
    <div class="documents-section">
        <h3>{{ __('leave.show.supporting_documents') }}</h3>
        <div class="documents-grid">
            @foreach($documents as $document)
            <div class="document-item">
                <div class="document-icon"><i class="material-icons">description</i></div>
                <div class="document-info">
                    <small>{{ __('leave.show.supporting_document') }}</small>
                </div>
                <div class="document-actions">
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" target="_blank" title="{{ __('leave.show.open') }}"><i class="material-icons">open_in_new</i></a>
                    <a href="{{ asset('storage/'.$document) }}" class="btn-action" download title="{{ __('leave.show.download') }}"><i class="material-icons">download</i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="action-section">
     @if($leave->status === 'approved_by_manager'|| $leave->status === 'rejected_by_manager')
        <div class="approval-actions">
            <button class="btn-approve" onclick="approveLeave()">
                <i class="material-icons">check_circle</i>
                {{ __('leave.show.approve_leave') }}
            </button>
            <button class="btn-reject" onclick="rejectLeave()">
                <i class="material-icons">cancel</i>
                {{ __('leave.show.reject_leave') }}
            </button>
        </div>
     @endif
        <div class="other-actions">
            <a href="{{ route('admin.leaves.edit', $leave->id) }}" class="btn-secondary">
                <i class="material-icons">edit</i>
                {{ __('leave.show.edit_request') }}
            </a>
            <button class="btn-danger" onclick="deleteLeave()">
                <i class="material-icons">delete</i>
                {{ __('leave.show.delete_request') }}
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function approveLeave() {
    const remarks = prompt(@json(__('leave.show.approve_prompt')));

    fetch(`/admin/leaves/{{ $leave->id }}/approve`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reason: remarks })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(@json(__('leave.show.approve_success')));
            location.reload();
        } else {
            alert(@json(__('leave.show.approve_error')));
        }
    })
    .catch(() => alert(@json(__('leave.show.approve_error'))));
}

function rejectLeave() {
    const reason = prompt(@json(__('leave.show.reject_prompt')));
    if (!reason || reason.trim() === '') {
        alert(@json(__('leave.show.reject_required')));
        return;
    }

    fetch(`/admin/leaves/{{ $leave->id }}/reject`, {
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
            alert(@json(__('leave.show.reject_success')));
            location.reload();
        } else {
            alert(@json(__('leave.show.reject_error')));
        }
    })
    .catch(() => alert(@json(__('leave.show.reject_error'))));
}

function deleteLeave() {
    if (confirm(@json(__('leave.show.delete_confirm')))) {
        fetch(`/admin/leaves/{{ $leave->id }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(@json(__('leave.show.delete_success')));
                window.location.href = '/admin/leaves';
            } else {
                alert(@json(__('leave.show.delete_error')));
            }
        })
        .catch(() => alert(@json(__('leave.show.delete_error'))));
    }
}
</script>
@endpush
