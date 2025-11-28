@extends('layouts.employee')

@section('title', 'Edit Document Request - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Dashboard')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.documents.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Documents
        </a>
        <a href="{{ route('employee.documents.show', $document->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            View Details
        </a>
    </div>
    <h2>Edit Document Request</h2>
    <p>Update document request</p>
</div>

<div class="form-container">
    <form action="{{ route('employee.documents.update', $document->id ?? 1) }}" method="POST" enctype="multipart/form-data" class="leave-form">
        @csrf
        @method('PUT')

      

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                Update Document Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="type">Document Type *</label>
                    <select id="type" name="type" required>
                        <option value="id_letter" {{ ($document->type ?? 'id_letter') === 'id_letter' ? 'selected' : '' }}>ID Letter</option>
                        <option value="experience" {{ ($document->type ?? 'experience') === 'experience' ? 'selected' : '' }}>Experience</option>
                        <option value="salary_slip" {{ ($document->type ?? 'salary_slip') === 'salary_slip' ? 'selected' : '' }}>Salary Slip</option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

               

               
            </div>
        </div>

        
        <!-- Current Documents -->
        @if(isset($leave->documents) && count($leave->documents) > 0)
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">folder</i>
                Current Documents
            </h3>

            <div class="document-list">
                @foreach($leave->documents as $document)
                <div class="document-item">
                    <div class="document-info">
                        <i class="material-icons">description</i>
                        <span>{{ $document }}</span>
                    </div>
                    <div class="document-actions">
                        <a href="#" target="_blank" class="btn-view">
                            <i class="material-icons">open_in_new</i>
                            View
                        </a>
                        <button type="button" class="btn-remove" onclick="removeDocument('{{ $document }}')">
                            <i class="material-icons">delete</i>
                            Remove
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

       

        <!-- Audit Trail -->
        <div class="audit-section">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                Change History
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">Oct 07, 2025 - 02:30 PM</div>
                    <div class="audit-action">Document request submitted</div>
                    <div class="audit-user">{{ $document->employee_name ?? 'Rajesh Kumar' }}</div>
                </div>

                @if(($document->status ?? '') === 'approved')
                <div class="audit-item">
                    <div class="audit-time">Oct 08, 2025 - 10:15 AM</div>
                    <div class="audit-action">Document request approved</div>
                    <div class="audit-user">{{ $document->approved_by ?? 'HR Manager' }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                Update Document Request
            </button>
            <a href="{{ route('employee.documents.show', $document->id ?? 1) }}" class="btn-cancel">
                Cancel Changes
            </a>
            <button type="button" class="btn-danger" onclick="deleteDocument()">
                <i class="material-icons">delete</i>
                Delete Request
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

    .form-container {
        max-width: 1000px;
    }

    .leave-form {
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

    .status-info p {
        margin: 0;
        color: #666;
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

    .form-section, .audit-section {
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

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width { grid-column: 1 / -1; }

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

    .calculated-field, .form-group input[readonly] {
        background: #f5f5f5 !important;
        color: #666;
    }

    .form-help {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
    }

    .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .document-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .document-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }

    .document-info {
        display: flex;
        align-items: center;
        gap: 12px;
        color: #333;
    }

    .document-info i {
        color: #6750A4;
    }

    .document-actions {
        display: flex;
        gap: 8px;
    }

    .btn-view, .btn-remove {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-view {
        background: rgba(103, 80, 164, 0.1);
        color: #6750A4;
    }

    .btn-view:hover { background: rgba(103, 80, 164, 0.2); }

    .btn-remove {
        background: rgba(244, 67, 54, 0.1);
        color: #f44336;
    }

    .btn-remove:hover { background: rgba(244, 67, 54, 0.2); }

    .audit-trail {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .audit-item {
        padding: 16px;
        background: #f8f9fa;
        border-radius: 8px;
        border-left: 3px solid #6750A4;
    }

    .audit-time {
        font-size: 12px;
        color: #666;
        margin-bottom: 4px;
    }

    .audit-action {
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }

    .audit-user {
        font-size: 12px;
        color: #6750A4;
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
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const calculatedDaysInput = document.getElementById('calculated_days');

    function calculateDays() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate >= startDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                calculatedDaysInput.value = diffDays + ' days';
            } else {
                calculatedDaysInput.value = '';
            }
        }
    }

    startDateInput.addEventListener('change', calculateDays);
    endDateInput.addEventListener('change', calculateDays);

    // Initial calculation
    calculateDays();
});

function removeDocument(documentName) {
    if (confirm(`Are you sure you want to remove "${documentName}"?`)) {
        // TODO: Implement document removal via AJAX
        alert('Document removal functionality to be implemented');
    }
}

function deleteDocument() {
    if (confirm('Are you sure you want to delete this document request?\n\nThis action cannot be undone.')) {
        fetch(`/employee/documents/{{ $document->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Document request deleted successfully');
                window.location.href = '/employee/documents';
            } else {
                alert('Error deleting document request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting document request');
        });
    }
}


</script>
@endpush
