<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Leave;
use App\Models\Loan;
use App\Models\User;
use App\Models\Training;
use App\Models\DocumentRequest;

class ApprovalController extends Controller
{
    /**
     * Display a listing of project approvals
     */
    public function index()
    {
        if (!auth()->user()->hasRole(['supervisor'])) {
            return redirect()->route('supervisor.dashboard')->with('error', 'Access denied');
        }
        $userId = auth()->id();
        // Fetch all leave requests
        $leaves = Leave::with('user:id,name')->where('manager_assigned', $userId)->get()->map(function ($leave) {
            return (object)[
                'id' => $leave->id,
                'employee' => $leave->user->name ?? 'Unknown',
                'type' => 'Leave',
                'status' => $leave->status,
                'manager' => $leave->assignedManager->name ?? '',
                'applied_on' => $leave->created_at->format('d-m-Y'),
                'details' => $leave->from_date->format('d-m-Y') . ' → ' . $leave->to_date->format('d-m-Y') . ' (' . round($leave->total_days) . ' days)',
            ];
        });

        // Fetch all loan requests
        $loans = Loan::with('user:id,name')->where('manager_assigned', $userId)->get()->map(function ($loan) {
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

        return view('supervisor.approvals.index', compact('approvals'));
    }

    // Approve request
    public function approve(Request $request, $id)
    {
        // Check access
        if (!auth()->user()->hasRole(['supervisor'])) {
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

        $requestModel->status = 'approved_by_manager';
        if ($request->has('reason')) {
            $requestModel->manager_remarks = $request->reason;
        }
        $requestModel->approved_by_manager = auth()->id();
        $requestModel->save();

        return response()->json(['success' => true, 'message' => 'Request approved successfully']);
    }

    // Reject request
    public function reject(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['supervisor'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        if (!$request->has('rejection_reason') || trim($request->rejection_reason) === '') {
            return response()->json(['success' => false, 'message' => 'Rejection reason is required'], 400);
        }

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

        $requestModel->status = 'rejected_by_manager';
        $requestModel->manager_remarks = $request->rejection_reason;
        $requestModel->approved_by_manager = auth()->id();
        $requestModel->save();

        return response()->json(['success' => true, 'message' => 'Request rejected successfully']);
    }
    /**
     * Show the form for editing approval
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['supervisor'])) {
            return redirect()->route('supervisor.dashboard')->with('error', 'Access denied');
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
        if (!auth()->user()->hasRole(['supervisor'])) {
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
        if (!auth()->user()->hasRole(['supervisor'])) {
            return redirect()->route('supervisor.dashboard')->with('error', 'Access denied');
        }
       $type="Leave";
        if (!in_array($type, ['Leave', 'Loan'])) {
        return redirect()->back()->with('error', 'Invalid request type');
        }

        // Fetch based on type
        if ($type === 'Leave') {
            $approval = Leave::with('user')->find($id);
            $type = 'Leave';
        } else {
            $approval = Loan::with('user')->find($id);
            $type = 'Loan';
        }

        if (!$approval) {
            return redirect()->back()->with('error', ucfirst($type) . ' request not found');
        }

        return view('supervisor.approvals.show', compact('approval', 'type'));
    }

    public function viewLeave($id)
    { 
        $approval = Leave::with(['user', 'assignedManager', 'approver'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Leave not found');
        }

        $type = 'Leave';
        return view('manager.approvals.show', compact('approval', 'type'));
    }

    // Show Loan details
    public function viewLoan($id)
    { 
        $approval = Loan::with(['user', 'assignedManager', 'approver'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Loan not found');
        }

        $type = 'Loan';
        return view('manager.approvals.show', compact('approval', 'type'));
    }

    // Show Leave details
    public function viewTraining($id)
    {
        $approval = Training::with(['user', 'assignedManager', 'approvedBy'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'Training not found');
        }

        $type = 'Training Request';
        return view('manager.approvals.show', compact('approval', 'type'));
    }

    // Show Loan details
    public function viewDocument($id)
    {
        $approval = DocumentRequest::with(['user', 'assignedManager', 'approvedBy'])->find($id);

        if (!$approval) {
            return redirect()->back()->with('error', 'DocumentRequest not found');
        }

        $type = 'Document Request';
        return view('manager.approvals.show', compact('approval', 'type'));
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

        return view('manager.approvals.project', compact('approvals', 'projectId'));
    }
}
