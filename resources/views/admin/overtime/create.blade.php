@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('overtime.create.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('overtime.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.overtime.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('overtime.create.back') }}
        </a>
    </div>

    <p>{{ __('overtime.create.description') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.overtime.store') }}" method="POST" class="overtime-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">person</i>
                {{ __('overtime.create.employee_info') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="employee_id">{{ __('overtime.create.select_employee') }} *</label>
                    <select id="employee_id" name="employee_id" required>
                        <option value="">{{ __('overtime.create.choose_employee') }}</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} - {{ $emp->employee_code ?? '' }}</option>
                        @endforeach
                    </select>
                    @error('employee_id') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="date">{{ __('overtime.create.date') }} *</label>
                    <input type="date" id="date" name="date" value="{{ old('date') }}" required>
                    @error('date') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">schedule</i>
                {{ __('overtime.create.details') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="hours">{{ __('overtime.create.hours') }} *</label>
                    <input type="number" id="hours" name="hours" step="0.5" min="0.5" max="8" value="{{ old('hours') }}" required>
                    <small class="form-help">{{ __('overtime.create.hours_help') }}</small>
                </div>

                <div class="form-group">
                    <label for="start_time">{{ __('overtime.create.start_time') }}</label>
                    <input type="time" id="start_time" name="start_time" value="{{ old('start_time') }}">
                </div>

                <div class="form-group">
                    <label for="end_time">{{ __('overtime.create.end_time') }}</label>
                    <input type="time" id="end_time" name="end_time" value="{{ old('end_time') }}">
                </div>

                <div class="form-group">
                    <label for="project_id">{{ __('overtime.create.project') }}</label>
                    <select id="project_id" name="project_id">
                        <option value="">{{ __('overtime.create.select_project_optional') }}</option>
                        @foreach($projects as $prj)
                            <option value="{{ $prj->id }}">{{ $prj->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                {{ __('overtime.create.reason_section') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="reason">{{ __('overtime.create.reason') }} *</label>
                    <textarea id="reason" name="reason" rows="4" required>{{ old('reason') }}</textarea>
                    @error('reason') <span class="error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="urgency_level">{{ __('overtime.create.urgency') }}</label>
                    <select id="urgency_level" name="urgency_level">
                        <option value="normal">{{ __('overtime.create.urgency_normal') }}</option>
                        <option value="urgent">{{ __('overtime.create.urgency_urgent') }}</option>
                        <option value="critical">{{ __('overtime.create.urgency_critical') }}</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="calculation-section">
            <h3 class="section-title">
                <i class="material-icons">calculate</i>
                {{ __('overtime.create.rate_calculation') }}
            </h3>

            <div class="calculation-grid">
                <div class="calc-item">
                    <h4>{{ __('overtime.create.base_rate') }}</h4>
                    <div class="calc-value">{{ __('overtime.show.currency') }}500.00</div>
                    <small>{{ __('overtime.create.per_hour') }}</small>
                </div>

                <div class="calc-item">
                    <h4>{{ __('overtime.create.multiplier') }}</h4>
                    <div class="calc-value">1.5x</div>
                    <small>{{ __('overtime.create.standard') }}</small>
                </div>

                <div class="calc-item">
                    <h4>{{ __('overtime.create.overtime_rate') }}</h4>
                    <div class="calc-value">{{ __('overtime.show.currency') }}750.00</div>
                    <small>{{ __('overtime.create.per_hour') }}</small>
                </div>

                <div class="calc-item total">
                    <h4>{{ __('overtime.create.estimated_total') }}</h4>
                    <div class="calc-value" id="total-amount">{{ __('overtime.show.currency') }}0.00</div>
                    <small>{{ __('overtime.create.total_pay') }}</small>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">send</i>
                {{ __('overtime.create.submit') }}
            </button>
            <a href="{{ route('admin.overtime.index') }}" class="btn-cancel">
                {{ __('overtime.create.cancel') }}
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

    .overtime-form {
        display: flex;
        flex-direction: column;
        gap: 32px;
    }

    .form-section, .calculation-section {
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

    .calculation-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .calc-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        border: 1px solid #e0e0e0;
    }

    .calc-item.total {
        background: linear-gradient(135deg, #6750A4, #7B68C8);
        color: white;
        border: none;
    }

    .calc-item h4 {
        margin: 0 0 12px 0;
        font-size: 14px;
        font-weight: 600;
    }

    .calc-value {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .calc-item small {
        font-size: 12px;
        opacity: 0.8;
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
        .form-grid, .calculation-grid { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const hoursInput = document.getElementById('hours');
    const totalAmountElement = document.getElementById('total-amount');
    const baseRate = 500;
    const overtimeMultiplier = 1.5;
    const overtimeRate = baseRate * overtimeMultiplier;

    function calculateTotal() {
        const hours = parseFloat(hoursInput.value) || 0;
        const total = hours * overtimeRate;
        totalAmountElement.textContent = '₹' + total.toLocaleString('en-IN', { minimumFractionDigits: 2 });
    }

    hoursInput.addEventListener('input', calculateTotal);

    // Auto-calculate hours based on time range
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    function calculateHoursFromTime() {
        if (startTimeInput.value && endTimeInput.value) {
            const start = new Date('1970-01-01 ' + startTimeInput.value);
            const end = new Date('1970-01-01 ' + endTimeInput.value);

            if (end > start) {
                const diffMs = end - start;
                const diffHours = diffMs / (1000 * 60 * 60);
                hoursInput.value = diffHours.toFixed(1);
                calculateTotal();
            }
        }
    }

    startTimeInput.addEventListener('change', calculateHoursFromTime);
    endTimeInput.addEventListener('change', calculateHoursFromTime);

    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('date').min = today;
});
</script>
@endpush