<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;
use App\Models\Loan;
use App\Models\Training;
use App\Models\DocumentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class ApprovalController extends Controller
{
    /**
     * Display a listing of project approvals
     */
    public function index()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Fetch all leave requests
        $leaves = Leave::with('user:id,name','assignedManager:id,name')->get()->map(function ($leave) {
            return (object)[
                'id' => $leave->id,
                'employee' => $leave->user->name ?? 'Unknown',
                'type' => 'Leave',
                'status' => $leave->status,
                'manager' => $leave->assignedManager->name  ?? '',
                'applied_on' => $leave->created_at->format('d-m-Y'),
                'details' => $leave->from_date->format('d-m-Y') . ' → ' . $leave->to_date->format('d-m-Y') . ' (' . round($leave->total_days) . ' days)',
            ];
        });

        // Fetch all loan requests
        $loans = Loan::with('user:id,name','assignedManager:id,name')->get()->map(function ($loan) {
            return (object)[
                'id' => $loan->id,
                'employee' => $loan->user->name ?? 'Unknown',
                'type' => 'Loan',
                'status' => $loan->status,
                'manager' => $loan->assignedManager->name ?? '',
                'applied_on' => $loan->created_at->format('d-m-Y'),
                'details' => '₹' . number_format($loan->amount, 2) . ' / ' . str_replace('_',' ',$loan->repayment_duration),
            ];
        });

        $trainings = Training::with('user:id,name','assignedManager:id,name')->get()->map(function ($training) {
            return (object)[
                'id' => $training->id,
                'employee' => $training->user->name ?? 'Unknown',
                'type' => 'Training Request',
                'status' => $training->status,
                'manager' => $training->assignedManager->name ?? '',
                'applied_on' => $training->created_at->format('d-m-Y'),
                'details' => $training->name,
            ];
        });

        $documentrequests = DocumentRequest::with('user:id,name','assignedManager:id,name')->get()->map(function ($document) {
            return (object)[
                'id' => $document->id,
                'employee' => $document->user->name ?? 'Unknown',
                'type' => 'Document Request',
                'status' => $document->status,
                'manager' => $document->assignedManager->name ?? '',
                'applied_on' => $document->created_at->format('d-m-Y'),
                'details' =>  ucfirst(str_replace('_',' ',$document->type)),
            ];
        });

        // Merge all approvals
        $approvals = $leaves->merge($loans)->merge($trainings)->merge($documentrequests)->sortByDesc('applied_on');
        $projectManagers = User::whereHas('roles', function($q) {
                    $q->where('name', 'project-manager');
                })->get();

        return view('admin.approvals.index', compact('approvals','projectManagers'));
    }

    // Approve request
    public function approve(Request $request, $id)
    {
        // Check access
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }
        // Find Leave or Loan by ID
        if($request->type === "Leave"){
        $requestModel = Leave::find($id) ;
        }
        elseif($request->type === "Loan"){
        $requestModel = Loan::find($id);
        }
        elseif($request->type === "Training Request"){
        $requestModel = Training::find($id);
        }
        elseif($request->type === "Document Request"){
        $requestModel = DocumentRequest::find($id);
        }
        if (!$requestModel) {
            return response()->json(['success' => false, 'message' => 'Request not found'], 404);
        }
        if($request->manager_id !=""){


        $request->validate(['manager_id' => 'required|exists:users,id']);


        $requestModel->manager_assigned = $request->manager_id;
        $requestModel->status = 'transferred_to_manager';
        $requestModel->save();

         return response()->json(['success' => true,'message' => 'Manager assigned successfully!']);
        }
        else{
        $requestModel->status = 'approved_by_account_and_hr';
        if ($request->has('remarks')) {
            $requestModel->accounts_and_hr_remarks = $request->remarks;
        }
        $requestModel->approved_by = auth()->id();
        $requestModel->approved_at = Carbon::now();
        $requestModel->save();

        return response()->json(['success' => true, 'message' => 'Request approved successfully']);
        }
    }

    // Reject request
    public function reject(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        if (!$request->has('rejection_reason') || trim($request->rejection_reason) === '') {
            return response()->json(['success' => false, 'message' => 'Rejection reason is required'], 400);
        }

        if($request->type === "Leave"){
        $requestModel = Leave::find($id) ;
        }
        else{
        $requestModel = Loan::find($id);
        }
        if (!$requestModel) {
            return response()->json(['success' => false, 'message' => 'Request not found'], 404);
        }

        $requestModel->status = 'rejected_by_account_and_hr';
        $requestModel->accounts_and_hr_remarks = $request->rejection_reason;
        $requestModel->approved_by = auth()->id();
        $requestModel->approved_at = Carbon::now();
        $requestModel->save();

        return response()->json(['success' => true, 'message' => 'Request rejected successfully']);
    }

                // Assign manager to a leave
    public function assign(Request $request, $id)
    {
      
        $request->validate(['manager_id' => 'required|exists:users,id']);

        $leave = Leave::findOrFail($id);
        $leave->update([
            'manager_assigned' => $request->manager_id,
            'status' => 'transferred_to_manager'
        ]);

         return response()->json([
        'success' => true,
        'message' => 'Manager assigned successfully!',
        'manager_name' => $leave->manager->name ?? 'N/A',
    ]);
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
    public function show($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }
      
        $type="Leave";
        if (!in_array($type, ['Leave', 'Loan', 'Training Request', 'Document Request'])) {
        return redirect()->back()->with('error', 'Invalid request type');
        }

        // Fetch based on type
        if ($type === 'Leave') {
            $approval = Leave::with(['user', 'assignedManager', 'approver'])->find($id);
            $type = 'Leave';
        } elseif($type === 'Loan') {
            $approval = Loan::with(['user', 'assignedManager', 'approver'])->find($id);
            $type = 'Loan';
        } elseif($type === 'Training Request') {
            $approval = Training::with(['user', 'assignedManager', 'approver'])->find($id);
            $type = 'Training Request';
        } elseif($type === 'Document Request') {
            $approval = DocumentRequest::with(['user', 'assignedManager', 'approver'])->find($id);
            $type = 'Document Request';
        }

        if (!$approval) {
            return redirect()->back()->with('error', ucfirst($type) . ' request not found');
        }

        return view('admin.approvals.show', compact('approval', 'type'));
    }

    // Show Leave details
    public function viewLeave($id)
    { 
        $approval = Leave::with(['user', 'assignedManager', 'approver'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Leave not found');
        }

        $type = 'Leave';
        return view('admin.approvals.show', compact('approval', 'type'));
    }

    // Show Loan details
    public function viewLoan($id)
    { 
        $approval = Loan::with(['user', 'assignedManager', 'approver'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Loan not found');
        }

        $type = 'Loan';
        return view('admin.approvals.show', compact('approval', 'type'));
    }

    // Show Leave details
    public function viewTraining($id)
    {
        $approval = Training::with(['user', 'assignedManager', 'approvedBy'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Training not found');
        }

        $type = 'Training Request';
        return view('admin.approvals.show', compact('approval', 'type'));
    }

    // Show Loan details
    public function viewDocument($id)
    {
        $approval = DocumentRequest::with(['user', 'assignedManager', 'approvedBy'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'DocumentRequest not found');
        }

        $type = 'Document Request';
        return view('admin.approvals.show', compact('approval', 'type'));
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
