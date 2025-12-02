@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@extends('layouts.employee')

@section('title', __('employee/documents/create.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/documents/create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.documents.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/documents/create.back_to_documents') }}
        </a>
    </div>
    <div>
        <h2>{{ __('employee/documents/create.header_title') }}</h2>
        <p>{{ __('employee/documents/create.header_description') }}</p>
    </div>
</div>

<div class="form-container">
    <form action="{{ route('employee.documents.store') }}" method="POST" enctype="multipart/form-data" class="document-form">
        @csrf
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                {{ __('employee/documents/create.document_type_title') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="type">{{ __('employee/documents/create.document_type_label') }} *</label>
                    <select id="type" name="type" required>
                        <option value="">{{ __('employee/documents/create.select_document_type') }}</option>
                        <option value="id_letter">{{ __('employee/documents/create.id_letter') }}</option>
                        <option value="experience">{{ __('employee/documents/create.experience_certificate') }}</option>
                        <option value="salary_slip">{{ __('employee/documents/create.salary_slip') }}</option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">send</i>
                {{ __('employee/documents/create.submit_button') }}
            </button>
            <a href="{{ route('employee.documents.index') }}" class="btn-cancel">
                {{ __('employee/documents/create.cancel_button') }}
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-nav { margin-bottom: 16px; }
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
    
    .form-container { max-width: 800px; }
    .document-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
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
    }
    .btn-primary {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #6750A4;
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-primary:hover { background: #5A4A94; }
    .btn-cancel {
        padding: 12px 24px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        text-decoration: none;
        color: #666;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        border-color: #666;
        color: #333;
    }
    
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush
