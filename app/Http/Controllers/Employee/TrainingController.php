<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Training;
use App\Models\User;

class TrainingController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

         $userId = auth()->id();

        // Fetch all Training for this user, ordered by most recent first
        $trainings = Training::where('user_id', $userId)
            ->orderBy('id', 'desc')
            ->get();

        $totalPendingTraining = Training::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $totalApprovedTraining = Training::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();

        $totalRejectedTraining = Training::where('user_id', $userId)
            ->where('status', 'rejected')
            ->count();

            return view('employee.trainings.index', compact('trainings','totalPendingTraining',
            'totalApprovedTraining',
            'totalRejectedTraining'));

    }

    /**
     * Show the form for creating new leave request
     */
    public function create()
    {
        return view('employee.trainings.create');
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'location' => 'required|string|max:500',
            'name' => 'required|string|max:500',
            'duration' => 'required|string|max:500',
            'benefit' => 'required|string|max:500',
            'supporting_document' => 'nullable|array',
            'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
        $data = $validator->validated();

        $data['user_id'] = $data['user_id'] ?? auth()->id();
        $data['created_by'] = auth()->id();



        if ($request->hasFile('supporting_document')) {
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('trainings', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
        }


        // ✅ Create record
        Training::create($data);

        return redirect()
            ->route('employee.trainings.index')
            ->with('success', 'Training request submitted successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to submit training: ' . $e->getMessage())->withInput();
    }

     
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        // Sample data - implement actual logic later
        $training = Training::with(['user'])->findOrFail($id);
        return view('employee.trainings.show', compact('training'));
    }

    /**
     * Show the form for editing leave request
     */
    public function edit($id)
    {
        // Sample data - implement actual logic later
        $training = Training::with(['user'])->findOrFail($id);

        return view('employee.trainings.edit', compact('training'));
    }

    /**
     * Update the specified leave request
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'location' => 'required|string|max:500',
            'name' => 'required|string|max:500',
            'duration' => 'required|string|max:500',
            'benefit' => 'required|string|max:500',
            'supporting_document' => 'nullable|array',
            'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $training = Training::findOrFail($id);

        $data = [
        'user_id'            => $request->user_id ?? $training->user_id,
        'name'               => $request->name,
        'location'           => $request->location,
        'duration'           => $request->duration,
        'benefit'            => $request->benefit,
        'status'             => $request->status ?? $training->status,
        'updated_by'         => auth()->id(),
    ];

    // 🔹 Handle supporting document upload if new file provided

    if ($request->hasFile('supporting_document')) {
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('trainings', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
    }

    // 🔹 Update the leave record
    $training->update($data);

        // TODO: Implement actual leave update logic
        return redirect()->route('employee.trainings.index')
            ->with('success', 'Training request updated successfully');
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
        $leave = Training::where('id', $id)
            ->where('user_id', auth()->id()) // ensure employee owns it
            ->first();

        if (!$leave) {
            return response()->json(['success' => false, 'message' => 'Training request not found'], 404);
        }

        $leave->delete();

        return response()->json([
            'success' => true,
            'message' => 'Training request deleted successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete training request: ' . $e->getMessage()
        ], 500);
    }

     
    }

    /**
     * Approve leave request
     */
    public function approve($id)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // TODO: Implement actual leave approval logic
        return response()->json([
            'success' => true,
            'message' => 'Training request approved successfully'
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

        // TODO: Implement actual leave rejection logic
        return response()->json([
            'success' => true,
            'message' => 'Training request rejected'
        ]);
    }
}
