<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $query = Project::with(['manager']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by project manager
        if ($request->filled('project_manager')) {
            $query->where('project_manager_id', $request->get('project_manager'));
        }

        $projects = $query->latest()->paginate(15);
        $projectManagers = User::whereHas('roles', function($q) {
            $q->where('name', 'project-manager');
        })->get();

        return view('admin.projects.index', compact('projects', 'projectManagers'));
    }

    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $projectManagers = User::whereHas('roles', function($q) {
            $q->where('name', 'project-manager');
        })->get();

        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'consultant']);
        })->get();

        return view('admin.projects.create', compact('projectManagers', 'employees'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_code' => 'required|string|unique:projects,project_code',
            'location' => 'required|string',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'manager_id' => 'required|exists:users,id',
        ]);

        $data['created_by'] = auth()->id();
        $project = Project::create($data);

        // create default 12 steps
        $this->createDefaultSteps($project->id);

        return redirect()->route('admin.projects.show', $project->id)->with('success','Project created');
    }

    protected function createDefaultSteps($projectId)
    {
        $default = [
            ['key'=>'design','name'=>'Design Consultancy'],
            ['key'=>'environment','name'=>'Environment Consultancy'],
            ['key'=>'construction','name'=>'Construction Management'],
            ['key'=>'safety','name'=>'Safety Consultancy'],
            ['key'=>'testing','name'=>'Testing & Commissioning'],
            ['key'=>'finance','name'=>'Finance Approval'],
            ['key'=>'procurement','name'=>'Procurement'],
            ['key'=>'quality','name'=>'Quality Control'],
            ['key'=>'inspection','name'=>'Inspection'],
            ['key'=>'final','name'=>'Final Approval'],
            ['key'=>'documentation','name'=>'Documentation'],
            ['key'=>'completion','name'=>'Completion']
        ];

        foreach ($default as $s) {
            ProjectStep::create([
                'project_id'=>$projectId,
                'step_key'=>$s['key'],
                'step_name'=>$s['name'],
                'status'=>'pending',
                'progress_percent'=>0
            ]);
        }
    }

    public function show($id)
    {
        $project = Project::with(['steps.consultant', 'steps.responsiblePerson', 'manager'])->findOrFail($id);
        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'consultant']);
        })->get();
        //dd($project);
        return view('admin.projects.show', compact('project', 'employees'));
    }


        public function edit(Project $project)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $projectManagers = User::whereHas('roles', function($q) {
            $q->where('name', 'project-manager');
        })->get();

        $employees = User::whereHas('roles', function($q) {
            $q->whereIn('name', ['employee', 'consultant']);
        })->get();

        return view('admin.projects.edit', compact('project', 'projectManagers', 'employees'));
    }

    /**
     * Update the specified project
     */
    public function update(Request $request, Project $project)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_code' => 'required|string|unique:projects,project_code,' . $project->id, // Ignore current project's project_code
            'location' => 'required|string',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'manager_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $projectData = $request->except(['documents']);

           

            $project->update($projectData);

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project updated successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update project: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified project
     */
    public function destroy(Project $project)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        try {
            // Delete project documents
            if ($project->documents) {
                foreach ($project->documents as $document) {
                    Storage::delete('public/project-documents/' . $document);
                }
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete project: ' . $e->getMessage()
            ], 500);
        }
    }
    public function getLocations($projectId)
    {
    try {
        $project = Project::with('locations')->findOrFail($projectId);

        $locations = $project->locations->map(function ($loc) {
            return [
                'id' => $loc->id,
                'name' => $loc->location_name,
            ];
        });

        return response()->json([
            'success' => true,
            'locations' => $locations
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Unable to fetch locations',
        ], 500);
    }
   }

}
