<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DocumentRequest;

class DocumentController extends Controller
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
        $totalPendingDocument = DocumentRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $totalApprovedDocument = DocumentRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();
        $totalRejectedDocument = DocumentRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->count();
        // Sample data - implement actual logic later
        $documents = DocumentRequest::with('user')->latest()->get();

        return view('employee.documents.index', compact('documents','totalPendingDocument',
            'totalApprovedDocument',
            'totalRejectedDocument'));
    }

    /**
     * Show the form for creating new leave request
     */
    public function create()
    {
        return view('employee.documents.create');
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:255',
            'supporting_document' => 'nullable|file|max:2048',
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
        DocumentRequest::create($data);

        return redirect()
            ->route('employee.documents.index')
            ->with('success', 'Document request submitted successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to submit document request: ' . $e->getMessage())->withInput();
    }

    
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        // Sample data - implement actual logic later
        $document = DocumentRequest::with(['user'])->findOrFail($id);

        return view('employee.documents.show', compact('document'));
    }

    /**
     * Show the form for editing leave request
     */
    public function edit($id)
    {
        // Sample data - implement actual logic later
        $document = DocumentRequest::with(['user'])->findOrFail($id);

        return view('employee.documents.edit', compact('document'));
    }

    /**
     * Update the specified leave request
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'type' => 'required|string|max:255',
            'supporting_document' => 'nullable|file|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $document = DocumentRequest::findOrFail($id);

        $data = [
            'user_id'            => $request->user_id ?? $document->user_id,
            'type'               => $request->type,
            'status'             => $request->status ?? $document->status,
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
        $document->update($data);

        return redirect()->route('employee.documents.index')
            ->with('success', 'Document request updated successfully');
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
        $document = DocumentRequest::where('id', $id)
            ->where('user_id', auth()->id()) // ensure employee owns it
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Document request not found'], 404);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document request deleted successfully'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete document request: ' . $e->getMessage()
        ], 500);
    }

   
    }

 
}
