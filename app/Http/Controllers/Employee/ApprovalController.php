<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApprovalController extends Controller
{
    /**
     * Display a listing of project approvals
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample 12-step approval data
        $approvals = collect([
            (object)[
                'id' => 1,
                'project_name' => 'Residential Complex - Phase 2',
                'project_code' => 'PROJ-RES-2024-001',
                'step_name' => 'Design Review',
                'step_order' => 1,
                'consultancy_type' => 'Design Consultancy',
                'status' => 'approved',
                'due_date' => '2025-10-15',
                'approved_at' => '2025-10-08',
                'responsible_person' => 'Architect Sharma'
            ],
            (object)[
                'id' => 2,
                'project_name' => 'Residential Complex - Phase 2',
                'project_code' => 'PROJ-RES-2024-001',
                'step_name' => 'Environmental Assessment',
                'step_order' => 2,
                'consultancy_type' => 'Environment Consultancy',
                'status' => 'in_progress',
                'due_date' => '2025-10-20',
                'approved_at' => null,
                'responsible_person' => 'Environmental Engineer'
            ],
            (object)[
                'id' => 3,
                'project_name' => 'Commercial Mall Construction',
                'project_code' => 'PROJ-COM-2024-002',
                'step_name' => 'Safety Planning',
                'step_order' => 3,
                'consultancy_type' => 'Safety Consultancy',
                'status' => 'pending',
                'due_date' => '2025-10-18',
                'approved_at' => null,
                'responsible_person' => 'Safety Officer'
            ]
        ]);

        return view('admin.approvals.index', compact('approvals'));
    }

    /**
     * Display the specified approval
     */
    public function show($id)
    {
        // Sample detailed approval data
        $approval = (object)[
            'id' => $id,
            'project_name' => 'Residential Complex - Phase 2',
            'project_code' => 'PROJ-RES-2024-001',
            'step_name' => 'Design Review',
            'step_order' => 1,
            'consultancy_type' => 'Design Consultancy',
            'status' => 'approved',
            'due_date' => '2025-10-15',
            'approved_at' => '2025-10-08 10:30:00',
            'responsible_person' => 'Architect Sharma',
            'remarks' => 'Design approved with minor modifications suggested',
            'documents' => ['design_blueprint_v2.pdf', 'structural_analysis.pdf'],
            'project_id' => 1
        ];

        return view('admin.approvals.show', compact('approval'));
    }

    /**
     * Show the form for editing approval
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $approval = (object)[
            'id' => $id,
            'project_name' => 'Residential Complex - Phase 2',
            'step_name' => 'Design Review',
            'status' => 'approved',
            'due_date' => '2025-10-15',
            'responsible_person' => 'Architect Sharma',
            'remarks' => 'Design approved with minor modifications suggested'
        ];

        return view('admin.approvals.edit', compact('approval'));
    }

    /**
     * Update the specified approval
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,in_progress,approved,rejected',
            'due_date' => 'required|date',
            'remarks' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual approval update logic
        return redirect()->route('admin.approvals.index')
            ->with('success', 'Approval step updated successfully');
    }

    /**
     * Approve the specified approval step
     */
    public function approve(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'remarks' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // TODO: Implement actual approval logic
        return response()->json([
            'success' => true,
            'message' => 'Approval step approved successfully'
        ]);
    }

    /**
     * Reject the specified approval step
     */
    public function reject(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // TODO: Implement actual rejection logic
        return response()->json([
            'success' => true,
            'message' => 'Approval step rejected'
        ]);
    }

    /**
     * Get approvals for specific project
     */
    public function projectApprovals($projectId)
    {
        // Sample 12-step workflow for a project
        $approvals = collect([
            (object)['step_order' => 1, 'step_name' => 'Design Review', 'status' => 'approved'],
            (object)['step_order' => 2, 'step_name' => 'Environmental Assessment', 'status' => 'in_progress'],
            (object)['step_order' => 3, 'step_name' => 'Safety Planning', 'status' => 'pending'],
            (object)['step_order' => 4, 'step_name' => 'Structural Analysis', 'status' => 'pending'],
            (object)['step_order' => 5, 'step_name' => 'Electrical Planning', 'status' => 'pending'],
            (object)['step_order' => 6, 'step_name' => 'Plumbing & HVAC', 'status' => 'pending'],
            (object)['step_order' => 7, 'step_name' => 'Financial Approval', 'status' => 'pending'],
            (object)['step_order' => 8, 'step_name' => 'Legal Compliance', 'status' => 'pending'],
            (object)['step_order' => 9, 'step_name' => 'Municipal Permits', 'status' => 'pending'],
            (object)['step_order' => 10, 'step_name' => 'Fire Safety Clearance', 'status' => 'pending'],
            (object)['step_order' => 11, 'step_name' => 'Quality Assurance', 'status' => 'pending'],
            (object)['step_order' => 12, 'step_name' => 'Final Approval', 'status' => 'pending']
        ]);

        return view('admin.approvals.project', compact('approvals', 'projectId'));
    }
}
