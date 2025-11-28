<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LeaveController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }
        // Sample data - implement actual logic later
        $leaves = Leave::with(['user', 'assignedManager', 'approver'])->orderBy('from_date', 'desc')->get();

        $employees = User::whereHas('roles', fn($q) => $q->where('name', 'employee'))->get();

        $totalPendingLeaves  = Leave::where('status', 'pending')->count();
        $totalApprovedLeaves = Leave::where('status', 'approved')->count();
        $totalRejectedLeaves = Leave::where('status', 'rejected')->count();
        $today = now()->toDateString();

        // Count today’s leaves (approved + ongoing)
        $todayLeaves = Leave::where('status', 'approved')
            ->whereDate('from_date', '<=', $today)
            ->whereDate('to_date', '>=', $today)
            ->count();
        $projectManagers = User::whereHas('roles', function($q) {
                    $q->where('name', 'project-manager');
                })->get();
        return view('admin.leaves.index', compact('leaves','employees','totalPendingLeaves','totalApprovedLeaves','totalRejectedLeaves','todayLeaves','projectManagers'));
    }

    /**
     * Show the form for creating new leave request
     */
    public function create()
    {
        $employees = User::whereHas('roles', fn($q) => $q->where('name', 'employee'))->get();
        return view('admin.leaves.create', compact('employees'));
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,id',
        'leave_type' => 'required|in:sick_leave,casual_leave,annual_leave,maternity_leave,emergency_leave',
        'from_date' => 'required|date|after_or_equal:today',
        'to_date' => 'required|date|after_or_equal:from_date',
        'reason' => 'required|string|max:500',
        'supporting_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
        'leave_type.required' => 'Please select a leave type.',
        'from_date.required' => 'Please select the start date of your leave.',
        'to_date.required' => 'Please select the end date of your leave.',
        'reason.required' => 'Please enter a reason for your leave.',
    ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
        $data = $validator->validated();

        $data['created_by'] = auth()->id();

        // ✅ Auto calculate total days
        $data['total_days'] = \Carbon\Carbon::parse($data['from_date'])
            ->diffInDays(\Carbon\Carbon::parse($data['to_date'])) + 1;

        // ✅ Handle supporting document upload (optional)
        if ($request->hasFile('supporting_document')) {
            $data['supporting_document'] = $request->file('supporting_document')->store('leaves', 'public');
        }

        // ✅ Create record
        Leave::create($data);

        return redirect()
            ->route('admin.leaves.index')
            ->with('success', 'Leave request added successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to add leave: ' . $e->getMessage())->withInput();
    }


    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        // Sample data - implement actual logic later
        $leave = Leave::with(['user'])->findOrFail($id);

        return view('admin.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing leave request
     */
    public function edit($id)
    {
        // Sample data - implement actual logic later
        $leave = Leave::with(['user'])->findOrFail($id);
        return view('admin.leaves.edit', compact('leave'));
    }

    /**
     * Update the specified leave request
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,id',
        'leave_type' => 'required|in:sick_leave,casual_leave,annual_leave,maternity_leave,emergency_leave',
        'from_date' => 'required|date|after_or_equal:today',
        'to_date' => 'required|date|after_or_equal:from_date',
        'reason' => 'required|string|max:500',
        'supporting_document' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ], [
        'leave_type.required' => 'Please select a leave type.',
        'from_date.required' => 'Please select the start date of your leave.',
        'to_date.required' => 'Please select the end date of your leave.',
        'reason.required' => 'Please enter a reason for your leave.',
    ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $leave = Leave::findOrFail($id);

        // 🔹 Calculate total days between from_date and to_date
        $from = \Carbon\Carbon::parse($request->from_date);
        $to   = \Carbon\Carbon::parse($request->to_date);
        $totalDays = $from->diffInDays($to) + 1;

        // 🔹 Prepare data for update
        $data = [
            'user_id'            => $request->user_id ?? $leave->user_id,
            'leave_type'         => $request->leave_type,
            'from_date'          => $from,
            'to_date'            => $to,
            'total_days'         => $totalDays,
            'reason'             => $request->reason,
            'status'             => $request->status ?? $leave->status,
            'updated_by'         => auth()->id(),
        ];

        // 🔹 Handle supporting document upload if new file provided
        if ($request->hasFile('supporting_document')) {
            $file = $request->file('supporting_document');
            $path = $file->store('leave_documents', 'public');
            $data['supporting_document'] = $path;
        }

        // 🔹 Update the leave record
        $leave->update($data);

        // TODO: Implement actual leave update logic
        return redirect()->route('admin.leaves.index')
            ->with('success', 'Leave request updated successfully');
    }

    /**
     * Remove the specified leave request
     */
    public function destroy($id)
    {
        
    if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
        return response()->json([
            'success' => false,
            'message' => 'Access denied'
        ], 403);
    }

    try {
        $leave = Leave::find($id);

        if (!$leave) {
            return response()->json([
                'success' => false,
                'message' => 'Leave request not found'
            ], 404);
        }

        // Delete supporting document if exists
        if ($leave->supporting_document && \Storage::disk('public')->exists($leave->supporting_document)) {
            \Storage::disk('public')->delete($leave->supporting_document);
        }

        // Delete the leave record
        $leave->delete();

        return response()->json([
            'success' => true,
            'message' => 'Leave request deleted successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete leave request: ' . $e->getMessage()
        ], 500);
    }
    }


    /**
     * Approve leave request
     */
    public function approve(Request $request,$id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $leave = Leave::findOrFail($id);
        
        $data = [
            'approved_by'        => auth()->id(),
            'approved_at'        => Carbon::now(),
            'status'             => $request->status ?? 'approved_by_account_and_hr',
            'accounts_and_hr_remarks'             => $request->reason ,
            'updated_by'         => auth()->id(),
        ];

        $leave->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Leave request approved successfully'
        ]);
    }

    /**
     * Reject leave request
     */
    public function reject(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $leave = Leave::findOrFail($id);

        $data = [
            'accounts_and_hr_remarks'             => $request->rejection_reason ,
            'status'             => $request->status ?? 'rejected_by_account_and_hr',
            'updated_by'         => auth()->id(),
        ];

        $leave->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Leave request rejected'
        ]);
    }

        // Assign manager to a leave
    public function assignManager(Request $request, $id)
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

    // Manager approval/rejection
    public function managerAction(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved_by_manager,rejected_by_manager',
            'manager_remarks' => 'nullable|string',
        ]);

        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => $validated['status'],
            'approved_by_manager' => Auth::id(),
            'manager_remarks' => $validated['manager_remarks'],
        ]);

        return back()->with('success', 'Leave reviewed by manager.');
    }

}
