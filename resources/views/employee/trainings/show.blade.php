@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.employee')

@section('title', __('employee/trainings/show.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/trainings/show.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.trainings.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/trainings/common.actions.back') }}
        </a>
    </div>
</div>

<div class="leave-details">
    <div class="detail-grid">
        <div class="detail-card">
            <h3>{{ __('employee/trainings/show.sections.info') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.name') }}:</strong>
                    <span>{{ $training->name }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.location') }}:</strong>
                    <span>{{ $training->location }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.duration') }}:</strong>
                    <span>{{ $training->duration }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.benefit') }}:</strong>
                    <span>{{ $training->benefit }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.applied_date') }}:</strong>
                    <span>{{ $training->created_at }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>{{ __('employee/trainings/show.sections.approval') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.current_status') }}:</strong>
                    <span class="status-badge status-{{ $training->status ?? 'pending' }}">
                        {{ ucfirst($training->status ?? 'Pending') }}
                    </span>
                </div>
                @if(isset($training->approved_by))
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.approved_by') }}:</strong>
                    <span>{{ $training->approved_by }}</span>
                </div>
                @endif
                @if(isset($training->approved_at))
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.approved_date') }}:</strong>
                    <span>{{ $training->approved_at }}</span>
                </div>
                @endif
                @if(isset($training->rejection_reason))
                <div class="info-item">
                    <strong>{{ __('employee/trainings/show.fields.rejection_reason') }}:</strong>
                    <span class="rejection-reason">{{ $training->rejection_reason }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if(isset($leave->documents) && count($leave->documents) > 0)
    <div class="documents-section">
        <h3>{{ __('employee/trainings/common.labels.supporting_docs') }}</h3>
        <div class="documents-grid">
            @foreach($leave->documents as $document)
            <div class="document-item">
                <div class="document-icon">
                    <i class="material-icons">description</i>
                </div>
                <div class="document-info">
                    <strong>{{ $document }}</strong>
                    <small>{{ __('employee/trainings/common.labels.supporting_doc_label') }}</small>
                </div>
                <div class="document-actions">
                    <a href="#" class="btn-action" target="_blank" title="{{ __('employee/trainings/common.actions.open_new') }}">
                        <i class="material-icons">open_in_new</i>
                    </a>
                    <a href="#" class="btn-action" download title="{{ __('employee/trainings/common.actions.download') }}">
                        <i class="material-icons">download</i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(($leave->status ?? 'pending') === 'pending')
    <div class="action-section">
        <div class="other-actions">
            <a href="{{ route('employee.trainings.edit', $training->id ?? 1) }}" class="btn-secondary">
                <i class="material-icons">edit</i>
                {{ __('employee/trainings/common.actions.edit') }}
            </a>
            <button class="btn-danger" onclick="deleteTraining()">
                <i class="material-icons">delete</i>
                {{ __('employee/trainings/common.actions.delete') }}
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

function deleteTraining() {
    if (confirm('Are you sure you want to delete this training request?\n\nThis action cannot be undone.')) {
        fetch(`/employee/trainings/{{ $training->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Training request deleted successfully');
                window.location.href = '/employee/trainings';
            } else {
                alert('Error deleting training request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting training request');
        });
    }
}
</script>
@endpush
