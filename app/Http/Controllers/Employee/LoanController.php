<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LoanController extends Controller
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

        // Fetch all loans for the logged-in employee
        $loans = Loan::with(['user', 'assignedManager', 'approver'])
             ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
        // Summary counts for dashboard widgets
        $totalLoans = $loans->count();
        $totalPendingLoans = $loans->where('status', Loan::STATUS_PENDING)->count();
        $totalApprovedLoans = $loans->where('status', Loan::STATUS_APPROVED)->count();
        $totalRejectedLoans = $loans->where('status', Loan::STATUS_REJECTED)->count();
        $totalCancelledLoans = $loans->where('status', Loan::STATUS_CANCELLED)->count();

        return view('employee.loans.index', compact(
            'loans',
            'totalLoans',
            'totalPendingLoans',
            'totalApprovedLoans',
            'totalRejectedLoans',
            'totalCancelledLoans'
        ));
    }

    /**
     * Show the form for creating new leave request
     */
    public function create()
    {
        return view('employee.loans.create');
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,id',
        'purpose' => 'required|string|in:medical_emergency,personal_expenses,other',
        'amount' => 'required|numeric|min:1',
        'reason' => 'required|string|max:500',
        'repayment_duration' => 'required|string|in:6_months,9_months,12_months,24_months',
        'supporting_document' => 'nullable|array',
        'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
    ], [
        'purpose.required' => 'Please select a loan purpose.',
        'amount.required' => 'Please enter the loan amount.',
        'repayment_duration.required' => 'Please select the repayment duration.',
        'reason.required' => 'Please enter a reason for your loan request.',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    try {
        $data = $validator->validated();

        // ✅ Use logged-in user if not passed
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        $data['created_by'] = auth()->id();
        $data['status'] = Loan::STATUS_PENDING;

        // ✅ Handle file upload (optional)
        if ($request->hasFile('supporting_document')) {
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('loans', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
        }



        // ✅ Create loan record
        Loan::create($data);

        return redirect()
            ->route('employee.loans.index')
            ->with('success', 'Loan request submitted successfully.');

    } catch (\Exception $e) {
        return back()->with('error', 'Failed to submit loan: ' . $e->getMessage())->withInput();
    }
    }

    /**
     * Display the specified leave request
     */
    public function show($id)
    {
        // Sample data - implement actual logic later
        $loan = Loan::with(['user', 'assignedManager', 'approver'])->findOrFail($id);
        return view('employee.loans.show', compact('loan'));
    }

    /**
     * Show the form for editing leave request
     */
    public function edit($id)
    {
        // Sample data - implement actual logic later
        $loan = Loan::with(['user', 'assignedManager', 'approver'])->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        return view('employee.loans.edit', compact('loan'));
    }

    /**
     * Update the specified leave request
     */
    public function update(Request $request, $id)
    {
         $validator = Validator::make($request->all(), [
        'user_id' => 'nullable|exists:users,id',
        'purpose' => 'required|string|in:medical_emergency,personal_expenses,other',
        'amount' => 'required|numeric|min:1',
        'reason' => 'required|string|max:500',
        'repayment_duration' => 'required|string|in:6_months,9_months,12_months,24_months',
        'supporting_document' => 'nullable|array',
        'supporting_document.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
    ], [
        'purpose.required' => 'Please select a loan purpose.',
        'amount.required' => 'Please enter the loan amount.',
        'repayment_duration.required' => 'Please select the repayment duration.',
        'reason.required' => 'Please enter a reason for your loan request.',
    ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $loan = Loan::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
            // 🔹 Prepare data for update
        $data = [
            'user_id'            => $request->user_id ?? $loan->user_id,
            'purpose'            => $request->purpose,
            'amount'             => $request->amount,
            'repayment_duration' => $request->repayment_duration,
            'reason'             => $request->reason,
            'updated_by'         => auth()->id(),
        ];

                // ✅ Handle file upload (optional)
        if ($request->hasFile('supporting_document')) {
        $uploadedFiles = [];

        foreach ($request->file('supporting_document') as $file) {
            $uploadedFiles[] = $file->store('loans', 'public');
        }

        // Store multiple file paths as JSON (or comma-separated if you prefer)
        $data['supporting_document'] = json_encode($uploadedFiles);
        }
        $loan->update($data);

        // TODO: Implement actual leave update logic
        return redirect()->route('employee.loans.index')
            ->with('success', 'Loan request updated successfully');


    }

    /**
     * Remove the specified leave request
     */
    public function destroy($id)
    {
    try {
        // Ensure the logged-in user is an employee
        if (!auth()->user()->hasRole(['employee'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // Find loan belonging to this user
        $loan = Loan::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$loan) {
            return response()->json(['success' => false, 'message' => 'Loan not found'], 404);
        }

        // Prevent deleting approved or rejected loans (optional but recommended)
        if (in_array($loan->status, [Loan::STATUS_APPROVED, Loan::STATUS_REJECTED])) {
            return response()->json(['success' => false, 'message' => 'You cannot delete approved or rejected loans.'], 400);
        }

        // Soft delete
        $loan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Loan request deleted successfully.'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error deleting loan: ' . $e->getMessage()
        ], 500);
    }
  }


    
}
