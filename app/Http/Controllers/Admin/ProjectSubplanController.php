<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectSubplan;
use App\Models\ProjectStep;
use App\Models\Project;
use Illuminate\Support\Facades\Storage;


class ProjectSubplanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($stepId)
    {
        //$step = ProjectStep::with('subplans')->findOrFail($stepId);
       // return view('admin.projects.subplans.index', compact('step'));
        $step = ProjectStep::with(['subplans', 'project'])->findOrFail($stepId);

        // Get the project and project_id from the step
        $project = $step->project;
        $project_id = $project ? $project->id : null;

        return view('admin.projects.subplans.index', compact('step', 'project', 'project_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($stepId)
    {
        $step = ProjectStep::findOrFail($stepId);
        return view('admin.projects.subplans.create', compact('step'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $stepId)
    {
        $request->validate([
            'activity_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        ProjectSubplan::create([
            'project_step_id' => $stepId,
            'activity_name' => $request->activity_name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'progress_percentage' => $request->progress_percentage ?? 0,
        ]);

        return redirect()->route('admin.subplans.index', $stepId)
                         ->with('success', 'Sub-plan created successfully!');
    }

    public function edit($id)
    {
        $subplan = ProjectSubplan::findOrFail($id);
        return view('admin.projects.subplans.edit', compact('subplan'));
    }

    public function update(Request $request, $id)
    {
        $subplan = ProjectSubplan::findOrFail($id);

        $request->validate([
            'activity_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress_percentage' => 'nullable|integer|min:0|max:100',
        ]);

        $subplan->update($request->all());

        return redirect()->route('admin.subplans.index', $subplan->project_step_id)
                         ->with('success', 'Sub-plan updated successfully!');
    }

    public function destroy($id)
    {
        $subplan = ProjectSubplan::findOrFail($id);
        $subplan->delete();

        return back()->with('success', 'Sub-plan deleted successfully!');
    }
    
}
