@extends('layouts.admin')
    @php
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
    @endphp
@section('title', __('employee.users.create.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('employee.users.create.page_title'))

@section('content')

<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee.users.create.back_button') }}
        </a>
    </div>
    <h2>{{ __('employee.users.create.add_new_employee') }}</h2>
    <p>{{ __('employee.users.create.description', ['company' => env('COMPANY_NAME', 'Teqin Vally')]) }}</p>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" class="employee-form">
    @csrf

    <!-- Basic Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">person</i>
            {{ __('employee.users.create.basic_information') }}
        </h3>
<div style="background: yellow; padding: 10px; margin: 10px 0;">
    <strong>DEBUG INFO:</strong><br>
    Current Locale: {{ app()->getLocale() }}<br>
    Config Locale: {{ config('app.locale') }}<br>
    Session Locale: {{ session('locale') }}<br>
    Test Translation: {{ __('messages.dashboard') }}
</div>
        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('employee.users.create.full_name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('employee.users.create.email_address') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('employee.users.create.password') }} *</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('employee.users.create.confirm_password') }} *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="employee_id">{{ __('employee.users.create.employee_id') }} *</label>
                <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required>
                @error('employee_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ __('employee.users.create.phone_number') }} *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo">{{ __('employee.users.create.employee_photo') }} *</label>
                <input type="file" id="photo" name="photo" value="{{ old('photo') }}" required>
                @error('photo')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">account_circle</i>
            {{ __('employee.users.create.personal_information') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="date_of_birth">{{ __('employee.users.create.date_of_birth') }}</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>

            <div class="form-group">
                <label for="gender">{{ __('employee.users.create.gender') }}</label>
                <select id="gender" name="gender">
                    <option value="">{{ __('employee.users.create.select_gender') }}</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>{{ __('employee.users.create.male') }}</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>{{ __('employee.users.create.female') }}</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>{{ __('employee.users.create.other') }}</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="address">{{ __('employee.users.create.address') }}</label>
                <textarea id="address" name="address" rows="3" placeholder="{{ __('employee.users.create.address_placeholder') }}">{{ old('address') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Employment Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            {{ __('employee.users.create.employment_information') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="department">{{ __('employee.users.create.department') }} *</label>
                <select id="department" name="department" required>
                    <option value="">{{ __('employee.users.create.select_department') }}</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ old('department') === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
                @error('department')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="designation">{{ __('employee.users.create.designation') }} *</label>
                <input type="text" id="designation" name="designation" value="{{ old('designation') }}" required>
                @error('designation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="role">{{ __('employee.users.create.role') }} *</label>
                <select id="role" name="role" required>
                    <option value="">{{ __('employee.users.create.select_role') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="joining_date">{{ __('employee.users.create.joining_date') }} *</label>
                <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" required>
                @error('joining_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" style="display:none">
                <label for="salary">{{ __('employee.users.create.monthly_salary') }}</label>
                <input type="number" id="salary" name="salary" value="{{ old('salary') }}" min="0" step="0.01" placeholder="{{ __('employee.users.create.optional') }}">
                @error('salary')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">{{ __('employee.users.create.employee_status') }}</label>
                <select id="status" name="status">
                    <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>{{ __('employee.users.create.active') }}</option>
                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>{{ __('employee.users.create.inactive') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="type">{{ __('employee.users.create.type_of_employee') }} *</label>
                <select id="type" name="type" required>
                    <option value="">{{ __('employee.users.create.select_employee_type') }}</option>
                        <option value="Daily" {{ old('type') === "Daily" ? 'selected' : '' }}>{{ __('employee.users.create.daily') }}</option>
                        <option value="Contract" {{ old('type') === "Contract" ? 'selected' : '' }}>{{ __('employee.users.create.contract') }}</option>
                        <option value="Permanent" {{ old('type') === "Permanent" ? 'selected' : '' }}>{{ __('employee.users.create.permanent') }}</option>
                </select>
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="manager">{{ __('employee.users.create.reporting_manager') }} *</label>
                <select id="manager" name="manager" required>
                    <option value="">{{ __('employee.users.create.select_manager') }}</option>
                </select>
                @error('manager')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Salary Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            {{ __('employee.users.create.salary_information') }}
         </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="basic_salary">{{ __('employee.users.create.basic_salary') }} *</label>
                 <input type="text" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}" required>
                @error('basic_salary')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
<div class="form-group"></div><div class="form-group"></div>
<h3 class="section-title">
            Allowances
         </h3></br><div class="form-group"></div>
            <div class="form-group">
                <label for="housing_allowance">All * &nbsp; &nbsp;<input type="checkbox" id="housing_allowance" name="housing_allowance" value="{{ old('housing_allowance') }}" required>
                @error('housing_allowance')
                    <span class="error">{{ $message }}</span>
                @enderror</label>
                
            </div>
 <div class="form-group">
                <label for="transportation_allowance">Housing Allowance&nbsp; &nbsp;<input type="checkbox" id="transportation_allowance" name="transportation_allowance" value="{{ old('transportation_allowance') }}" >
                @error('salary')
                    <span class="error">{{ $message }}</span>
                @enderror</label>
            </div>
            <div class="form-group">
                <label for="transportation_allowance">Transportation Allowance&nbsp; &nbsp;<input type="checkbox" id="transportation_allowance" name="transportation_allowance" value="{{ old('transportation_allowance') }}" >
                @error('salary')
                    <span class="error">{{ $message }}</span>
                @enderror </label>
                
            </div>
             <div class="form-group col-md-4">
                <label for="iban_no"></label>
                  <button type="submit" class="btn-primary" disabled>
             <i class="material-icons">add</i>
            Add More       </button>
                @error('iban_no')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
</div>



    <!-- Salary Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            Bank Information
         </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="bank_name">Bank Name *</label>
                 <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                @error('bank_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="iban_no">IBAN No *</label>
                <input type="text" id="iban_no" name="iban_no" value="{{ old('iban_no') }}" required>
                @error('iban_no')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="bank_document">Document *</label>
                <input type="file" id="bank_document" name="bank_document" value="{{ old('bank_document') }}" >
                @error('bank_document')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

         
        </div>
</div>



    <!-- Salary Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            Attachements
         </h3>

        <div class="form-grid">
            <div class="form-group col-md-4">
                <label for="bank_name">Attachment 1 *</label>
                 <input type="file" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                @error('bank_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
  <div class="form-group"></div>
            <div class="form-group col-md-4">
                <label for="iban_no"></label>
                  <button type="submit" class="btn-primary" disabled>
             <i class="material-icons">add</i>
            Add More Attachment        </button>
                @error('iban_no')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

           

         
        </div>
</div>


    <!-- Salary Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
             Documents Section
         </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="bank_name">Document ID/Name *</label>
                 <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                @error('bank_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-grid">
                <label for="bank_name">Document *</label>
                 <input type="file" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                @error('bank_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-4">
                <label for="iban_no"></label>
                  <button type="submit" class="btn-primary" disabled>
             <i class="material-icons">add</i>
            Add More Document        </button>
                @error('iban_no')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

         
        </div>
</div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('employee.users.create.create_employee') }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
            {{ __('employee.users.create.cancel') }}
        </a>
    </div>
</form>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const employeeIdInput = document.getElementById('employee_id');
    const departmentSelect = document.getElementById('department');
    const companyDomain = '{{ env("COMPANY_DOMAIN", "teqinvalley.in") }}';

    // Auto-suggest email based on name
    nameInput.addEventListener('blur', function() {
        if (!emailInput.value && this.value) {
            const namePart = this.value.toLowerCase()
                .replace(/\s+/g, '.')
                .replace(/[^a-z0-9.]/g, '');
            emailInput.value = namePart + '@' + companyDomain;
        }
    });

    // Generate employee ID suggestion
    departmentSelect.addEventListener('change', function() {
        if (!employeeIdInput.value && this.value) {
            const deptCode = getDepartmentCode(this.value);
            const timestamp = Date.now().toString().slice(-3);
            employeeIdInput.value = deptCode + timestamp;
        }
    });

    function getDepartmentCode(department) {
        const codes = {
            'Administration': 'ADM',
            'Engineering': 'ENG', 
            'Project Management': 'PM',
            'Construction': 'CON',
            'Design': 'DES',
            'Finance': 'FIN',
            'Human Resources': 'HR',
            'Quality Control': 'QC',
            'Safety': 'SAF',
            'Procurement': 'PRO'
        };
        return codes[department] || 'EMP';
    }

    // Form validation
    document.querySelector('.employee-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert("{{ __('employee.users.create.password_mismatch') }}");
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> ' + "{{ __('employee.users.create.creating_employee') }}";
        submitButton.disabled = true;
    });
});
</script>
@endpush