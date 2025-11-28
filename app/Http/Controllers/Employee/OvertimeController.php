<?php

namespace App\Http\Controllers\Employee;

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
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
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

        return view('employee.overtime.index', compact('overtimes'));
    }

    /**
     * Show the form for creating new overtime request
     */
    public function create()
    {
        return view('employee.overtime.create');
    }

    /**
     * Store a newly created overtime request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'hours' => 'required|numeric|min:0.5|max:8',
            'reason' => 'required|string|max:500'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Overtime::create([
                'employee_id' => auth()->id(), // ✅ Secure and trusted
                'date' => $request->input('date'),
                'hours' => $request->input('hours'),
                'reason' => $request->input('reason'),
            ]);
        // TODO: Implement actual overtime creation logic
        return redirect()->route('employee.overtime.index')
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

        return view('employee.overtime.show', compact('overtime'));
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

        return view('employee.overtime.edit', compact('overtime'));
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

        return redirect()->route('employee.overtime.index')
            ->with('success', 'Overtime request updated successfully');
    }

    /**
     * Remove the specified overtime request
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Overtime request deleted successfully'
        ]);
    }
}