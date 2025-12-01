@extends('layouts.admin')

@section('title', __('employee/users/edit.title') . ' - ' . $user->name . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/users/edit.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.users.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/users/common.actions.back_employees') }}
        </a>
        <a href="{{ route('admin.users.show', $user) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('employee/users/common.actions.view_profile') }}
        </a>
    </div>
    <h2>{{ __('employee/users/edit.title') }}: {{ $user->name }}</h2>
    <p>{{ __('employee/users/edit.description', ['company' => env('COMPANY_NAME', 'Teqin Vally')]) }}</p>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST" class="employee-form">
    @csrf
    @method('PUT')

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">person</i>
            {{ __('employee/users/edit.sections.basic') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">{{ __('employee/users/common.labels.full_name') }} *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">{{ __('employee/users/common.labels.email') }} *</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('employee/users/edit.fields.new_password') }}</label>
                <input type="password" id="password" name="password" placeholder="{{ __('employee/users/edit.fields.password_placeholder') }}">
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('employee/users/edit.fields.confirm_password') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="{{ __('employee/users/edit.fields.confirm_password') }}">
            </div>

            <div class="form-group">
                <label for="employee_id">{{ __('employee/users/common.labels.employee_id') }} *</label>
                <input type="text" id="employee_id" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" required>
                @error('employee_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">{{ __('employee/users/common.labels.phone') }} *</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">work</i>
            {{ __('employee/users/edit.sections.employment') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="department">{{ __('employee/users/common.labels.department') }} *</label>
                <select id="department" name="department" required>
                    <option value="">{{ __('employee/users/create.placeholders.select_dept') }}</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ old('department', $user->department) === $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
                @error('department')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="designation">{{ __('employee/users/common.labels.designation') }} *</label>
                <input type="text" id="designation" name="designation" value="{{ old('designation', $user->designation) }}" required>
                @error('designation')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="role">{{ __('employee/users/common.labels.role') }} *</label>
                <select id="role" name="role" required>
                    <option value="">{{ __('employee/users/create.placeholders.select_role') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->getRoleNames()->first()) === $role->name ? 'selected' : '' }}>
                            {{ ucwords(str_replace('-', ' ', $role->name)) }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="joining_date">{{ __('employee/users/common.labels.joining_date') }} *</label>
                <input type="date" id="joining_date" name="joining_date" value="{{ old('joining_date', $user->joining_date ? $user->joining_date->format('Y-m-d') : '') }}" required>
                @error('joining_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="salary">{{ __('employee/users/create.fields.monthly_salary') }}</label>
                <input type="number" id="salary" name="salary" value="{{ old('salary', $user->salary) }}" min="0" step="0.01" placeholder="{{ __('employee/users/create.placeholders.optional') }}">
                @error('salary')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">{{ __('employee/users/create.fields.employee_status') }}</label>
                <select id="status" name="status">
                    <option value="1" {{ old('status', $user->status ? '1' : '0') === '1' ? 'selected' : '' }}>{{ __('employee/users/common.status.active') }}</option>
                    <option value="0" {{ old('status', $user->status ? '1' : '0') === '0' ? 'selected' : '' }}>{{ __('employee/users/common.status.inactive') }}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">account_circle</i>
            {{ __('employee/users/edit.sections.personal') }}
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="date_of_birth">{{ __('employee/users/common.labels.dob') }}</label>
                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}">
            </div>

            <div class="form-group">
                <label for="gender">{{ __('employee/users/common.labels.gender') }}</label>
                <select id="gender" name="gender">
                    <option value="">{{ __('employee/users/create.placeholders.select_gender') }}</option>
                    <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>{{ __('employee/users/create.options.male') }}</option>
                    <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>{{ __('employee/users/create.options.female') }}</option>
                    <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>{{ __('employee/users/create.options.other') }}</option>
                </select>
            </div>

            <div class="form-group full-width">
                <label for="address">{{ __('employee/users/common.labels.address') }}</label>
                <textarea id="address" name="address" rows="3" placeholder="{{ __('employee/users/create.placeholders.address') }}">{{ old('address', $user->address) }}</textarea>
            </div>

            <div class="form-group">
                <label for="emergency_contact">{{ __('employee/users/edit.fields.emergency_contact') }}</label>
                <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->emergency_contact) }}">
            </div>

            <div class="form-group">
                <label for="emergency_phone">{{ __('employee/users/edit.fields.emergency_phone') }}</label>
                <input type="tel" id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone', $user->emergency_phone) }}">
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            {{ __('employee/users/edit.buttons.update') }}
        </button>
        <a href="{{ route('admin.users.show', $user) }}" class="btn-cancel">
            {{ __('employee/users/common.actions.cancel') }}
        </a>
        <button type="button" onclick="deleteEmployee({{ $user->id }})" class="btn-danger">
            <i class="material-icons">delete</i>
            {{ __('employee/users/edit.buttons.delete') }}
        </button>
    </div>
</form>
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
        transition: all 0.2s;
        border: none;
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
        .form-section { padding: 20px; }
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel, .btn-danger { width: 100%; justify-content: center; }
        .page-nav { flex-direction: column; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    document.querySelector('.employee-form').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Password and confirm password do not match');
            return;
        }

        if (password && password.length < 6) {
            e.preventDefault();
            alert('Password must be at least 6 characters long');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Updating Employee...';
        submitButton.disabled = true;
    });
});

// Delete employee function
async function deleteEmployee(employeeId) {
    if (!confirm('Are you sure you want to delete this employee?\n\nThis action cannot be undone and will permanently remove:\n• Employee profile and personal information\n• All associated records\n• Login access to the system\n\nType "DELETE" to confirm this action.')) {
        return;
    }

    const confirmation = prompt('To confirm deletion, type "DELETE" (in capital letters):');
    if (confirmation !== 'DELETE') {
        alert('Deletion cancelled. You must type "DELETE" exactly as shown.');
        return;
    }

    try {
        const response = await fetch(`/admin/users/${employeeId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Employee deleted successfully');
            window.location.href = '/admin/users';
        } else {
            alert(data.message || 'Failed to delete employee');
        }
    } catch (error) {
        alert('Error deleting employee: ' + error.message);
        console.error(error);
    }
}
</script>
@endpush