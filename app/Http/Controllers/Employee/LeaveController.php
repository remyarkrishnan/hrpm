<?php

namespace App\Http\Controllers\Employee;

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
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        // Sample data - implement actual logic later
        $userId = auth()->id();

    // Fetch all leaves for this user, ordered by most recent first
    $leaves = Leave::where('user_id', $userId)
        ->orderBy('from_date', 'desc')
        ->get();

    $totalPendingLeaves = Leave::where('user_id', $userId)
        ->where('status', 'pending')
        ->count();

    $totalApprovedLeaves = Leave::where('user_id', $userId)
        ->where('status', 'approved')
        ->count();

    $totalRejectedLeaves = Leave::where('user_id', $userId)
        ->where('status', 'rejected')
        ->count();

        return view('employee.leaves.index', compact('leaves','totalPendingLeaves',
        'totalApprovedLeaves',
        'totalRejectedLeaves',));
    }

    /**
     * Show the form for creating new leave request
     */
    public function create()
    {
        return view('employee.leaves.create');
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
        'supporting_document' => 'nullable|array',
        'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
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

        // ✅ Use logged-in user if not manually passed
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        $data['created_by'] = auth()->id();

        // ✅ Auto calculate total days
        $data['total_days'] = \Carbon\Carbon::parse($data['from_date'])
            ->diffInDays(\Carbon\Carbon::parse($data['to_date'])) + 1;

        // ✅ Handle supporting document upload (optional)

        if ($request->hasFile('supporting_document')) {
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('leaves', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
        }


        // ✅ Create record
        Leave::create($data);

        return redirect()
            ->route('employee.leaves.index')
            ->with('success', 'Leave request submitted successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to submit leave: ' . $e->getMessage())->withInput();
    }
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        // Sample data - implement actual logic later
        $leave = Leave::with(['user'])->findOrFail($id);

        return view('employee.leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing leave request
     */
    public function edit($id)
    {
        // Sample data - implement actual logic later
        $leave = Leave::with(['user'])->findOrFail($id);

        return view('employee.leaves.edit', compact('leave'));
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
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('leaves', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
    }

    // 🔹 Update the leave record
    $leave->update($data);

        // TODO: Implement actual leave update logic
        return redirect()->route('employee.leaves.index')
            ->with('success', 'Leave request updated successfully');
    }

    /**
     * Remove the specified leave request
     */
    public function destroy($id)
    {
    if (!auth()->user()->hasRole(['employee'])) {
        return response()->json(['success' => false, 'message' => 'Access denied'], 403);
    }

    try {
        $leave = Leave::where('id', $id)
            ->where('user_id', auth()->id()) // ensure employee owns it
            ->first();

        if (!$leave) {
            return response()->json(['success' => false, 'message' => 'Leave request not found'], 404);
        }

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



}