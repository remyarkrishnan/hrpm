<!-- Step Update Modal -->
<div class="modal fade" id="stepModal{{ $step->id }}" tabindex="-1" aria-labelledby="stepModalLabel{{ $step->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('admin.project_steps.update', $step->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="stepModalLabel{{ $step->id }}">
                    <i class="bi bi-gear-wide-connected me-2"></i> 
                    {{ __('projects.step_modal.update_step_title', ['step' => $step->step_name]) }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('projects.step_modal.close') }}"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Step Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.status_label') }}</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $step->status === 'pending' ? 'selected' : '' }}>
                                {{ __('projects.step_modal.status.pending') }}
                            </option>
                            <option value="in_progress" {{ $step->status === 'in_progress' ? 'selected' : '' }}>
                                {{ __('projects.step_modal.status.in_progress') }}
                            </option>
                            <option value="approved" {{ $step->status === 'approved' ? 'selected' : '' }}>
                                {{ __('projects.step_modal.status.approved') }}
                            </option>
                        </select>
                    </div>

                    <!-- Responsible Person -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.responsible_label') }}</label>
                        <select name="responsible_person_id" class="form-select">
                            <option value="">{{ __('projects.step_modal.select_person') }}</option>
                            @foreach($employees as $user)
                                <option value="{{ $user->id }}" {{ $step->responsible_person_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.due_date_label') }}</label>
                        <input type="date" name="due_date" value="{{ $step->due_date ? $step->due_date->format('Y-m-d') : '' }}" class="form-control">
                    </div>

                    <!-- Progress Percentage -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.progress_label') }}</label>
                        <input type="number" name="progress_percent" min="0" max="100" value="{{ $step->progress_percent }}" class="form-control">
                    </div>

                    <!-- Remarks -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.remarks_label') }}</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $step->remarks }}</textarea>
                    </div>

                    <!-- Document Upload -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">{{ __('projects.step_modal.document_label') }}</label>
                        <input type="file" name="supporting_document[]" class="form-control" multiple>

                        @if(!empty($step->documents))
                            <div class="mt-2">
                                <strong>{{ __('projects.step_modal.existing_files') }}:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach((array)$step->documents as $file)
                                        <li>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="text-decoration-none">
                                                <i class="bi bi-file-earmark-text me-1"></i> 
                                                {{ __('projects.step_modal.view_document') }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> {{ __('projects.step_modal.close_button') }}
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save2"></i> {{ __('projects.step_modal.save_button') }}
                </button>
            </div>
        </form>
    </div>
</div>

<style>
.workflow-step {
    background: #fff;
    border: 1px solid #ddd;
    border-left: 6px solid #0d6efd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,.05);
}
.workflow-step.step-approved { border-left-color: #28a745; }
.workflow-step.step-in_progress { border-left-color: #ffc107; }
.workflow-step.step-pending { border-left-color: #6c757d; }

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    color: #fff;
    font-size: 0.85rem;
}
.status-approved { background: #28a745; }
.status-in_progress { background: #ffc107; color: #000; }
.status-pending { background: #6c757d; }

.btn-icon {
    border: none;
    background: transparent;
    color: #0d6efd;
}
.btn-icon:hover { color: #0a58ca; }
</style>
