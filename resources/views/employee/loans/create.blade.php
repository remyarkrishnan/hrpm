@extends('layouts.employee')

@section('title', 'Apply Loan - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Dashboard')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.loans.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Loans
        </a>
    </div>
    <h2>Apply for Loan</h2>
    <p>Submit a loan request for approval</p>
</div>

<div class="form-container">
    <form action="{{ route('employee.loans.store') }}" method="POST" enctype="multipart/form-data" class="leave-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                Loan Purpose & Amount
            </h3>

            <div class="form-grid">
                

                <div class="form-group">
                    <label for="type">Loan Purpose *</label>
                    <select id="purpose" name="purpose" required>
                        <option value="">Select Loan Purose</option>
                        <option value="medical_emergency" {{ old('purpose') === 'medical_emergency' ? 'selected' : '' }}>Medical Emergency</option>
                        <option value="personal_expenses" {{ old('purpose') === 'personal_expenses' ? 'selected' : '' }}>Personal Expenses</option>
                        <option value="other" {{ old('purpose') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="loan_amount">Loan Amount *</label>
                     <input type="text" id="amount" name="amount" value="{{ old('amount') }}" 
                           placeholder="Loan Amount">
                    @error('loan_amount')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">date_range</i>
                Repayment Duration
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">Repayment Duration *</label>
                    <select id="repayment_duration" name="repayment_duration" required>
                        <option value="">Select Repayment Duration</option>
                        <option value="6_months" {{ old('repayment_duration') === '6_months' ? 'selected' : '' }}>6 Months</option>
                        <option value="9_months" {{ old('repayment_duration') === '9_months' ? 'selected' : '' }}>9 Months</option>
                        <option value="12_months" {{ old('repayment_duration') === '12_months' ? 'selected' : '' }}>12 Months</option>
                        <option value="24_months" {{ old('repayment_duration') === '24_months' ? 'selected' : '' }}>24 Months</option>
                    </select>
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
                    <textarea id="reason" name="reason" rows="4" required placeholder="Please provide detailed reason for taking loan">{{ old('reason') }}</textarea>
                    @error('reason')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="documents">Supporting Documents (Optional)</label>
                    <input type="file" id="supporting_document" name="supporting_document[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small class="form-help">Upload salary slip, ID Proof etc. Max 5MB per file.</small>
                    @error('documents.*')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

               
            </div>
        </div>

       

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">send</i>
                Submit Loan Request
            </button>
            <a href="{{ route('employee.loans.index') }}" class="btn-cancel">
                Cancel
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

    .form-container {
        max-width: 800px;
    }

    .leave-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section, .balance-section {
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

    .calculated-field {
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

    .balance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .balance-card {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }

    .balance-card h4 {
        margin: 0 0 12px 0;
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .balance-info {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .balance-info .available {
        font-size: 24px;
        font-weight: 600;
        color: #6750A4;
    }

    .balance-info .total {
        color: #666;
    }

    .balance-info small {
        color: #666;
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
        .form-grid, .balance-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
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
        } else {
            calculatedDaysInput.value = '';
        }
    }

    startDateInput.addEventListener('change', calculateDays);
    endDateInput.addEventListener('change', calculateDays);

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;

    // Set minimum end date when start date changes
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
        if (endDateInput.value && endDateInput.value < this.value) {
            endDateInput.value = this.value;
        }
        calculateDays();
    });
});
</script>
@endpush
