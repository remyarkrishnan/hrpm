<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectProgressController extends Controller
{
    public function show($projectId)
    {
        $project = Project::with(['steps.subplans'])->findOrFail($projectId);

        // Overall completion: average of subplans' progress
        $totalSubplans = $project->steps->flatMap->subplans->count();
        $completed = $project->steps->flatMap->subplans->sum('progress_percentage');
        $completion = $totalSubplans > 0 ? round($completed / $totalSubplans, 2) : 0;

        // Delay alert: compare end_date with today
        $delayed = $project->steps->flatMap->subplans->filter(function ($subplan) {
            return isset($subplan->end_date) && $subplan->end_date < now() && ($subplan->progress_percentage ?? 0) < 100;
        })->count();

        return view('admin.projects.progress.show', compact('project', 'completion', 'delayed'));

        
    }
}
