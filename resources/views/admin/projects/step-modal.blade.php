<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<!-- ✅ Bootstrap 5.3 CSS -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
  rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
  crossorigin="anonymous"
/>

<!-- ✅ Bootstrap Icons -->
<link
  href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
  rel="stylesheet"
/>

<!-- Optional: small tweak for workflow boxes -->
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

<!-- Step Update Modal -->
<div class="modal fade" id="stepModal{{ $step->id }}" tabindex="-1" aria-labelledby="stepModalLabel{{ $step->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="{{ route('admin.project_steps.update', $step->id) }}" method="POST" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            <div class="modal-header bg-light">
                <h5 class="modal-title" id="stepModalLabel{{ $step->id }}">
                    <i class="bi bi-gear-wide-connected me-2"></i> Update Step: {{ $step->step_name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                    <!-- Step Status -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $step->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $step->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="approved" {{ $step->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        </select>
                    </div>

                    <!-- Responsible Person -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Responsible Person</label>
                        <select name="responsible_person_id" class="form-select">
                            <option value="">-- Select --</option>
                            @foreach($employees as $user)
                                <option value="{{ $user->id }}" {{ $step->responsible_person_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Due Date -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Due Date</label>
                        <input type="date" name="due_date" value="{{ $step->due_date ? $step->due_date->format('Y-m-d') : '' }}" class="form-control">
                    </div>

                    <!-- Progress Percentage -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Progress (%)</label>
                        <input type="number" name="progress_percent" min="0" max="100" value="{{ $step->progress_percent }}" class="form-control">
                    </div>

                    <!-- Remarks -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3">{{ $step->remarks }}</textarea>
                    </div>

                    <!-- Document Upload -->
                    <div class="col-12">
                        <label class="form-label fw-semibold">Supporting Document</label>
                        <input type="file" name="supporting_document[]" class="form-control">

                        @if(!empty($step->documents))
                            <div class="mt-2">
                                <strong>Existing Files:</strong>
                                <ul class="list-unstyled mb-0">
                                    @foreach((array)$step->documents as $file)
                                        <li>
                                            <a href="{{ Storage::url($file) }}" target="_blank" class="text-decoration-none">
                                                <i class="bi bi-file-earmark-text me-1"></i> View Document
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
                    <i class="bi bi-x-circle"></i> Close
                </button>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save2"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>



<!-- ✅ Bootstrap 5.3 JS (includes Popper) -->
<script
  src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
  crossorigin="anonymous"
></script>

