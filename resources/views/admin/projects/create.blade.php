@extends('layouts.admin')

@section('title', 'Create Project - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Create New Project')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Projects
        </a>
    </div>
   
    <p>Add a new project with 12-step approval workflow for {{ env('COMPANY_NAME', 'Teqin Vally') }}</p>
</div>

<form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            Project Information
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Project Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                       placeholder="e.g. Residential Complex Phase 2">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">Project Code *</label>
                <input type="text" id="project_code" name="project_code" value="{{ old('project_code') }}" required
                       placeholder="e.g. PROJ-RES-2024-001">
                @error('project_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            

            <div class="form-group">
                <label for="priority">Priority *</label>
                <select id="priority" name="priority" required>
                    <option value="">Select Priority</option>
                    <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                </select>
                @error('priority')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description">Project Description *</label>
                <textarea id="description" name="description" rows="4" required 
                          placeholder="Detailed project description, scope, and objectives">{{ old('description') }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}" required
                       placeholder="e.g. Sector 15, Gurgaon">
                @error('location')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Client Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">business</i>
            Client Information
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="client_name">Client Name *</label>
                <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" required
                       placeholder="e.g. ABC Developers Pvt Ltd">
                @error('client_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_contact">Client Contact</label>
                <input type="text" id="client_contact" name="client_contact" value="{{ old('client_contact') }}"
                       placeholder="Phone/Email/Contact Person">
                @error('client_contact')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Project Planning -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">schedule</i>
            Project Planning
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="budget">Project Budget (₹) *</label>
                <input type="number" id="budget" name="budget" value="{{ old('budget') }}" required
                       min="0" step="0.01" placeholder="e.g. 5000000">
                @error('budget')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">Start Date *</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', date('Y-m-d')) }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="end_date">End Date *</label>
                <input type="date" id="end_date" name="end_date" 
                       value="{{ old('end_date') }}" required>
                @error('end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_manager_id">Project Manager *</label>
                <select id="manager_id" name="manager_id" required>
                    <option value="">Select Project Manager</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->designation ?? 'Project Manager' }}
                        </option>
                    @endforeach
                </select>
                @error('manager_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>



    <!-- 12-Step Approval Workflow Info -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">approval</i>
            Approval Workflow
        </h3>

        <div class="workflow-info">
            <p class="workflow-description">
                <strong>12-Step Approval Process:</strong> This project will automatically generate a 12-step approval workflow 
                with the following consultancy stages:
            </p>

            <div class="workflow-steps">
                <div class="workflow-grid">
                    <div class="workflow-step">1. Design Consultancy</div>
                    <div class="workflow-step">2. Environment Consultancy</div>
                    <div class="workflow-step">3. Construction Management</div>
                    <div class="workflow-step">4. Safety Consultancy</div>
                    <div class="workflow-step">5. Testing & Commissioning</div>
                    <div class="workflow-step">6. Finance Approval</div>
                    <div class="workflow-step">7. Procurement</div>
                    <div class="workflow-step">8. Quality Control</div>
                    <div class="workflow-step">9. Inspection</div>
                    <div class="workflow-step">10. Final Approval</div>
                    <div class="workflow-step">11. Documentation</div>
                    <div class="workflow-step">12. Completion</div>


                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            Create Project
        </button>
        <a href="{{ route('admin.projects.index') }}" class="btn-cancel">
            Cancel
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

    .project-form {
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

    .form-help {
        color: #666;
        font-size: 12px;
        margin-top: 4px;
    }

    /* Workflow Info Styles */
    .workflow-info {
        background: #f8f9fa;
        padding: 24px;
        border-radius: 12px;
        border-left: 4px solid #6750A4;
    }

    .workflow-description {
        margin: 0 0 20px 0;
        color: #333;
        line-height: 1.5;
    }

    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
    }

    .workflow-step {
        background: white;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        color: #6750A4;
        text-align: center;
        border: 1px solid #e0e0e0;
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
        .workflow-grid { grid-template-columns: repeat(2, 1fr); }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const projectCodeInput = document.getElementById('project_code');
    const typeSelect = document.getElementById('type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('expected_end_date');

    // Auto-generate project code based on name and type
    function generateProjectCode() {
        const name = nameInput.value;
        const type = typeSelect.value;
        const year = new Date().getFullYear();

        if (name && type) {
            const nameCode = name.substring(0, 3).toUpperCase();
            const typeCode = type.substring(0, 3).toUpperCase();
            const randomNum = Math.floor(Math.random() * 100).toString().padStart(2, '0');

            projectCodeInput.value = `${typeCode}-${nameCode}-${year}-${randomNum}`;
        }
    }

    nameInput.addEventListener('blur', generateProjectCode);
    typeSelect.addEventListener('change', generateProjectCode);

    // Set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 30); // Minimum 30 days

        endDateInput.min = minEndDate.toISOString().split('T')[0];

        if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
            endDateInput.value = minEndDate.toISOString().split('T')[0];
        }
    });

    // Form validation
    document.querySelector('.project-form').addEventListener('submit', function(e) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (endDate <= startDate) {
            e.preventDefault();
            alert('Expected completion date must be after start date');
            return;
        }

        // Show loading state
        const submitButton = this.querySelector('button[type="submit"]');
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Creating Project...';
        submitButton.disabled = true;
    });
});
</script>
@endpush