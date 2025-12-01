@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee.documents.show.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee.documents.show.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.documents.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee.documents.show.back_to_documents') }}
        </a>
    </div>
</div>

<div class="document-details">
    <div class="detail-grid">
        <div class="detail-card">
            <h3>{{ __('employee.documents.show.document_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.document_type') }}</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $document->type ?? 'pending')) }}</span>
                </div>
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.applied_date') }}</strong>
                    <span>{{ $document->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <h3>{{ __('employee.documents.show.approval_information') }}</h3>
            <div class="info-list">
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.current_status') }}</strong>
                    <span class="status-badge status-{{ $document->status ?? 'pending' }}">
                        {{ ucfirst($document->status ?? 'Pending') }}
                    </span>
                </div>
                @if(isset($document->approved_by))
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.approved_by') }}</strong>
                    <span>{{ $document->approved_by }}</span>
                </div>
                @endif
                @if(isset($document->approved_at))
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.approved_date') }}</strong>
                    <span>{{ $document->approved_at }}</span>
                </div>
                @endif
                @if(isset($document->rejection_reason))
                <div class="info-item">
                    <strong>{{ __('employee.documents.show.rejection_reason') }}</strong>
                    <span class="rejection-reason">{{ $document->rejection_reason }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($document->status ?? 'pending' == 'pending')
    <div class="action-section">
        <div class="other-actions">
            <a href="{{ route('employee.documents.edit', $document->id ?? 1) }}" class="btn-secondary">
                <i class="material-icons">edit</i>
                {{ __('employee.documents.show.edit_request') }}
            </a>
            <button class="btn-danger" onclick="deleteDocument()">
                <i class="material-icons">delete</i>
                {{ __('employee.documents.show.delete_request') }}
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

    .document-details {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 24px;
    }
    .detail-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .detail-card h3 {
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

    .rejection-reason {
        color: #d32f2f;
        font-style: italic;
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
    .other-actions {
        display: flex;
        gap: 16px;
    }
    .btn-secondary, .btn-danger {
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
        .detail-grid { grid-template-columns: 1fr; }
        .action-section { flex-direction: column; }
        .other-actions { width: 100%; }
        .btn-secondary, .btn-danger { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteDocument() {
    if (confirm('{{ __("employee.documents.show.delete_confirm") }}')) {
        fetch(`/employee/documents/{{ $document->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("employee.documents.show.delete_success") }}');
                window.location.href = '/employee/documents';
            } else {
                alert('{{ __("employee.documents.show.delete_error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("employee.documents.show.delete_error") }}');
        });
    }
}
</script>
@endpush
