<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanningController extends Controller
{
    /**
     * Display a listing of project plans
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample project planning data
        $plans = collect([
            (object)[
                'id' => 1,
                'project_name' => 'Residential Complex - Phase 2',
                'project_code' => 'PROJ-RES-2024-001',
                'phase' => 'Foundation Work',
                'start_date' => '2025-10-15',
                'end_date' => '2025-11-30',
                'progress' => 35.5,
                'status' => 'in_progress',
                'responsible_person' => 'Site Engineer Kumar',
                'milestones_completed' => 3,
                'milestones_total' => 8
            ],
            (object)[
                'id' => 2,
                'project_name' => 'Commercial Mall Construction',
                'project_code' => 'PROJ-COM-2024-002',
                'phase' => 'Site Preparation',
                'start_date' => '2025-10-20',
                'end_date' => '2025-12-15',
                'progress' => 15.0,
                'status' => 'planning',
                'responsible_person' => 'Project Manager Singh',
                'milestones_completed' => 1,
                'milestones_total' => 6
            ]
        ]);

        return view('admin.planning.index', compact('plans'));
    }

    /**
     * Show the form for creating new project plan
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample projects for selection
        $projects = collect([
            (object)['id' => 1, 'name' => 'Residential Complex - Phase 2', 'code' => 'PROJ-RES-2024-001'],
            (object)['id' => 2, 'name' => 'Commercial Mall Construction', 'code' => 'PROJ-COM-2024-002']
        ]);

        return view('admin.planning.create', compact('projects'));
    }

    /**
     * Store a newly created project plan
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|integer',
            'phase_name' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'responsible_person' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual plan creation logic
        return redirect()->route('admin.planning.index')
            ->with('success', 'Project plan created successfully');
    }

    /**
     * Display the specified project plan
     */
    public function show($id)
    {
        $plan = (object)[
            'id' => $id,
            'project_name' => 'Residential Complex - Phase 2',
            'project_code' => 'PROJ-RES-2024-001',
            'phase' => 'Foundation Work',
            'start_date' => '2025-10-15',
            'end_date' => '2025-11-30',
            'progress' => 35.5,
            'status' => 'in_progress',
            'responsible_person' => 'Site Engineer Kumar',
            'description' => 'Complete foundation work including excavation, reinforcement, and concrete pouring',
            'milestones' => [
                (object)['name' => 'Site Excavation', 'due_date' => '2025-10-20', 'status' => 'completed'],
                (object)['name' => 'Reinforcement Setup', 'due_date' => '2025-10-25', 'status' => 'completed'],
                (object)['name' => 'Concrete Pouring - Phase 1', 'due_date' => '2025-11-01', 'status' => 'in_progress'],
                (object)['name' => 'Quality Testing', 'due_date' => '2025-11-10', 'status' => 'pending']
            ]
        ];

        return view('admin.planning.show', compact('plan'));
    }

    /**
     * Show the form for editing project plan
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $plan = (object)[
            'id' => $id,
            'project_id' => 1,
            'phase_name' => 'Foundation Work',
            'start_date' => '2025-10-15',
            'end_date' => '2025-11-30',
            'responsible_person' => 'Site Engineer Kumar',
            'description' => 'Complete foundation work including excavation, reinforcement, and concrete pouring',
            'status' => 'in_progress'
        ];

        return view('admin.planning.edit', compact('plan'));
    }

    /**
     * Update the specified project plan
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'phase_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'responsible_person' => 'required|string|max:255',
            'progress' => 'nullable|numeric|min:0|max:100'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual plan update logic
        return redirect()->route('admin.planning.index')
            ->with('success', 'Project plan updated successfully');
    }

    /**
     * Remove the specified project plan
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // TODO: Implement actual plan deletion logic
        return response()->json([
            'success' => true,
            'message' => 'Project plan deleted successfully'
        ]);
    }

    /**
     * Get planning for specific project
     */
    public function projectPlanning($projectId)
    {
        $projectPlans = collect([
            (object)[
                'phase' => 'Site Preparation',
                'start_date' => '2025-10-10',
                'end_date' => '2025-10-20',
                'progress' => 100,
                'status' => 'completed'
            ],
            (object)[
                'phase' => 'Foundation Work',
                'start_date' => '2025-10-15',
                'end_date' => '2025-11-30',
                'progress' => 35.5,
                'status' => 'in_progress'
            ],
            (object)[
                'phase' => 'Structure Construction',
                'start_date' => '2025-12-01',
                'end_date' => '2026-03-15',
                'progress' => 0,
                'status' => 'planned'
            ]
        ]);

        return view('admin.planning.project', compact('projectPlans', 'projectId'));
    }

    /**
     * Add milestone to project plan
     */
    public function addMilestone(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'milestone_name' => 'required|string|max:255',
            'due_date' => 'required|date',
            'description' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // TODO: Implement milestone creation
        return response()->json([
            'success' => true,
            'message' => 'Milestone added successfully'
        ]);
    }
}