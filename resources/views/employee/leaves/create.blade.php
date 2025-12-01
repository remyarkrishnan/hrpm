@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.employee')

@section('title', __('employee/leaves/create.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Employee Dashboard')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('employee.leaves.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/leaves/common.actions.back') }}
        </a>
    </div>
    <h2>{{ __('employee/leaves/create.title') }}</h2>
    <p>{{ __('employee/leaves/create.subtitle') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('employee.leaves.store') }}" method="POST" enctype="multipart/form-data" class="leave-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                {{ __('employee/leaves/create.sections.type') }}
            </h3>

            <div class="form-grid">
                

                <div class="form-group">
                    <label for="type">{{ __('employee/leaves/common.labels.leave_type') }} *</label>
                    <select id="leave_type" name="leave_type" required>
                        <option value="">{{ __('employee/leaves/create.form.select_type') }}</option>
                        <option value="sick_leave" {{ old('leave_type') === 'sick_leave' ? 'selected' : '' }}>{{ __('employee/leaves/common.types.sick_leave') }}</option>
                        <option value="casual_leave" {{ old('leave_type') === 'casual_leave' ? 'selected' : '' }}>{{ __('employee/leaves/common.types.casual_leave') }}</option>
                        <option value="annual_leave" {{ old('leave_type') === 'annual_leave' ? 'selected' : '' }}>{{ __('employee/leaves/common.types.annual_leave') }}</option>
                        <option value="maternity_leave" {{ old('leave_type') === 'maternity_leave' ? 'selected' : '' }}>{{ __('employee/leaves/common.types.maternity_leave') }}</option>
                        <option value="emergency_leave" {{ old('leave_type') === 'emergency_leave' ? 'selected' : '' }}>{{ __('employee/leaves/common.types.emergency_leave') }}</option>
                    </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">date_range</i>
                {{ __('employee/leaves/create.sections.duration') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="start_date">{{ __('employee/leaves/common.labels.start_date') }} *</label>
                    <input type="date" id="from_date" name="from_date" value="{{ old('from_date') }}" required>
                    @error('from_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">{{ __('employee/leaves/common.labels.end_date') }} *</label>
                    <input type="date" id="to_date" name="to_date" value="{{ old('to_date') }}" required>
                    @error('to_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="calculated_days">{{ __('employee/leaves/common.labels.total_days') }}</label>
                    <input type="text" id="calculated_days" readonly class="calculated-field">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                {{ __('employee/leaves/create.sections.details') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="reason">{{ __('employee/leaves/common.labels.reason') }} *</label>
                    <textarea id="reason" name="reason" rows="4" required placeholder="{{ __('employee/leaves/create.form.reason_placeholder') }}">{{ old('reason') }}</textarea>
                    @error('reason')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="documents">{{ __('employee/leaves/create.form.docs_optional') }}</label>
                    <input type="file" id="supporting_document" name="supporting_document[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small class="form-help">{{ __('employee/leaves/create.form.docs_help') }}</small>
                    @error('documents.*')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

               
            </div>
        </div>

        <div class="balance-section">
            <h3 class="section-title">
                <i class="material-icons">account_balance_wallet</i>
                {{ __('employee/leaves/create.sections.balance') }}
            </h3>

            <div class="balance-grid">
                <div class="balance-card">
                    <h4>{{ __('employee/leaves/common.types.sick_leave') }}</h4>
                    <div class="balance-info">
                        <span class="available">8</span> / <span class="total">12</span>
                        <small>{{ __('employee/leaves/create.balance.available') }}</small>
                    </div>
                </div>

                <div class="balance-card">
                    <h4>{{ __('employee/leaves/common.types.casual_leave') }}</h4>
                    <div class="balance-info">
                        <span class="available">5</span> / <span class="total">7</span>
                        <small>{{ __('employee/leaves/create.balance.available') }}</small>
                    </div>
                </div>

                <div class="balance-card">
                    <h4>{{ __('employee/leaves/common.types.annual_leave') }}</h4>
                    <div class="balance-info">
                        <span class="available">18</span> / <span class="total">21</span>
                        <small>{{ __('employee/leaves/create.balance.available') }}</small>
                    </div>
                </div>

                <div class="balance-card">
                    <h4>{{ __('employee/leaves/common.types.emergency_leave') }}</h4>
                    <div class="balance-info">
                        <span class="available">3</span> / <span class="total">3</span>
                        <small>{{ __('employee/leaves/create.balance.available') }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">send</i>
                {{ __('employee/leaves/create.form.submit_btn') }}
            </button>
            <a href="{{ route('employee.leaves.index') }}" class="btn-cancel">
                {{ __('employee/leaves/common.actions.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection

@push('styles')
{{-- Styles remain exactly as provided --}}
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
    const startDateInput = document.getElementById('from_date');
    const endDateInput = document.getElementById('to_date');
    const calculatedDaysInput = document.getElementById('calculated_days');
    // Localized label for JS
    const daysLabel = "{{ __('employee/leaves/common.labels.days') }}";

    function calculateDays() {
        if (startDateInput.value && endDateInput.value) {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(endDateInput.value);

            if (endDate >= startDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                calculatedDaysInput.value = diffDays + ' ' + daysLabel;
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