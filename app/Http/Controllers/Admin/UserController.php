<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\Attachment;
use App\Models\Document;
use App\Models\Allowance;


class UserController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        // Check if user has admin role
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $query = User::with('roles');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('designation', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->whereHas('roles', function($q) use ($request) {
                $q->where('name', $request->get('role'));
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status') === 'active');
        }

        $employees = $query->latest()->paginate(15);
        $departments = User::distinct('department')->whereNotNull('department')->pluck('department');
        $roles = Role::all();

        return view('admin.users.index', compact('employees', 'departments', 'roles'));
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $roles = Role::all();
        $departments = [
            'Administration', 'Engineering', 'Project Management', 
            'Construction', 'Design', 'Finance', 'Human Resources',
            'Quality Control', 'Safety', 'Procurement'
        ];

        $managers = User::with('roles')->whereHas('roles', function($q) {
            $q->whereIn('name', ['project-manager']);
        })->get();
       

        return view('admin.users.create', compact('roles', 'departments' , 'managers'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id',
            'department' => 'required|string',
            'designation' => 'required|string',
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'role' => 'required|exists:roles,name',
              // Nullable validation rules
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // profile image is nullable
            'documents' => 'nullable|array',  // documents is nullable, it may or may not be an array
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg', // document file validation
            'attachments' => 'nullable|array',  // attachments is nullable, may or may not be an array
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,doc,docx,pdf', // attachment file validation
            'bank_proof_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'allowance_names' => 'nullable|array',
            'allowance_names.*' => 'string',
            'allowance_percentage' => 'nullable|array',
            'allowance_percentage.*' => 'numeric',
            'allowance_active' => 'nullable|array',
            'allowance_active.*' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $userData = $request->except(['password_confirmation', 'role']);
            $userData['password'] = Hash::make($request->password);
            $userData['status'] = $request->boolean('status', true);
            $userData['basic_salary'] = $request['basic_salary'];
            $userData['employee_type'] = $request['employee_type'];
            $userData['bank_name'] = $request['bank_name'];
            $userData['iban_no'] = $request['iban_no'];
            $userData['reporting_manager'] = $request['reporting_manager'];
            $userData['email_verified_at'] = now();

            if ($request->hasFile('photo')) {
            $profileImage = $request->file('photo');
            $profileImagePath = $profileImage->store('photo', 'public');
            $userData['photo'] = $profileImagePath;
            }

            // Handle bank_proof_document upload (if exists)
            if ($request->hasFile('bank_proof_document')) {
            $bankProofDocument = $request->file('bank_proof_document');
            $bankProofDocumentPath = $bankProofDocument->store('bank_proof_documents', 'public');
            $userData['bank_proof_document'] = $bankProofDocumentPath;
            }

            $user = User::create($userData);

            // Assign role
            if ($request->filled('role')) {
                $user->assignRole($request->role);
            }

           
            // Handle allowances
            if ($request->has('allowance_names')) {
                $allowances = [];

                foreach ($request->allowance_names as $index => $allowanceName) {
                    $percentage = $request->allowance_percentage[$index] ?? 0;
                    $active = $request->has('allowance_active') && in_array($index, array_keys($request->allowance_active)) ? 1 : 0;
                    
                    // Add predefined allowances like Housing and Transportation
                    if ($allowanceName == 'housing_allowance') {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * 0.25,
                            'percentage' => 25,
                            'active' => 1
                        ];
                    } elseif ($allowanceName == 'transportation_allowance') {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * 0.10,
                            'percentage' => 10,
                            'active' => 1
                        ];
                    } else {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * ($percentage / 100),
                            'percentage' => $percentage,
                            'active' => $active
                        ];
                    }
                }

                foreach ($allowances as $allowance) {
                    $user->allowances()->create([
                        'allowance_name' => $allowance['allowance_name'],
                        'amount' => $allowance['amount'],
                        'percentage' => $allowance['percentage'],
                        'active' => $allowance['active'],
                    ]);
                }
            }


                    // Save Attachments if any
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    // Ensure the file is uploaded correctly before proceeding
                    if ($attachment->isValid()) {
                        $filePath = $attachment->store('attachments', 'public');
                        $user->attachments()->create([
                            'file_path' => $filePath,
                            'file_name' => $attachment->getClientOriginalName(),
                        ]);
                    }
                }
            }

                    // Save Documents if any
            if ($request->hasFile('documents') && count($request->documents) > 0) {
                foreach ($request->documents as $index => $document) {
                    // Check if document is a valid file
                    if ($document->isValid()) {
                        // Store the document file
                        $filePath = $document->store('documents', 'public');

                        // Store the document details in the database
                        $user->documents()->create([
                            'document_name' => $request->document_names[$index], // Get the name from the array
                            'file_path' => $filePath,
                        ]);
                    }
                }
            }


            return redirect()->route('admin.users.index')
                ->with('success', 'Employee created successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create employee: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified employee
     */
    public function show(User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $user->load('roles');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $roles = Role::all();
        $departments = [
            'Administration', 'Engineering', 'Project Management', 
            'Construction', 'Design', 'Finance', 'Human Resources',
            'Quality Control', 'Safety', 'Procurement'
        ];
        $managers = User::with('roles')->whereHas('roles', function($q) {
            $q->whereIn('name', ['project-manager']);
        })->get();

        return view('admin.users.edit', compact('user', 'roles', 'departments', 'managers'));
    }

    /**
     * Update the specified employee
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'employee_id' => 'required|string|unique:users,employee_id,' . $user->id,
            'department' => 'required|string',
            'designation' => 'required|string',
            'phone' => 'required|string|max:20',
            'joining_date' => 'required|date',
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // profile image is nullable
            'documents' => 'nullable|array',  // documents is nullable, it may or may not be an array
            'documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpeg,png,jpg', // document file validation
            'attachments' => 'nullable|array',  // attachments is nullable, may or may not be an array
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,doc,docx,pdf', // attachment file validation
            'bank_proof_document' => 'nullable|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'allowance_names' => 'nullable|array',
            'allowance_names.*' => 'string',
            'allowance_percentage' => 'nullable|array',
            'allowance_percentage.*' => 'numeric',
            'allowance_active' => 'nullable|array',
            'allowance_active.*' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $userData = $request->except(['password_confirmation', 'role', 'password']);

            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $userData['status'] = $request->boolean('status', true);
            $userData['basic_salary'] = $request['basic_salary'];
            $userData['employee_type'] = $request['employee_type'];
            $userData['bank_name'] = $request['bank_name'];
            $userData['iban_no'] = $request['iban_no'];
            $userData['reporting_manager'] = $request['reporting_manager'];

            if ($request->hasFile('photo')) {
            $profileImage = $request->file('photo');
            $profileImagePath = $profileImage->store('photo', 'public');
            $userData['photo'] = $profileImagePath;
            }

            // Handle bank_proof_document upload (if exists)
            if ($request->hasFile('bank_proof_document')) {
            $bankProofDocument = $request->file('bank_proof_document');
            $bankProofDocumentPath = $bankProofDocument->store('bank_proof_documents', 'public');
            $userData['bank_proof_document'] = $bankProofDocumentPath;
            }

            $user->update($userData);

            // Update role
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
            }


            // Handle allowances
            if ($request->has('allowance_names')) {
                $allowances = [];

                foreach ($request->allowance_names as $index => $allowanceName) {
                    $percentage = $request->allowance_percentage[$index] ?? 0;
                    $active = $request->has('allowance_active') && in_array($index, array_keys($request->allowance_active)) ? 1 : 0;
                    
                    // Add predefined allowances like Housing and Transportation
                    if ($allowanceName == 'housing_allowance') {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * 0.25,
                            'percentage' => 25,
                            'active' => 1
                        ];
                    } elseif ($allowanceName == 'transportation_allowance') {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * 0.10,
                            'percentage' => 10,
                            'active' => 1
                        ];
                    } else {
                        $allowances[] = [
                            'allowance_name' => $allowanceName,
                            'amount' => $userData['basic_salary'] * ($percentage / 100),
                            'percentage' => $percentage,
                            'active' => $active
                        ];
                    }
                }

                foreach ($allowances as $allowance) {
                    $user->allowances()->create([
                        'allowance_name' => $allowance['allowance_name'],
                        'amount' => $allowance['amount'],
                        'percentage' => $allowance['percentage'],
                        'active' => $allowance['active'],
                    ]);
                }
            }


                    // Save Attachments if any
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $attachment) {
                    // Ensure the file is uploaded correctly before proceeding
                    if ($attachment->isValid()) {
                        $filePath = $attachment->store('attachments', 'public');
                        $user->attachments()->create([
                            'file_path' => $filePath,
                            'file_name' => $attachment->getClientOriginalName(),
                        ]);
                    }
                }
            }

                    // Save Documents if any
            if ($request->hasFile('documents') && count($request->documents) > 0) {
                foreach ($request->documents as $index => $document) {
                    // Check if document is a valid file
                    if ($document->isValid()) {
                        // Store the document file
                        $filePath = $document->store('documents', 'public');

                        // Store the document details in the database
                        $user->documents()->create([
                            'document_name' => $request->document_names[$index], // Get the name from the array
                            'file_path' => $filePath,
                        ]);
                    }
                }
            }

            return redirect()->route('admin.users.index')
                ->with('success', 'Employee updated successfully');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update employee: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Remove the specified employee
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        try {
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete employee: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle employee status
     */
    public function toggleStatus(User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        try {
            $user->update(['status' => !$user->status]);

            return response()->json([
                'success' => true,
                'message' => 'Employee status updated successfully',
                'status' => $user->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified employee
     */
    public function salary(User $user)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $user->load('roles');
        return view('admin.users.salary', compact('user'));
    }
}