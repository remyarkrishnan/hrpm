<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftController extends Controller
{
    /**
     * Display a listing of shifts
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample data
        $shifts = collect([
            (object)[
                'id' => 1,
                'name' => 'Morning Shift',
                'start_time' => '07:00:00',
                'end_time' => '15:00:00',
                'type' => 'morning',
                'employees_count' => 15,
                'location' => 'Site A'
            ],
            (object)[
                'id' => 2,
                'name' => 'Evening Shift',
                'start_time' => '15:00:00',
                'end_time' => '23:00:00',
                'type' => 'evening',
                'employees_count' => 12,
                'location' => 'Site A'
            ]
        ]);

        return view('admin.shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating new shift
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        return view('admin.shifts.create');
    }

    /**
     * Store a newly created shift
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:morning,evening,night,flexible'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift created successfully');
    }

    /**
     * Display the specified shift
     */
    public function show($id)
    {
        $shift = (object)[
            'id' => $id,
            'name' => 'Morning Shift',
            'start_time' => '07:00:00',
            'end_time' => '15:00:00',
            'type' => 'morning',
            'employees_count' => 15,
            'location' => 'Site A'
        ];

        return view('admin.shifts.show', compact('shift'));
    }

    /**
     * Show the form for editing shift
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $shift = (object)[
            'id' => $id,
            'name' => 'Morning Shift',
            'start_time' => '07:00',
            'end_time' => '15:00',
            'type' => 'morning'
        ];

        return view('admin.shifts.edit', compact('shift'));
    }

    /**
     * Update the specified shift
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'type' => 'required|in:morning,evening,night,flexible'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return redirect()->route('admin.shifts.index')
            ->with('success', 'Shift updated successfully');
    }

    /**
     * Remove the specified shift
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shift deleted successfully'
        ]);
    }
}