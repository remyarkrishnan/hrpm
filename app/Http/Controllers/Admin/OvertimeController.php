<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OvertimeController extends Controller
{
    /**
     * Display a listing of overtime requests
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample data
        $overtimes = collect([
            (object)[
                'id' => 1,
                'employee_name' => 'Rajesh Kumar',
                'employee_code' => 'EMP-001',
                'date' => '2025-10-07',
                'hours' => 2.5,
                'reason' => 'Project deadline',
                'status' => 'approved',
                'project' => 'Residential Complex'
            ]
        ]);

        return view('admin.overtime.index', compact('overtimes'));
    }

    /**
     * Show the form for creating new overtime request
     */
    public function create()
    {
        return view('admin.overtime.create');
    }

    /**
     * Store a newly created overtime request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:8',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual overtime creation logic
        return redirect()->route('admin.overtime.index')
            ->with('success', 'Overtime request submitted successfully');
    }

    /**
     * Display the specified overtime request
     */
    public function show($id)
    {
        $overtime = (object)[
            'id' => $id,
            'employee_name' => 'Rajesh Kumar',
            'employee_code' => 'EMP-001',
            'date' => '2025-10-07',
            'hours' => 2.5,
            'reason' => 'Project deadline work',
            'status' => 'approved'
        ];

        return view('admin.overtime.show', compact('overtime'));
    }

    /**
     * Show the form for editing overtime request
     */
    public function edit($id)
    {
        $overtime = (object)[
            'id' => $id,
            'employee_name' => 'Rajesh Kumar',
            'date' => '2025-10-07',
            'hours' => 2.5,
            'reason' => 'Project deadline work'
        ];

        return view('admin.overtime.edit', compact('overtime'));
    }

    /**
     * Update the specified overtime request
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:8',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return redirect()->route('admin.overtime.index')
            ->with('success', 'Overtime request updated successfully');
    }

    /**
     * Remove the specified overtime request
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Overtime request deleted successfully'
        ]);
    }
}