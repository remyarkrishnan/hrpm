@php
    $locale = session('locale', config('app.locale'));
    app()->setLocale($locale);
@endphp
@extends('layouts.admin')

@section('title', __('employee/planning/create.title') . ' - ' . env('COMPANY_NAME', 'Teqin Vally'))
@section('page-title', __('employee/planning/create.title'))

@section('content')
<div class="page-header">
    <div class="page-nav">
        <a href="{{ route('admin.planning.index') }}" class="btn-back">
            <i class="material-icons">arrow_back</i>
            {{ __('employee/planning/common.actions.back') }}
        </a>
    </div>
    <h2>{{ __('employee/planning/create.title') }}</h2>
    <p>{{ __('employee/planning/create.subtitle') }}</p>
</div>

<div class="form-container">
    <form action="{{ route('admin.planning.store') }}" method="POST" class="planning-form">
        @csrf

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">engineering</i>
                {{ __('employee/planning/create.sections.info') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="project_id">{{ __('employee/planning/create.form.select_project') }} *</label>
                    <select id="project_id" name="project_id" required>
                        <option value="">{{ __('employee/planning/create.form.choose') }}</option>
                        <option value="1">Residential Complex - Phase 2</option>
                        <option value="2">Commercial Mall Construction</option>
                        <option value="3">Highway Bridge Project</option>
                    </select>
                    @error('project_id')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="plan_name">{{ __('employee/planning/create.form.plan_name') }} *</label>
                    <input type="text" id="plan_name" name="plan_name" value="{{ old('plan_name') }}" required 
                           placeholder="{{ __('employee/planning/create.form.plan_placeholder') }}">
                    @error('plan_name')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">{{ __('employee/planning/create.form.start_date') }} *</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_date">{{ __('employee/planning/create.form.end_date') }} *</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                    @error('end_date')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">timeline</i>
                {{ __('employee/planning/create.sections.phases') }}
            </h3>

            <div class="phases-container">
                <div class="phase-item">
                    <div class="phase-header">
                        <h4>Phase 1: {{ __('employee/planning/common.phases.foundation') }}</h4>
                        <input type="checkbox" name="phases[]" value="foundation" checked>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="foundation_duration" value="45" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_alloc') }}</label>
                                <input type="number" name="foundation_budget" value="30" min="1" max="100">
                            </div>
                        </div>
                        <textarea name="foundation_description" rows="2" 
                                  placeholder="Site preparation, excavation, foundation laying">Site preparation, excavation, foundation laying</textarea>
                    </div>
                </div>

                <div class="phase-item">
                    <div class="phase-header">
                        <h4>Phase 2: {{ __('employee/planning/common.phases.structure') }}</h4>
                        <input type="checkbox" name="phases[]" value="structure" checked>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="structure_duration" value="90" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_alloc') }}</label>
                                <input type="number" name="structure_budget" value="45" min="1" max="100">
                            </div>
                        </div>
                        <textarea name="structure_description" rows="2" 
                                  placeholder="Framework, walls, structural elements">Framework, walls, structural elements</textarea>
                    </div>
                </div>

                <div class="phase-item">
                    <div class="phase-header">
                        <h4>Phase 3: {{ __('employee/planning/common.phases.finishing') }}</h4>
                        <input type="checkbox" name="phases[]" value="finishing" checked>
                    </div>
                    <div class="phase-content">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.duration') }}</label>
                                <input type="number" name="finishing_duration" value="60" min="1">
                            </div>
                            <div class="form-group">
                                <label>{{ __('employee/planning/common.labels.budget_alloc') }}</label>
                                <input type="number" name="finishing_budget" value="25" min="1" max="100">
                            </div>
                        </div>
                        <textarea name="finishing_description" rows="2" 
                                  placeholder="Interior work, painting, final touches">Interior work, painting, final touches</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">flag</i>
                {{ __('employee/planning/create.sections.milestones') }}
            </h3>

            <div class="milestones-container">
                <div class="milestone-item">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                            <input type="text" name="milestone_1_name" value="Foundation Inspection" placeholder="e.g. Foundation Inspection">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                            <input type="date" name="milestone_1_date">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.phase') }}</label>
                            <select name="milestone_1_phase">
                                <option value="foundation" selected>{{ __('employee/planning/common.phases.foundation') }}</option>
                                <option value="structure">{{ __('employee/planning/common.phases.structure') }}</option>
                                <option value="finishing">{{ __('employee/planning/common.phases.finishing') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="milestone-item">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                            <input type="text" name="milestone_2_name" value="Structural Framework Complete" placeholder="e.g. Structural Framework">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                            <input type="date" name="milestone_2_date">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.phase') }}</label>
                            <select name="milestone_2_phase">
                                <option value="foundation">{{ __('employee/planning/common.phases.foundation') }}</option>
                                <option value="structure" selected>{{ __('employee/planning/common.phases.structure') }}</option>
                                <option value="finishing">{{ __('employee/planning/common.phases.finishing') }}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="milestone-item">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                            <input type="text" name="milestone_3_name" value="Final Quality Check" placeholder="e.g. Final Quality Check">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                            <input type="date" name="milestone_3_date">
                        </div>
                        <div class="form-group">
                            <label>{{ __('employee/planning/common.labels.phase') }}</label>
                            <select name="milestone_3_phase">
                                <option value="foundation">{{ __('employee/planning/common.phases.foundation') }}</option>
                                <option value="structure">{{ __('employee/planning/common.phases.structure') }}</option>
                                <option value="finishing" selected>{{ __('employee/planning/common.phases.finishing') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn-secondary" onclick="addMilestone()">
                <i class="material-icons">add</i>
                {{ __('employee/planning/common.actions.add_milestone') }}
            </button>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">groups</i>
                {{ __('employee/planning/create.sections.resources') }}
            </h3>

            <div class="form-grid">
                <div class="form-group">
                    <label for="project_manager">{{ __('employee/planning/create.form.manager') }}</label>
                    <select id="project_manager" name="project_manager">
                        <option value="">{{ __('employee/planning/create.form.select_manager') }}</option>
                        <option value="1">Rajesh Kumar - Senior PM</option>
                        <option value="2">Priya Singh - Construction Manager</option>
                        <option value="3">Amit Sharma - Site Engineer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="team_size">{{ __('employee/planning/create.form.est_team') }}</label>
                    <input type="number" id="team_size" name="team_size" value="25" min="1" max="200">
                </div>

                <div class="form-group">
                    <label for="budget">{{ __('employee/planning/create.form.total_budget') }} (₹)</label>
                    <input type="number" id="budget" name="budget" value="5000000" min="0" step="10000">
                </div>

                <div class="form-group">
                    <label for="priority">{{ __('employee/planning/create.form.priority') }}</label>
                    <select id="priority" name="priority">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3 class="section-title">
                <i class="material-icons">description</i>
                {{ __('employee/planning/create.sections.details') }}
            </h3>

            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="description">{{ __('employee/planning/create.form.desc') }}</label>
                    <textarea id="description" name="description" rows="4" 
                              placeholder="{{ __('employee/planning/create.form.desc_ph') }}">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="risks">{{ __('employee/planning/create.form.risks') }}</label>
                    <textarea id="risks" name="risks" rows="3" 
                              placeholder="{{ __('employee/planning/create.form.risks_ph') }}">{{ old('risks') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="success_criteria">{{ __('employee/planning/create.form.criteria') }}</label>
                    <textarea id="success_criteria" name="success_criteria" rows="3" 
                              placeholder="{{ __('employee/planning/create.form.criteria_ph') }}">{{ old('success_criteria') }}</textarea>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">
                <i class="material-icons">save</i>
                {{ __('employee/planning/common.actions.create') }}
            </button>
            <a href="{{ route('admin.planning.index') }}" class="btn-cancel">
                {{ __('employee/planning/common.actions.cancel') }}
            </a>
        </div>
    </form>
</div>

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
        max-width: 1000px;
    }

    .planning-form {
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

    .error {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
    }

    .phases-container, .milestones-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .phase-item, .milestone-item {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
    }

    .phase-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .phase-header h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        color: #333;
    }

    .phase-content textarea {
        width: 100%;
        margin-top: 12px;
        resize: vertical;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f5f5f5;
        color: #666;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        margin-top: 16px;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
        color: #333;
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
        .form-actions { flex-direction: column; }
        .btn-primary, .btn-cancel { width: 100%; justify-content: center; }
    }
</style>
@endpush

@push('scripts')
<script>
let milestoneCounter = 3;

function addMilestone() {
    milestoneCounter++;
    const container = document.querySelector('.milestones-container');
    const milestoneHtml = `
        <div class="milestone-item">
            <div class="form-grid">
                <div class="form-group">
                    <label>{{ __('employee/planning/common.labels.milestone_name') }}</label>
                    <input type="text" name="milestone_${milestoneCounter}_name" placeholder="e.g. Quality Inspection">
                </div>
                <div class="form-group">
                    <label>{{ __('employee/planning/common.labels.target_date') }}</label>
                    <input type="date" name="milestone_${milestoneCounter}_date">
                </div>
                <div class="form-group">
                    <label>{{ __('employee/planning/common.labels.phase') }}</label>
                    <select name="milestone_${milestoneCounter}_phase">
                        <option value="foundation">{{ __('employee/planning/common.phases.foundation') }}</option>
                        <option value="structure">{{ __('employee/planning/common.phases.structure') }}</option>
                        <option value="finishing">{{ __('employee/planning/common.phases.finishing') }}</option>
                    </select>
                </div>
            </div>
            <button type="button" class="btn-remove" onclick="removeMilestone(this)">
                <i class="material-icons">delete</i>
                {{ __('employee/planning/common.actions.remove') }}
            </button>
        </div>
    `;

    const addButton = container.nextElementSibling;
    container.insertAdjacentHTML('beforeend', milestoneHtml);
}

function removeMilestone(button) {
    button.closest('.milestone-item').remove();
}

// Calculate total duration and budget validation
document.addEventListener('DOMContentLoaded', function() {
    const budgetInputs = document.querySelectorAll('input[name$="_budget"]');

    function validateBudgetTotal() {
        let total = 0;
        budgetInputs.forEach(input => {
            // Check if user has checkbox checked (if logic exists) or just sum inputs
            const checkbox = input.closest('.phase-item').querySelector('input[type="checkbox"]');
            if (checkbox && checkbox.checked) {
                total += parseInt(input.value) || 0;
            } else if (!checkbox) {
                 total += parseInt(input.value) || 0;
            }
        });

        // Visual feedback for budget total
        budgetInputs.forEach(input => {
            if (total > 100) {
                input.style.borderColor = '#f44336';
            } else {
                input.style.borderColor = '#e0e0e0';
            }
        });
    }

    budgetInputs.forEach(input => {
        input.addEventListener('input', validateBudgetTotal);
    });

    // Initial validation
    validateBudgetTotal();
});
</script>
@endpush
@endsection