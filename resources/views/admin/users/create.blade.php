@extends('layouts.admin')
@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp

@section('title', __('admin.users.create.title', ['company' => env('COMPANY_NAME', 'Teqin Valley')]))
@section('page-title', __('admin.users.create.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('admin.users.create.back_button') }}
        </a>
    </div>
    <h2>{{ __('admin.users.create.add_new_employee') }}</h2>
    <p>{{ __('admin.users.create.description', ['company' => env('COMPANY_NAME', 'Teqin Valley')]) }}</p>
</div>

<form action="{{ route('admin.users.store') }}" method="POST" class="employee-form" enctype="multipart/form-data">
    @csrf

    <!-- Basic Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">person</i>
            {{ __('admin.users.create.basic_information') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('admin.users.create.full_name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('admin.users.create.email_address') }} *</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('admin.users.create.password') }} *</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('admin.users.create.confirm_password') }} *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <label for="employee_id">{{ __('admin.users.create.employee_id') }} *</label>
                <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id') }}" required>
                @error('employee_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ __('admin.users.create.phone_number') }} *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="photo">{{ __('admin.users.create.employee_photo') }} *</label>
                <input type="file" id="photo" name="photo" value="{{ old('photo') }}" accept="image/*">
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
            {{ __('admin.users.create.personal_information') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="date_of_birth">{{ __('admin.users.create.date_of_birth') }}</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
            </div>

            <div class="form-group">
                <label for="gender">{{ __('admin.users.create.gender') }}</label>
                <select id="gender" name="gender">
                    <option value="">{{ __('admin.users.create.select_gender') }}</option>
                    <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>{{ __('admin.users.create.male') }}</option>
                    <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>{{ __('admin.users.create.female') }}</option>
                    <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>{{ __('admin.users.create.other') }}</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="address">{{ __('admin.users.create.address') }}</label>
                <textarea id="address" name="address" rows="3" placeholder="{{ __('admin.users.create.full_address') }}">{{ old('address') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Employment Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            {{ __('admin.users.create.employment_information') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="department">{{ __('admin.users.create.department') }} *</label>
                <select id="department" name="department" required>
                    <option value="">{{ __('admin.users.create.select_department') }}</option>
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
                <label for="designation">{{ __('admin.users.create.designation') }} *</label>
                <input type="text" id="designation" name="designation" value="{{ old('designation') }}" required>
                @error('designation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">{{ __('admin.users.create.role') }} *</label>
                <select id="role" name="role" required>
                    <option value="">{{ __('admin.users.create.select_role') }}</option>
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
                <label for="joining_date">{{ __('admin.users.create.joining_date') }} *</label>
                <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', date('Y-m-d')) }}" required>
                @error('joining_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">{{ __('admin.users.create.employee_status') }}</label>
                <select id="status" name="status">
                    <option value="1" {{ old('status', '1') === '1' ? 'selected' : '' }}>{{ __('admin.users.create.active') }}</option>
                    <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>{{ __('admin.users.create.inactive') }}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="employee_type">{{ __('admin.users.create.type_of_employee') }} *</label>
                <select id="employee_type" name="employee_type" required>
                    <option value="">{{ __('admin.users.create.select_employee_type') }}</option>
                    <option value="daily" {{ old('employee_type') === "daily" ? 'selected' : '' }}>{{ __('admin.users.create.daily') }}</option>
                    <option value="contract" {{ old('employee_type') === "contract" ? 'selected' : '' }}>{{ __('admin.users.create.contract') }}</option>
                    <option value="permanent" {{ old('employee_type') === "permanent" ? 'selected' : '' }}>{{ __('admin.users.create.permanent') }}</option>
                </select>
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="reportingManagerGroup" style="display: none;">
                <label for="reporting_manager">{{ __('admin.users.create.reporting_manager') }} *</label>
                <select id="reporting_manager" name="reporting_manager">
                    <option value="">{{ __('admin.users.create.select_manager') }}</option>
                    @foreach($managers as $manager)
                        <option value="{{ $manager->id }}" {{ old('reporting_manager') === $manager->id ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $manager->name)) }}
                        </option>
                    @endforeach
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
            {{ __('admin.users.create.salary_information') }}
         </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="basic_salary">{{ __('admin.users.create.basic_salary') }} *</label>
                 <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary') }}" required>
                @error('basic_salary')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
<div class="form-group"></div><div class="form-group"></div>
<h3 class="section-title">
            {{ __('admin.users.create.allowances') }}
         </h3></br><div class="form-group"></div>
            <div class="form-group">
                <label for="housing_allowance">{{ __('admin.users.create.housing_allowance') }}&nbsp; &nbsp;<input type="hidden" name="allowance_names[]" value="housing_allowance"><input type="hidden" name="allowance_percentage[]" value="25"><input type="checkbox" name="allowance_active[]" value="1" >
                @error('housing_allowance')
                    <span class="error">{{ $message }}</span>
                @enderror</label>
            </div>
            <div class="form-group">
                <label for="transportation_allowance">{{ __('admin.users.create.transportation_allowance') }}&nbsp; &nbsp;<input type="hidden" name="allowance_names[]" value="transportation_allowance"><input type="hidden" name="allowance_percentage[]" value="10"><input type="checkbox" id="transportation_allowance" name="transportation_allowance" value="{{ old('transportation_allowance') }}" >
                @error('transportation_allowance')
                    <span class="error">{{ $message }}</span>
                @enderror </label>
                
            </div>
             <div id="allowance-container" style=""></div><br><br>
             <div class="form-group col-md-4">
                <label for=""></label>
                  <a class="btn-primary" id="add-allowance-btn">
             <i class="material-icons">add</i>
            {{ __('admin.users.create.add_more') }}       </a>
                
            </div>
        </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('add-allowance-btn');
    const container = document.getElementById('allowance-container');

    addBtn.addEventListener('click', () => {
        const newDiv = document.createElement('div');
        newDiv.className = 'form-group';

        // Add input + checkbox + remove button inside label to keep structure similar
        newDiv.innerHTML = `<div class="form-grid"> <div class="form-group"> </div><div class="form-group">
            <label>
                <input type="text" name="allowance_names[]" placeholder="{{ __('admin.users.create.allowance_name') }}" required>
                &nbsp;&nbsp;
                <input type="number" name="allowance_percentage[]"  placeholder="{{ __('admin.users.create.allowance_percentage') }}" required >&nbsp;&nbsp;
                <input type="checkbox" name="allowance_active[]" value="1">
                {{ __('admin.users.create.active') }}
                &nbsp;&nbsp;
                <button type="button" class="remove-allowance" style="all:unset; color:red; cursor:pointer;">{{ __('admin.users.create.remove') }}</button>
            </label> <div class="form-group"></div></div>
        `;

        container.appendChild(newDiv);
    });

    container.addEventListener('click', (e) => {
        if(e.target.classList.contains('remove-allowance')){
            e.target.closest('.form-group').remove();
        }
    });
});
</script>


    <!-- Salary Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            {{ __('admin.users.create.bank_information') }}
         </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="bank_name">{{ __('admin.users.create.bank_name') }} *</label>
                 <input type="text" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                @error('bank_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="iban_no">{{ __('admin.users.create.iban_no') }} *</label>
                <input type="text" id="iban_no" name="iban_no" value="{{ old('iban_no') }}" required>
                @error('iban_no')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="bank_proof_document">{{ __('admin.users.create.document') }} </label>
                <input type="file" id="bank_proof_document" name="bank_proof_document" value="{{ old('bank_proof_document') }}" >
                @error('bank_proof_document')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

         
        </div>
</div>



<!-- Salary Information -->
<div class="form-section">
    <h3 class="section-title">
        <i class="material-icons">work</i>
        {{ __('admin.users.create.attachments') }}
    </h3>

    <div class="form-grid">
        <div class="form-group col-md-4">
            <label for="attachment_1">{{ __('admin.users.create.attachment') }} </label>
            <input type="file" id="attachment_1" name="attachments[]" value="{{ old('attachment_1') }}">
            @error('attachments.*')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label for=""></label>
            <a class="btn-primary" id="add-attachment-btn" style="margin-top: 20px;">
                <i class="material-icons">add</i>
                {{ __('admin.users.create.add_more') }}
            </a>
        </div>

        <div class="form-group col-md-4 newdiv"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addBtn = document.getElementById('add-attachment-btn');
    const formGrid = addBtn.closest('.form-grid');

    let attachmentCount = 1; // track attachment number

    addBtn.addEventListener('click', (e) => {
        e.preventDefault(); // prevent link navigation

        attachmentCount++;

        // Create a new form-group div
        const newGroup = document.createElement('div');
        newGroup.className = 'form-group col-md-4 newdiv';

        // Build the label + input
        newGroup.innerHTML = `
            <label for="attachment_${attachmentCount}">{{ __('admin.users.create.attachment') }} </label>
            <input type="file" id="attachment_${attachmentCount}" name="attachments[]">
            <button type="button" class="remove-attachment" style="all:unset; color:red; cursor:pointer; margin-left:8px;">{{ __('admin.users.create.remove') }}</button>
        `;

        // Insert new field before the last "Add More" button
        const addButtonGroup = formGrid.querySelector('#add-attachment-btn').closest('.form-group col-md-4 newdiv');
        formGrid.insertBefore(newGroup, addButtonGroup);

        // Handle remove
        const removeBtn = newGroup.querySelector('.remove-attachment');
        removeBtn.addEventListener('click', () => newGroup.remove());
    });
});
</script>


   <!-- Documents Section -->
<div class="form-section">
    <h3 class="section-title">
        <i class="material-icons">work</i>
        {{ __('admin.users.create.documents_section') }}
    </h3>

    <div class="form-grid" id="documents-grid">

        <!-- First document input set -->
        <div class="form-group">
            <label for="document_name_1">{{ __('admin.users.create.document_id_name') }} </label>
            <input type="text" id="document_name_1" name="document_names[]" value="{{ old('document_names.0') }}">
            @error('document_names.*')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="document_1">{{ __('admin.users.create.document') }} </label>
            <input type="file"id="document_1" name="documents[]" value="{{ old('documents.0') }}">
            @error('documents.*')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
            <label for=""></label>
            <button type="button" class="btn-primary" id="add-document-btn" style="margin-top: 20px;">
                <i class="material-icons">add</i> {{ __('admin.users.create.add_more_document') }}
            </button>
        </div><div class="">
               
            </div><div class="">
               
            </div>
<div class="newdiv">
               
            </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const addDocBtn = document.getElementById('add-document-btn');
    const documentsGrid = document.getElementById('documents-grid');
    let docCount = 1; // tracks current number of document sets

    addDocBtn.addEventListener('click', (e) => {
        e.preventDefault();
        docCount++;

        // Create container divs for document name and file input
        const nameGroup = document.createElement('div');
        nameGroup.className = 'form-group';
        nameGroup.innerHTML = `
            <label for="document_name_${docCount}">{{ __('admin.users.create.document_id_name') }} *</label>
            <input type="text" id="document_name_${docCount}" name="document_names[]" >
        `;

        const fileGroup = document.createElement('div');
        fileGroup.className = 'form-group';
        fileGroup.innerHTML = `
            <label for="document_${docCount}">{{ __('admin.users.create.document') }} *</label>
            <input type="file" id="document_${docCount}" name="documents[]" >
        `;

        // Remove button group
        const removeGroup = document.createElement('div');
        removeGroup.className = 'form-group col-md-4';
        removeGroup.style.display = 'flex';
        removeGroup.style.alignItems = 'flex-end';
        removeGroup.innerHTML = `
            <label>&nbsp;</label>
            <button type="button" class="remove-document btn-primary" 
                style="background:none; border:none; color:red; cursor:pointer; padding:0;">
                <i class="material-icons" style="font-size:16px; vertical-align:middle;">remove_circle</i> {{ __('admin.users.create.remove') }}
            </button>
        `;

        // Append new document inputs *before* the Add More button
        const addButtonGroup = addDocBtn.closest('.newdiv');
        documentsGrid.insertBefore(nameGroup, addButtonGroup);
        documentsGrid.insertBefore(fileGroup, addButtonGroup);
        documentsGrid.insertBefore(removeGroup, addButtonGroup);

        // Remove handler
        removeGroup.querySelector('.remove-document').addEventListener('click', () => {
            nameGroup.remove();
            fileGroup.remove();
            removeGroup.remove();
        });
    });
});
</script>


    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('admin.users.create.create_employee') }}
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn-cancel">
            {{ __('admin.users.create.cancel') }}
        </a>
    </div>
</form>
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

    .page-header h2 {
        margin: 0 0 8px 0;
        font-size: 28px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .page-header p {
        margin: 0;
        color: #666;
        font-size: 16px;
    }

    .employee-form {
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
        font-size: 20px;
        font-weight: 500;
        color: #1C1B1F;
    }

    .section-title i { color: #6750A4; }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

    .form-group .error {
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
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
    // Translation strings for JavaScript
    const translations = {
        passwordMismatch: "{{ __('admin.users.create.password_mismatch') }}",
        creatingEmployee: "{{ __('admin.users.create.creating_employee') }}"
    };
</script>

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
            alert(translations.passwordMismatch);
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> ' + translations.creatingEmployee;
        submitButton.disabled = true;
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const managerGroup = document.getElementById('reportingManagerGroup');
    const managerSelect = document.getElementById('reporting_manager');

    function toggleReportingManager() {
        if (roleSelect.value === 'employee') {
            managerGroup.style.display = 'flex';
            managerSelect.setAttribute('required', 'true');
        } else {
            managerGroup.style.display = 'none';
            managerSelect.removeAttribute('required');
        }
    }

    toggleReportingManager(); // Check on load (for old input)
    roleSelect.addEventListener('change', toggleReportingManager);
});
</script>


@endpush