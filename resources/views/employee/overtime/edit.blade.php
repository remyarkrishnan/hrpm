@extends('layouts.admin')

@section('title', 'Edit Overtime Request - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Edit Overtime Request')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.overtime.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Overtime
        </a>
        <a href="{{ route('admin.overtime.show', $overtime->id ?? 1) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            View Details
        </a>
    </div>
    <h2>Edit Overtime Request</h2>
    <p>Update overtime request for {{ $overtime->employee_name ?? 'Rajesh Kumar' }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.overtime.update', $overtime->id ?? 1) }}" method="POST" class="overtime-form">
        @csrf
        @method('PUT')

        <!-- Current Status Display -->
        <div class="current-status">
            <div class="status-info">
                <div class="employee-avatar">{{ strtoupper(substr($overtime->employee_name ?? 'Rajesh Kumar', 0, 1)) }}</div>
                <div>
                    <h3>{{ $overtime->employee_name ?? 'Rajesh Kumar' }}</h3>
                    <p>{{ $overtime->employee_code ?? 'EMP-001' }} • Requested on {{ $overtime->date ?? '2025-10-07' }}</p>
                </div>
            </div>
            <div class="current-status-badge">
                <span class="status-badge status-{{ $overtime->status ?? 'approved' }}">
                    {{ ucfirst($overtime->status ?? 'Approved') }}
                </span>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">edit</i>
                Update Overtime Information
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="date">Overtime Date *</label>
                    <input type="date" id="date" name="date" value="{{ old('date', $overtime->date ?? '2025-10-07') }}" required>
                    @error('date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="hours">Overtime Hours *</label>
                    <input type="number" id="hours" name="hours" step="0.5" min="0.5" max="8" 
                           value="{{ old('hours', $overtime->hours ?? '2.5') }}" required>
                    @error('hours')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_time">Start Time</label>
                    <input type="time" id="start_time" name="start_time" 
                           value="{{ old('start_time', $overtime->start_time ?? '18:00') }}">
                </div>

                <div class="form-group">
                    <label for="end_time">End Time</label>
                    <input type="time" id="end_time" name="end_time" 
                           value="{{ old('end_time', $overtime->end_time ?? '20:30') }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                Update Reason & Details
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="reason">Reason for Overtime *</label>
                    <textarea id="reason" name="reason" rows="4" required>{{ old('reason', $overtime->reason ?? 'Project deadline approaching. Additional foundation work required to meet construction schedule. Critical phase completion needed.') }}</textarea>
                    @error('reason')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="project_id">Project Assignment</label>
                    <select id="project_id" name="project_id">
                        <option value="">Select Project (Optional)</option>
                        <option value="1" {{ ($overtime->project_id ?? 1) == 1 ? 'selected' : '' }}>Residential Complex - Phase 2</option>
                        <option value="2" {{ ($overtime->project_id ?? '') == 2 ? 'selected' : '' }}>Commercial Mall Construction</option>
                        <option value="3" {{ ($overtime->project_id ?? '') == 3 ? 'selected' : '' }}>Highway Bridge Project</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="urgency_level">Urgency Level</label>
                    <select id="urgency_level" name="urgency_level">
                        <option value="normal" {{ ($overtime->urgency_level ?? 'urgent') === 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="urgent" {{ ($overtime->urgency_level ?? 'urgent') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                        <option value="critical" {{ ($overtime->urgency_level ?? '') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Payment Calculation (Updated) -->
        <div class="calculation-section">
            <h3 class="section-title">
                <i class="material-icons">calculate</i>
                Updated Rate Calculation
            </h3>

            <div class="calculation-grid">
                <div class="calc-item">
                    <h4>Base Hourly Rate</h4>
                    <div class="calc-value">₹500.00</div>
                </div>

                <div class="calc-item">
                    <h4>Overtime Multiplier</h4>
                    <div class="calc-value">1.5x</div>
                </div>

                <div class="calc-item">
                    <h4>Overtime Rate</h4>
                    <div class="calc-value">₹750.00/hr</div>
                </div>

                <div class="calc-item total">
                    <h4>Updated Total</h4>
                    <div class="calc-value" id="total-amount">₹{{ number_format(($overtime->hours ?? 2.5) * 750, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Admin Actions -->
        @if(auth()->user()->hasRole(['super-admin', 'admin', 'project-manager']))
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">admin_panel_settings</i>
                Administrative Actions
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="admin_status">Update Status</label>
                    <select id="admin_status" name="status">
                        <option value="pending" {{ ($overtime->status ?? 'approved') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($overtime->status ?? 'approved') === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ ($overtime->status ?? '') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="cancelled" {{ ($overtime->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="approved_by">Approved/Handled By</label>
                    <input type="text" id="approved_by" name="approved_by" 
                           value="{{ old('approved_by', $overtime->approved_by ?? auth()->user()->name) }}" readonly>
                </div>

                <div class="form-group full-width">
                    <label for="admin_remarks">Admin Remarks</label>
                    <textarea id="admin_remarks" name="admin_remarks" rows="3" 
                              placeholder="Add administrative notes or approval remarks">{{ old('admin_remarks', $overtime->admin_remarks ?? '') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="rejection_reason">Rejection Reason (if rejected)</label>
                    <input type="text" id="rejection_reason" name="rejection_reason" 
                           value="{{ old('rejection_reason', $overtime->rejection_reason ?? '') }}"
                           placeholder="Enter reason if rejecting the overtime">
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
                    <div class="audit-time">Oct 07, 2025 - 06:30 PM</div>
                    <div class="audit-action">Overtime request submitted</div>
                    <div class="audit-user">{{ $overtime->employee_name ?? 'Rajesh Kumar' }}</div>
                </div>

                @if(($overtime->status ?? '') === 'approved')
                <div class="audit-item">
                    <div class="audit-time">Oct 08, 2025 - 09:30 AM</div>
                    <div class="audit-action">Overtime request approved</div>
                    <div class="audit-user">{{ $overtime->approved_by ?? 'Project Manager Singh' }}</div>
                </div>
                @endif

                <div class="audit-item current">
                    <div class="audit-time">{{ now()->format('M d, Y - h:i A') }}</div>
                    <div class="audit-action">Overtime request being modified</div>
                    <div class="audit-user">{{ auth()->user()->name }}</div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                Update Overtime Request
            </button>
            <a href="{{ route('admin.overtime.show', $overtime->id ?? 1) }}" class="btn-cancel">
                Cancel Changes
            </a>
            <button type="button" class="btn-danger" onclick="deleteOvertime()">
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

    .overtime-form {
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

    .form-section, .calculation-section, .audit-section {
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

    .form-group input[readonly] {
        background: #f5f5f5 !important;
        color: #666;
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
    }

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

    .audit-item.current {
        background: #e3f2fd;
        border-left-color: #2196F3;
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
        .form-grid, .calculation-grid { grid-template-columns: 1fr; }
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

    // Initial calculation
    calculateTotal();
});

function deleteOvertime() {
    if (confirm('Are you sure you want to delete this overtime request?\n\nThis action cannot be undone.')) {
        fetch(`/admin/overtime/{{ $overtime->id ?? 1 }}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Overtime request deleted successfully');
                window.location.href = '/admin/overtime';
            } else {
                alert('Error deleting overtime request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting overtime request');
        });
    }
}

// Form submission with loading state
document.querySelector('.overtime-form').addEventListener('submit', function(e) {
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Updating...';
    submitButton.disabled = true;
});
</script>
@endpush