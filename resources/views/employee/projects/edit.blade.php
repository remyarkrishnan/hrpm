@extends('layouts.admin')

@section('title', 'Edit Project - ' . $project->name . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Edit Project')

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.projects.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Projects
        </a>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-secondary">
            <i class="material-icons">visibility</i>
            View Project
        </a>
    </div>
    <h2>Edit Project: {{ $project->name }}</h2>
    <p>Update project information and settings</p>
</div>

<form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf
    @method('PUT')

    <!-- Basic Project Information -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">info</i>
            Project Information
        </h3>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Project Name *</label>
                <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required>
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">Project Code *</label>
                <input type="text" id="project_code" name="project_code" 
                       value="{{ old('project_code', $project->project_code) }}" required>
                @error('project_code')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Project Type *</label>
                <select id="type" name="type" required>
                    @foreach(App\Models\Project::getTypes() as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $project->type) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('type')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="priority">Priority *</label>
                <select id="priority" name="priority" required>
                    @foreach(App\Models\Project::getPriorities() as $key => $label)
                        <option value="{{ $key }}" {{ old('priority', $project->priority) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('priority')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="status">Project Status *</label>
                <select id="status" name="status" required>
                    @foreach(App\Models\Project::getStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $project->status) === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="progress_percentage">Progress Percentage</label>
                <input type="number" id="progress_percentage" name="progress_percentage" 
                       value="{{ old('progress_percentage', $project->progress_percentage) }}" 
                       min="0" max="100" step="0.1">
                @error('progress_percentage')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group full-width">
                <label for="description">Project Description *</label>
                <textarea id="description" name="description" rows="4" required>{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" value="{{ old('location', $project->location) }}" required>
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
                <input type="text" id="client_name" name="client_name" 
                       value="{{ old('client_name', $project->client_name) }}" required>
                @error('client_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="client_contact">Client Contact</label>
                <input type="text" id="client_contact" name="client_contact" 
                       value="{{ old('client_contact', $project->client_contact) }}">
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
                <input type="number" id="budget" name="budget" value="{{ old('budget', $project->budget) }}" 
                       required min="0" step="0.01">
                @error('budget')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="start_date">Start Date *</label>
                <input type="date" id="start_date" name="start_date" 
                       value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" required>
                @error('start_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="expected_end_date">Expected Completion Date *</label>
                <input type="date" id="expected_end_date" name="expected_end_date" 
                       value="{{ old('expected_end_date', $project->expected_end_date?->format('Y-m-d')) }}" required>
                @error('expected_end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="actual_end_date">Actual Completion Date</label>
                <input type="date" id="actual_end_date" name="actual_end_date" 
                       value="{{ old('actual_end_date', $project->actual_end_date?->format('Y-m-d')) }}">
                @error('actual_end_date')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_manager_id">Project Manager *</label>
                <select id="project_manager_id" name="project_manager_id" required>
                    <option value="">Select Project Manager</option>
                    @foreach($projectManagers as $manager)
                        <option value="{{ $manager->id }}" 
                                {{ old('project_manager_id', $project->project_manager_id) == $manager->id ? 'selected' : '' }}>
                            {{ $manager->name }} - {{ $manager->designation ?? 'Project Manager' }}
                        </option>
                    @endforeach
                </select>
                @error('project_manager_id')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Current Documents -->
    @if($project->documents && count($project->documents) > 0)
        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">folder</i>
                Current Documents
            </h3>

            <div class="document-list">
                @foreach($project->documents as $document)
                    <div class="document-item">
                        <div class="document-info">
                            <i class="material-icons">description</i>
                            <span>{{ $document }}</span>
                        </div>
                        <a href="{{ Storage::url('project-documents/' . $document) }}" 
                           target="_blank" class="btn-view">
                            <i class="material-icons">open_in_new</i>
                            View
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Upload New Documents -->
    <div class="form-section">
        <h3 class="section-title">
            <i class="material-icons">attachment</i>
            Upload Additional Documents
        </h3>

        <div class="form-grid">
            <div class="form-group full-width">
                <label for="documents">Upload New Documents (Optional)</label>
                <input type="file" id="documents" name="documents[]" multiple
                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                <small class="form-help">Accepted formats: PDF, DOC, DOCX, JPG, PNG. Max 5MB per file.</small>
                @error('documents.*')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            Update Project
        </button>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn-cancel">
            Cancel
        </a>
        <button type="button" onclick="deleteProject({{ $project->id }})" class="btn-danger">
            <i class="material-icons">delete</i>
            Delete Project
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

    /* Document List Styles */
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

    .btn-view {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6750A4;
        text-decoration: none;
        padding: 6px 12px;
        border-radius: 6px;
        transition: background 0.2s;
        font-size: 14px;
    }

    .btn-view:hover { background: rgba(103, 80, 164, 0.08); }

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
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('expected_end_date');

    // Set minimum end date based on start date
    startDateInput.addEventListener('change', function() {
        const startDate = new Date(this.value);
        const minEndDate = new Date(startDate);
        minEndDate.setDate(minEndDate.getDate() + 1);

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
        submitButton.innerHTML = '<i class="material-icons">hourglass_empty</i> Updating Project...';
        submitButton.disabled = true;
    });
});

// Delete project function
async function deleteProject(projectId) {
    if (!confirm('Are you sure you want to delete this project?\n\nThis will permanently remove:\n• Project information and documents\n• All approval steps and progress\n• Resource allocations\n\nType "DELETE" to confirm.')) {
        return;
    }

    const confirmation = prompt('Type "DELETE" to confirm project deletion:');
    if (confirmation !== 'DELETE') {
        alert('Deletion cancelled. You must type "DELETE" exactly.');
        return;
    }

    try {
        const response = await fetch(`/admin/projects/${projectId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();

        if (data.success) {
            alert('Project deleted successfully');
            window.location.href = '/admin/projects';
        } else {
            alert(data.message || 'Failed to delete project');
        }
    } catch (error) {
        alert('Error deleting project: ' + error.message);
        console.error(error);
    }
}
</script>
@endpush