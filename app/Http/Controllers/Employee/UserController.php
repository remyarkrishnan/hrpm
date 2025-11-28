<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index(Request $request)
    {
        // Check if user has admin role
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }
        $user = auth()->user(); 
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

        return view('employee.users.index', compact('employees', 'departments', 'roles', 'user'));
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

        return view('admin.users.create', compact('roles', 'departments'));
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
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $userData = $request->except(['password_confirmation', 'role']);
            $userData['password'] = Hash::make($request->password);
            $userData['status'] = $request->boolean('status', true);
            $userData['email_verified_at'] = now();

            $user = User::create($userData);

            // Assign role
            if ($request->filled('role')) {
                $user->assignRole($request->role);
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

        return view('admin.users.edit', compact('user', 'roles', 'departments'));
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

            $user->update($userData);

            // Update role
            if ($request->filled('role')) {
                $user->syncRoles([$request->role]);
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