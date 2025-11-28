<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    /**
     * Display a listing of resource allocations
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample resource allocation data
        $resources = collect([
            (object)[
                'id' => 1,
                'employee_name' => 'Rajesh Kumar',
                'employee_code' => 'EMP-001',
                'department' => 'Construction',
                'project_name' => 'Residential Complex - Phase 2',
                'role' => 'Site Engineer',
                'allocation_percentage' => 100,
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
                'status' => 'active'
            ],
            (object)[
                'id' => 2,
                'employee_name' => 'Priya Singh',
                'employee_code' => 'EMP-002',
                'department' => 'Engineering',
                'project_name' => 'Commercial Mall Construction',
                'role' => 'Structural Engineer',
                'allocation_percentage' => 75,
                'start_date' => '2025-10-15',
                'end_date' => '2026-01-15',
                'status' => 'active'
            ],
            (object)[
                'id' => 3,
                'employee_name' => 'Amit Sharma',
                'employee_code' => 'EMP-003',
                'department' => 'Safety',
                'project_name' => 'Highway Bridge Project',
                'role' => 'Safety Officer',
                'allocation_percentage' => 50,
                'start_date' => '2025-11-01',
                'end_date' => '2026-02-28',
                'status' => 'planned'
            ]
        ]);

        return view('admin.resources.index', compact('resources'));
    }

    /**
     * Show the form for creating new resource allocation
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample data for dropdowns
        $employees = collect([
            (object)['id' => 1, 'name' => 'Rajesh Kumar', 'code' => 'EMP-001', 'department' => 'Construction'],
            (object)['id' => 2, 'name' => 'Priya Singh', 'code' => 'EMP-002', 'department' => 'Engineering'],
            (object)['id' => 3, 'name' => 'Amit Sharma', 'code' => 'EMP-003', 'department' => 'Safety']
        ]);

        $projects = collect([
            (object)['id' => 1, 'name' => 'Residential Complex - Phase 2', 'code' => 'PROJ-RES-2024-001'],
            (object)['id' => 2, 'name' => 'Commercial Mall Construction', 'code' => 'PROJ-COM-2024-002'],
            (object)['id' => 3, 'name' => 'Highway Bridge Project', 'code' => 'PROJ-INF-2024-003']
        ]);

        return view('admin.resources.create', compact('employees', 'projects'));
    }

    /**
     * Store a newly created resource allocation
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'project_id' => 'required|integer',
            'role' => 'required|string|max:255',
            'allocation_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual resource allocation logic
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource allocated successfully');
    }

    /**
     * Display the specified resource allocation
     */
    public function show($id)
    {
        $resource = (object)[
            'id' => $id,
            'employee_name' => 'Rajesh Kumar',
            'employee_code' => 'EMP-001',
            'department' => 'Construction',
            'designation' => 'Site Engineer',
            'project_name' => 'Residential Complex - Phase 2',
            'project_code' => 'PROJ-RES-2024-001',
            'role' => 'Site Engineer',
            'allocation_percentage' => 100,
            'start_date' => '2025-10-01',
            'end_date' => '2025-12-31',
            'status' => 'active',
            'allocated_by' => 'Project Manager Singh',
            'allocation_date' => '2025-09-25',
            'skills' => ['Concrete Work', 'Site Supervision', 'Quality Control'],
            'performance_rating' => 4.5,
            'current_tasks' => [
                'Foundation excavation supervision',
                'Quality testing coordination',
                'Safety compliance monitoring'
            ]
        ];

        return view('admin.resources.show', compact('resource'));
    }

    /**
     * Show the form for editing resource allocation
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $resource = (object)[
            'id' => $id,
            'employee_id' => 1,
            'employee_name' => 'Rajesh Kumar',
            'project_id' => 1,
            'project_name' => 'Residential Complex - Phase 2',
            'role' => 'Site Engineer',
            'allocation_percentage' => 100,
            'start_date' => '2025-10-01',
            'end_date' => '2025-12-31',
            'status' => 'active'
        ];

        return view('admin.resources.edit', compact('resource'));
    }

    /**
     * Update the specified resource allocation
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:255',
            'allocation_percentage' => 'required|numeric|min:1|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual resource update logic
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource allocation updated successfully');
    }

    /**
     * Remove the specified resource allocation
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // TODO: Implement actual resource deallocation logic
        return response()->json([
            'success' => true,
            'message' => 'Resource allocation removed successfully'
        ]);
    }

    /**
     * Get resource allocations for specific project
     */
    public function projectResources($projectId)
    {
        $projectResources = collect([
            (object)[
                'employee_name' => 'Rajesh Kumar',
                'role' => 'Site Engineer',
                'allocation_percentage' => 100,
                'skills' => ['Concrete Work', 'Site Supervision']
            ],
            (object)[
                'employee_name' => 'Priya Singh',
                'role' => 'Structural Engineer',
                'allocation_percentage' => 75,
                'skills' => ['Structural Design', 'CAD Software']
            ]
        ]);

        return view('admin.resources.project', compact('projectResources', 'projectId'));
    }

    /**
     * Assign employee to project
     */
    public function assignToProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|integer',
            'project_id' => 'required|integer',
            'role' => 'required|string|max:255',
            'allocation_percentage' => 'required|numeric|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // TODO: Implement actual assignment logic
        return response()->json([
            'success' => true,
            'message' => 'Employee assigned to project successfully'
        ]);
    }

    /**
     * Unassign employee from project
     */
    public function unassignFromProject($projectId, $userId)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // TODO: Implement actual unassignment logic
        return response()->json([
            'success' => true,
            'message' => 'Employee unassigned from project successfully'
        ]);
    }
}