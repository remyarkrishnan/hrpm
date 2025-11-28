@extends('layouts.employee')

@section('title', 'Edit Loan Request - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Dashboard')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.loans.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Loans
        </a>
        <a href="{{ route('employee.loans.show', $loan->id ) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            View Details
        </a>
    </div>
    <h2>Edit Loan Request</h2>
    <p>Update loan request </p>
</div>

<div class="form-container">
    <form action="{{ route('employee.loans.update', $loan->id) }}" method="POST" enctype="multipart/form-data" class="leave-form">
        @csrf
        @method('PUT')

      

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                Update Loan Information
            </h3>

            <div class="form-grid">


            <div class="form-group">
                    <label for="start_date">Purpose *</label>
                     <select id="purpose" name="purpose" required>
                        <option value="">Select Loan Purose</option>
                        <option value="medical_emergency"  {{ ($loan->purpose ?? 'medical_emergency') === 'medical_emergency' ? 'selected' : '' }}>Medical Emergency</option>
                        <option value="personal_expenses"  {{ ($loan->purpose ?? 'personal_expenses') === 'personal_expenses' ? 'selected' : '' }}>Personal Expenses</option>
                        <option value="other"  {{ ($loan->purpose ?? 'other') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                  
                    @error('purpose')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="type">Loan Amount *</label>
                    <input type="text" id="amount"  name="amount" value="{{ $loan->amount }}" required>
                    @error('amount')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                

                <div class="form-group">
                    <label for="end_date">Repayment Duration *</label>
                    <select id="repayment_duration" name="repayment_duration" required>
                        <option value="">Select Repayment Duration</option>
                        <option value="6_months"  {{ ($loan->repayment_duration ?? '6_months') === '6_months' ? 'selected' : '' }}>6 Months</option>
                        <option value="9_months"  {{ ($loan->repayment_duration ?? '9_months') === '9_months' ? 'selected' : '' }}>9 Months</option>
                        <option value="12_months"  {{ ($loan->repayment_duration ?? '12_months') === '12_months' ? 'selected' : '' }}>12 Months</option>
                        <option value="24_months"  {{ ($loan->repayment_duration ?? '24_months') === '24_months' ? 'selected' : '' }}>24 Months</option>
                    </select>
                    @error('repayment_duration')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                Loan Details
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="reason">Reason for taking Loan *</label>
                    <textarea id="reason" name="reason" rows="4" required>{{ old('reason', $loan->reason ?? 'Please provide detailed reason for taking loan.') }}</textarea>
                    @error('reason')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

             

                <div class="form-group">
                    <label for="documents">Additional Documents</label>
                    <input type="file" id="supporting_document" name="supporting_document[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small class="form-help">Upload salary slip, ID Proof etc. Max 5MB per file.</small>
                </div>
            </div>
        </div>

        <!-- Current Documents -->
    @php
    $documents = json_decode($loan->supporting_document ?? '[]', true);
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


    

        <!-- Audit Trail -->
        <div class="audit-section">
            <h3 class="section-title">
                <i class="material-icons">history</i>
                Change History
            </h3>

            <div class="audit-trail">
                <div class="audit-item">
                    <div class="audit-time">{{ $loan->created_at->format('d-m-Y')  }}</div>
                    <div class="audit-action">Loan request submitted</div>
                    <div class="audit-user">{{ $loan->user->name }}</div>
                </div>

                @if(($loan->status ?? '') === 'approved')
                <div class="audit-item">
                    <div class="audit-time">{{ $loan->approved_at->format('d-m-Y')  }}</div>
                    <div class="audit-action">Loan request approved</div>
                    <div class="audit-user">{{ $loan->approver->name  }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                Update Loan Request
            </button>
            <a href="{{ route('employee.loans.show', $loan->id) }}" class="btn-cancel">
                Cancel Changes
            </a>
            <button type="button" class="btn-danger" onclick="deleteLoan()">
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

function deleteLoan() {
    if (confirm('Are you sure you want to delete this loan request?\n\nThis action cannot be undone.')) {
        fetch(`/employee/loans/{{ $loan->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Loan request deleted successfully');
                window.location.href = '/employee/loans';
            } else {
                alert('Error deleting loan request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting loan request');
        });
    }
}

// Form submission with loading state
document.querySelector('.leave-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Updating...';
    submitButton.disabled = true;
});
</script>
@endpush
