@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('leave.edit.title', ['company' => env('COMPANY_NAME', 'Teqin Vally')]))
@section('page-title', __('leave.edit.page_title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.leaves.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('leave.edit.back') }}
        </a>
        <a href="{{ route('admin.leaves.show', $leave->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            {{ __('leave.edit.view_details') }}
        </a>
    </div>
    <h2>{{ __('leave.edit.header') }}</h2>
    <p>{{ __('leave.edit.update_note') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.leaves.update', $leave->id ) }}" method="POST" enctype="multipart/form-data" class="leave-form">
        @csrf
        @method('PUT')

        <!-- Current Status Display -->
        <div class="current-status">
            <div class="status-info">
                <div class="employee-avatar">

                @if(!empty($leave->user->photo) && Storage::disk('public')->exists($leave->user->photo))
                <img src="{{ asset('storage/' . $leave->user->photo) }}" alt="{{ $leave->user->name }}" 
                    style="width:40px; height:40px; border-radius:50%; object-fit:cover;">
            @else
                <span >
                    {{ strtoupper(substr($leave->user->name, 0, 1)) }}
                </span>
            @endif  

                </div>
                <div>
                    <h3>{{ $leave->user->name ?? '' }}</h3>
                    <p>{{ $leave->user->employee_code ?? '' }} • Applied on {{ $leave->created_at->format('Y-m-d') ?? '' }}</p>
                </div>
            </div>
            <div class="current-status-badge">
                <span class="status-badge status-{{ $leave->status ?? 'pending' }}">
                    {{ ucfirst(str_replace('_', ' ', $leave->status)) }}
                </span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                Update Leave Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="type">{{ __('leave.fields.leave_type') }} *</label>
                    <select id="type" name="type" required>
                        <option value="sick_leave" {{ ($leave->type ?? 'sick_leave') === 'sick_leave' ? 'selected' : '' }}>{{ __('leave.types.sick_leave') }}</option>
                        <option value="casual_leave" {{ ($leave->type ?? '') === 'casual_leave' ? 'selected' : '' }}>{{ __('leave.types.casual_leave') }}</option>
                        <option value="annual_leave" {{ ($leave->type ?? '') === 'annual_leave' ? 'selected' : '' }}>{{ __('leave.types.annual_leave') }}</option>
                        <option value="maternity_leave" {{ ($leave->type ?? '') === 'maternity_leave' ? 'selected' : '' }}>{{ __('leave.types.maternity_leave') }}</option>
                        <option value="emergency_leave" {{ ($leave->type ?? '') === 'emergency_leave' ? 'selected' : '' }}>{{ __('leave.types.emergency_leave') }}</option>
                     </select>
                    @error('type')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">{{ __('leave.fields.start_date') }} *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $leave->start_date ?? '2025-10-10') }}" required>
                    @error('start_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">{{ __('leave.fields.end_date') }} *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $leave->end_date ?? '2025-10-12') }}" required>
                    @error('end_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="calculated_days">Total Days</label>
                    <input type="text" id="calculated_days" readonly class="calculated-field" value="3 days">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                Leave Reason & Details
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="reason">Reason for Leave *</label>
                    <textarea id="reason" name="reason" rows="4" required>{{ old('reason', $leave->reason ?? 'Medical checkup and treatment required. Doctor has advised 3 days rest for recovery.') }}</textarea>
                    @error('reason')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

              
                <div class="form-group">
                    <label for="documents">Additional Documents</label>
                    <input type="file" id="documents" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                    <small class="form-help">Upload additional supporting documents if needed. Max 5MB per file.</small>
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

        <!-- Admin Notes Section -->
        @if(auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager']))
        <div class="form-section" style="display:none">
            <h3 class="section-title">
                <i class="material-icons">note</i>
                Administrative Notes
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="admin_status">Update Status</label>
                    <select id="admin_status" name="status">
                        <option value="pending" {{ ($leave->status ?? 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($leave->status ?? '') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($leave->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ ($leave->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approved_by">Approved/Handled By</label>
                    <input type="text" id="approved_by" name="approved_by" 
                           value="{{ old('approved_by', $leave->approved_by ?? auth()->user()->name) }}" readonly>
                </div>

                <div class="form-group full-width">
                    <label for="admin_remarks">Admin Remarks</label>
                    <textarea id="admin_remarks" name="admin_remarks" rows="3" 
                              placeholder="Add administrative notes or remarks">{{ old('admin_remarks', $leave->admin_remarks ?? '') }}</textarea>
                </div>

               
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
                    <div class="audit-action">Leave request submitted</div>
                    <div class="audit-user">{{ $leave->employee_name ?? 'Rajesh Kumar' }}</div>
                </div>

                @if(($leave->status ?? '') === 'approved')
                <div class="audit-item">
                    <div class="audit-time">Oct 08, 2025 - 10:15 AM</div>
                    <div class="audit-action">Leave request approved</div>
                    <div class="audit-user">{{ $leave->approved_by ?? 'HR Manager' }}</div>
                </div>
                @endif
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('leave.edit.update_button') }}
            </button>
            <a href="{{ route('admin.leaves.show', $leave->id ?? 1) }}" class="btn-cancel">
                {{ __('leave.edit.cancel') }}
            </a>
            <button type="button" class="btn-danger" onclick="deleteLeave()">
                <i class="material-icons">delete</i>
                {{ __('leave.show.delete_request') }}
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
    if (confirm(@json(__('leave.edit.remove_document_confirm', ['name' => '${documentName}'])))) {
         // TODO: Implement document removal via AJAX
        alert(@json(__('leave.edit.remove_document_alert')));
     }
}

function deleteLeave() {
    if (confirm(@json(__('leave.show.delete_confirm')))) {
         fetch(`/admin/leaves/{{ $leave->id ?? 1 }}`, {
             method: 'DELETE',
             headers: {
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                 'Content-Type': 'application/json'
             }
         })
         .then(response => response.json())
         .then(data => {
             if (data.success) {
                alert(@json(__('leave.show.delete_success')));
                 window.location.href = '/admin/leaves';
             } else {
                alert(@json(__('leave.show.delete_error')));
             }
         })
         .catch(error => {
             console.error('Error:', error);
            alert(@json(__('leave.show.delete_error')));
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
