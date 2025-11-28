<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectStepController extends Controller
{
    // Update progress / upload docs / change status
    public function update(Request $request, $stepId)
    {
        $step = ProjectStep::findOrFail($stepId);

        $request->validate([
            'progress_percent'=>'nullable|integer|min:0|max:100',
            'remarks'=>'nullable|string',
            'status'=>'nullable|in:pending,in_progress,approved',
            'responsible_person_id'=>'nullable|exists:users,id',
            'due_date'=>'nullable|date',
            'supporting_document' => 'nullable|array',
            'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($request->filled('progress_percent')) $step->progress_percent = $request->progress_percent;
        if ($request->filled('remarks')) $step->remarks = $request->remarks;
        if ($request->filled('status')) {
            $step->status = $request->status;
            if ($request->status === 'approved') $step->approved_at = now();
        }
        if ($request->filled('responsible_person_id')) $step->responsible_person_id = $request->responsible_person_id;
        if ($request->filled('due_date')) $step->due_date = $request->due_date;

        // handle multiple documents
        if ($request->hasFile('documents')) {
            $docs = $step->documents ?? [];
            foreach ($request->file('documents') as $file) {
                $path = $file->store('project_steps', 'public');
                $docs[] = $path;
            }
            $step->documents = $docs;
        }

        $step->save();

        // update project completion (simple average)
        $project = $step->project;
        $avg = intval($project->steps()->avg('progress_percent'));
        $project->completion_percentage = $avg;
        $project->save();

        return back()->with('success','Step updated');
    }

    // Approve step quickly via AJAX
    public function approve(Request $request, $stepId)
    {
        $step = ProjectStep::findOrFail($stepId);

        // permission: allow only admin or assigned consultant
        if (!auth()->user()->hasRole(['super-admin','admin']) && auth()->id() !== $step->consultant_id) {
            return response()->json(['success'=>false,'message'=>'Access denied'],403);
        }

        $step->status = 'approved';
        $step->approved_at = now();
        $step->remarks = $request->remarks ?? $step->remarks;

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('project_steps', 'public');
            $docs = $step->documents ?? [];
            $docs[] = $path;
            $step->documents = $docs;
        }

        $step->save();

        // update project completion
        $project = $step->project;
        $project->completion_percentage = intval($project->steps()->avg('progress_percent'));
        $project->save();

        return response()->json(['success'=>true,'message'=>'Step approved']);
    }

    // download document by index
    public function download($stepId, $index = 0)
    {
        $step = ProjectStep::findOrFail($stepId);
        $docs = $step->documents ?? [];
        if (!isset($docs[$index])) abort(404);
        return response()->download(storage_path('app/public/'.$docs[$index]));
    }
}
