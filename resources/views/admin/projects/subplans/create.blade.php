@extends('layouts.admin')

@section('title', 'Create Subplan for Step: ' . $step->step_name . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', 'Create Subplan for Step: ' . $step->step_name)

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.subplans.index', $step->id) }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            Back to Subplans for {{$step->step_name}}
        </a>
    </div>
   
    <p>Add a new subplan</p>
</div>

<form action="{{ route('admin.subplans.store', $step->id) }}" method="POST" enctype="multipart/form-data" class="project-form">
    @csrf

    <!-- Basic Project Information -->
    <div class="form-section">
      
        <div class="form-grid">
            <div class="form-group">
                <label for="name">Activity Name *</label>
                  <input type="text" name="activity_name" class="form-control" required value="{{ old('activity_name') }}">
                @error('activity_name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="project_code">Description </label>
                 <textarea name="description" class="form-control">{{ old('description') }}</textarea>
               
                @error('description')
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
                <label for="name">Progress %</label>
                  <input type="number" name="progress_percentage"  min="0" max="100" value="0" class="form-control"  value=">{{ old('progress_percentage') }}">
                @error('progress_percentage')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

    <!-- Submit Buttons -->
    <div class="form-actions">
        <button type="submit" class="btn-primary">
            <i class="material-icons">save</i>
            Create Subplan
        </button>
        <a href="{{ route('admin.subplans.index', $step->id) }}" class="btn-cancel">
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

