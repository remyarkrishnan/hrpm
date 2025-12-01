@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee.documents.index.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee.documents.index.page_title'))

@section('content')
<div class="page-header">
    <div>
        <h2>{{ __('employee.documents.index.header_title') }}</h2>
        <p>{{ __('employee.documents.index.header_description') }}</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('employee.documents.create') }}" class="btn-primary">
            <i class="material-icons">add</i>
            {{ __('employee.documents.index.request_document') }}
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card pending">
        <div class="stat-icon">
            <i class="material-icons">pending</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalPending ?? 0 }}</h3>
            <p>{{ __('employee.documents.index.stats.pending_requests') }}</p>
        </div>
    </div>

    <div class="stat-card approved">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-info">
            <h3>{{ $totalApproved ?? 0 }}</h3>
            <p>{{ __('employee.documents.index.stats.approved') }}</p>
        </div>
    </div>
</div>

<div class="document-table-card">
    <h3>{{ __('employee.documents.index.table_title') }}</h3>
    <div class="table-responsive">
        <table class="document-table">
            <thead>
                <tr>
                    <th>{{ __('employee.documents.index.table.columns.type') }}</th>
                    <th>{{ __('employee.documents.index.table.columns.applied_date') }}</th>
                    <th>{{ __('employee.documents.index.table.columns.status') }}</th>
                    <th>{{ __('employee.documents.index.table.columns.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                <tr>
                    <td><span class="document-type">{{ ucfirst(str_replace('_', ' ', $doc->type)) }}</span></td>
                    <td>{{ $doc->created_at->format('d-m-Y') }}</td>
                    <td>
                        <span class="status-badge status-{{ $doc->status }}">
                            {{ ucfirst($doc->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('employee.documents.show', $doc->id) }}" class="btn-action" title="{{ __('employee.documents.index.view_details') }}">
                                <i class="material-icons">visibility</i>
                            </a>
                            @if($doc->status == 'pending')
                            <button class="btn-action btn-reject" onclick="deleteDocument({{ $doc->id }})" title="{{ __('employee.documents.index.delete_request') }}">
                                <i class="material-icons">close</i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">{{ __('employee.documents.index.no_requests') }}</td>
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
    .header-actions {
        display: flex;
        gap: 12px;
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

    .document-table-card {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .document-table-card h3 {
        margin: 0 0 20px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .table-responsive {
        overflow-x: auto;
    }
    .document-table {
        width: 100%;
        border-collapse: collapse;
    }
    .document-table th {
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid #f0f0f0;
        font-weight: 600;
        color: #333;
    }
    .document-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .document-type {
        padding: 4px 12px;
        background: #f0f0f0;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
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
    .btn-reject:hover {
        background: #f44336;
    }

    .text-center {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr; }
        .page-header { flex-direction: column; gap: 16px; }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteDocument(id) {
    if (confirm('{{ __("employee.documents.index.delete_confirm") }}')) {
        fetch(`/employee/documents/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('{{ __("employee.documents.index.delete_success") }}');
                window.location.href = '/employee/documents';
            } else {
                alert('{{ __("employee.documents.index.delete_error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("employee.documents.index.delete_error") }}');
        });
    }
}
</script>
@endpush
