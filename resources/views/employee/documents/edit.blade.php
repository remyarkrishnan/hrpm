@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee/documents/edit.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/documents/edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.documents.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/documents/edit.back_to_documents') }}
        </a>
        <a href="{{ route('employee.documents.show', $document->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('employee/documents/edit.view_details') }}
        </a>
    </div>
    <div>
        <h2>{{ __('employee/documents/edit.header_title') }}</h2>
        <p>{{ __('employee/documents/edit.header_description') }}</p>
    </div>
</div>

<div class="form-container">
    <form action="{{ route('employee.documents.update', $document->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="document-form">
        @csrf
        @method('PUT')
        
        <div class="current-status">
            <div class="status-info">
                <div class="employee-avatar">{{ strtoupper(substr(auth()->user()->name ?? '', 0, 1)) }}</div>
                <div>
                    <h3>{{ auth()->user()->name ?? '' }}</h3>
                    <p>{{ $document->status ?? 'pending' }}</p>
                </div>
            </div>
            <span class="status-badge status-{{ $document->status ?? 'pending' }}">
                {{ ucfirst($document->status ?? 'Pending') }}
            </span>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                {{ __('employee/documents/edit.update_document_info') }}
            </h3>
            <div class="form-grid">
                <div class="form-group">
                    <label for="type">{{ __('employee/documents/edit.document_type_label') }}</label>
                    <select id="type" name="type" required>
                        <option value="id_letter" {{ ($document->type ?? 'id_letter') == 'id_letter' ? 'selected' : '' }}>
                            {{ __('employee/documents/edit.id_letter') }}
                        </option>
                        <option value="experience" {{ ($document->type ?? '') == 'experience' ? 'selected' : '' }}>
                            {{ __('employee/documents/edit.experience_certificate') }}
                        </option>
                        <option value="salary_slip" {{ ($document->type ?? '') == 'salary_slip' ? 'selected' : '' }}>
                            {{ __('employee/documents/edit.salary_slip') }}
                        </option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('employee/documents/edit.update_button') }}
            </button>
            <a href="{{ route('employee.documents.show', $document->id ?? 1) }}" class="btn-cancel">
                {{ __('employee/documents/edit.cancel_button') }}
            </a>
            <button type="button" class="btn-danger" onclick="deleteDocument()">
                <i class="material-icons">delete</i>
                {{ __('employee/documents/edit.delete_request') }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-nav {
        margin-bottom: 16px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    .btn-back, .btn-secondary {
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
    .btn-back:hover, .btn-secondary:hover { background: rgba(103, 80, 164, 0.08); }

    .form-container { max-width: 1000px; }
    .document-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    .current-status {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 24px 32px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .status-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .employee-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #6750A4;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 20px;
    }
    .status-info h3 {
        margin: 0 0 4px 0;
        font-size: 20px;
        font-weight: 500;
    }
    .status-info p { margin: 0; color: #666; }

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

    .form-section {
        background: white;
        padding: 28px;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 0 24px 0;
        font-size: 18px;
        font-weight: 500;
        color: #1C1B1F;
    }
    .section-title i { color: #6750A4; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }
    .form-group { display: flex; flex-direction: column; }
    .form-group label {
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px 16px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
        background: white;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #6750A4;
    }
    .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        justify-content: flex-end;
        padding-top: 24px;
        border-top: 1px solid #e0e0e0;
        flex-wrap: wrap;
    }
    .btn-primary, .btn-cancel, .btn-danger {
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
    .btn-primary {
        background: #6750A4;
        color: white;
    }
    .btn-primary:hover { background: #5A4A94; }
    .btn-cancel {
        border: 2px solid #e0e0e0;
        color: #666;
        background: white;
    }
    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }
    .btn-danger {
        background: #d32f2f;
        color: white;
    }
    .btn-danger:hover { background: #b71c1c; }

    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .current-status { flex-direction: column; gap: 16px; text-align: center; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
function deleteDocument() {
    if (confirm('{{ __("employee.documents.edit.delete_confirm") }}')) {
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
                alert('{{ __("employee.documents.edit.delete_success") }}');
                window.location.href = '/employee/documents';
            } else {
                alert('{{ __("employee.documents.edit.delete_error") }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __("employee.documents.edit.delete_error") }}');
        });
    }
}
</script>
@endpush
