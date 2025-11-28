<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectSubplan;
use App\Models\ProjectStep;
use App\Models\ResourceAllocation;

class ResourceOverviewController extends Controller
{
    public function index($id)
    {
        $project = Project::with([
            'steps.subplans.allocations.employee'
        ])->findOrFail($id);

        $totalSteps = $project->steps->count();
        $totalSubplans = $project->steps->flatMap->subplans->count();

        return view('admin.projects.resource_overview', compact(
            'project', 'totalSteps', 'totalSubplans'
        ));
        
    }
  
}
