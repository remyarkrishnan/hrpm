<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectApprovalStep;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $query = Project::with(['projectManager', 'creator']);

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
        if ($request->filled('priority')) {
            $query->where('priority', $request->get('priority'));
        }

        // Filter by project manager
        if ($request->filled('manager')) {
            $query->where('manager', $request->get('manager'));
        }

        $projects = $query->latest()->paginate(15);
        $projectManagers = User::whereHas('roles', function($q) {
            $q->where('name', 'project-manager');
        })->get();

        return view('admin.projects.index', compact('projects', 'projectManagers'));
    }

    /**
     * Show the form for creating a new project
     */
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

    /**
     * Store a newly created project
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'project_code' => 'required|string|unique:projects,project_code',
            'type' => 'required|in:' . implode(',', array_keys(Project::getTypes())),
            'location' => 'required|string',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'expected_end_date' => 'required|date|after:start_date',
            'project_manager_id' => 'required|exists:users,id',
            'priority' => 'required|in:' . implode(',', array_keys(Project::getPriorities())),
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $projectData = $request->except(['documents']);
            $projectData['created_by'] = auth()->id();
            $projectData['status'] = Project::STATUS_DRAFT;
            $projectData['progress_percentage'] = 0;

            // Handle document uploads
            if ($request->hasFile('documents')) {
                $documentPaths = [];
                foreach ($request->file('documents') as $document) {
                    $documentName = time() . '_' . $projectData['project_code'] . '_' . $document->getClientOriginalName();
                    $document->storeAs('public/project-documents', $documentName);
                    $documentPaths[] = $documentName;
                }
                $projectData['documents'] = $documentPaths;
            }

            $project = Project::create($projectData);

            // Create default 12-step approval workflow
            foreach (ProjectApprovalStep::getDefaultSteps() as $stepData) {
                $stepData['project_id'] = $project->id;
                $stepData['status'] = ProjectApprovalStep::STATUS_PENDING;
                $stepData['due_date'] = Carbon::parse($project->start_date)->addDays($stepData['step_order'] * 7); // 1 week per step
                ProjectApprovalStep::create($stepData);
            }

            return redirect()->route('admin.projects.index')
                ->with('success', 'Project created successfully with 12-step approval workflow');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create project: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $project->load(['projectManager', 'creator', 'approvalSteps.responsiblePerson', 'allocatedEmployees', 'milestones']);

        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified project
     */
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
            'project_code' => 'required|string|unique:projects,project_code,' . $project->id,
            'type' => 'required|in:' . implode(',', array_keys(Project::getTypes())),
            'location' => 'required|string',
            'client_name' => 'required|string|max:255',
            'client_contact' => 'nullable|string|max:255',
            'budget' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'expected_end_date' => 'required|date|after:start_date',
            'project_manager_id' => 'required|exists:users,id',
            'priority' => 'required|in:' . implode(',', array_keys(Project::getPriorities())),
            'status' => 'required|in:' . implode(',', array_keys(Project::getStatuses())),
            'progress_percentage' => 'required|numeric|between:0,100',
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $projectData = $request->except(['documents']);

            // Handle new document uploads
            if ($request->hasFile('documents')) {
                $existingDocs = $project->documents ?? [];
                $newDocumentPaths = [];

                foreach ($request->file('documents') as $document) {
                    $documentName = time() . '_' . $projectData['project_code'] . '_' . $document->getClientOriginalName();
                    $document->storeAs('public/project-documents', $documentName);
                    $newDocumentPaths[] = $documentName;
                }
                $projectData['documents'] = array_merge($existingDocs, $newDocumentPaths);
            }

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

    /**
     * Update project status
     */
    public function updateStatus(Request $request, Project $project)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:' . implode(',', array_keys(Project::getStatuses())),
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $project->update(['status' => $request->status]);

            return response()->json([
                'success' => true,
                'message' => 'Project status updated successfully',
                'status' => $project->status_label
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}