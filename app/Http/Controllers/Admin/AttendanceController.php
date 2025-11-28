<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Project;
use App\Models\Attendance;
use App\Models\ProjectLocation;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(Request $request)
    {
            // Check role access
    if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
        return redirect()->route('admin.dashboard')->with('error', 'Access denied');
    }

    $today = Carbon::today();

    // Fetch all attendances for today
    $attendanceCounts = Attendance::selectRaw("
        SUM(status = 'present') as present,
        SUM(status = 'absent') as absent,
        SUM(status = 'late') as late,
        SUM(status = 'half_day') as half_day,
        SUM(status = 'work_from_home') as work_from_home,
        SUM(status = 'leave') as leave_count
    ")
    ->whereDate('date', $today)
    ->first();
    // Build query with relationships
    $query = Attendance::with(['user', 'project', 'projectLocation']);

    // Optional filters
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    if ($request->filled('date')) {
        $query->whereDate('date', $request->date);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('project_id')) {
        $query->where('project_id', $request->project_id);
    }

    if ($request->filled('project_location_id')) {
        $query->where('project_location_id', $request->project_location_id);
    }

    // Show deleted records if requested
    if ($request->filled('show_deleted') && $request->show_deleted == 1) {
        $query->onlyTrashed(); // fetch only soft-deleted records
    }

    // Fetch records with latest first, paginated
    $attendances = $query->orderBy('date', 'desc')->paginate(15);

    // Pass list of employees and projects for filters in view
    $employees = User::whereHas('roles', fn($q) => $q->where('name', 'employee'))->get();
    $projects = Project::all();

    return view('admin.attendance.index', compact('attendances', 'employees', 'projects', 'attendanceCounts'));

    }

    /**
     * Show the form for creating new attendance
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $employees = User::with('roles')->whereHas('roles', function($q) {
            $q->whereIn('name', ['employee']);
        })->get();

        $projects = Project::with(['manager', 'creator'])->get();

        return view('admin.attendance.create', compact('employees', 'projects'));
    }

    /**
     * Store a newly created attendance
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // 🧩 Validate input
        $validator = Validator::make($request->all(), [
            'user_id'              => 'required|exists:users,id',
            'date'                 => [
                'required',
                'date',
                Rule::unique('attendances')->where(function ($query) use ($request) {
                    return $query->where('user_id', $request->user_id);
                }),
            ],
            'check_in'             => 'required|date_format:H:i',
            'check_out'            => 'nullable|date_format:H:i|after:check_in',
            'check_in_location'    => 'nullable|string|max:255',
            'check_out_location'   => 'nullable|string|max:255',
            'location_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'working_hours'        => 'nullable|numeric|min:0',
            'break_duration'       => 'nullable|integer|min:0',
            'overtime_hours'       => 'nullable|numeric|min:0',
            'status'               => 'required|in:present,absent,late,half_day,work_from_home',
            'project_id'           => 'nullable|exists:projects,id',
            'project_location_id'  => 'nullable|exists:project_locations,id',
            'approved_by'          => 'nullable|exists:users,id',
            'approved_at'          => 'nullable|date',
            'notes'                => 'nullable|string|max:1000',
            'late_reason'          => 'nullable|string|max:1000',
        ], [
            'user_id.required' => 'Please select an employee.',
            'user_id.exists'   => 'The selected employee does not exist.',
            'date.required'    => 'Please select the attendance date.',
            'date.unique'      => 'This employee already has an attendance record for the selected date.',
            'check_in.required' => 'Please enter the check-in time.',
            'check_in.date_format' => 'The check-in time must be in HH:MM format.',
            'check_out.after'  => 'Check-out time must be after check-in time.',
            'status.required'  => 'Please select the attendance status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'user_id',
                'date',
                'check_in',
                'check_out',
                'check_in_location',
                'check_out_location',
                'location_image',
                'working_hours',
                'break_duration',
                'overtime_hours',
                'status',
                'project_id',
                'project_location_id',
                'approved_by',
                'approved_at',
                'notes',
                'late_reason'
            ]);

            // 🖼️ Handle optional location image upload
            if ($request->hasFile('location_image') && $request->file('location_image')->isValid()) {
                $path = $request->file('location_image')->store('attendance-images', 'public');
                $data['location_image'] = $path;
            }

            $date = Carbon::parse($data['date'])->format('Y-m-d');

            $data['check_in'] = !empty($data['check_in'])
            ? \Carbon\Carbon::parse("{$date} {$data['check_in']}:00")
            : null;
            $data['check_out'] = !empty($data['check_out'])
            ? \Carbon\Carbon::parse("{$date} {$data['check_out']}:00")
            : null;

            $data['work_hours'] =  $data['check_out']->diffInMinutes( $data['check_in']) / 60;

            $data['approved_by'] = auth()->id();

            $data['created_by'] = auth()->id();

            // 💾 Save the attendance
            Attendance::create($data);

            return redirect()
                ->route('admin.attendance.index')
                ->with('success', 'Attendance record created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create attendance record: ' . $e->getMessage()])->withInput();
        }
   }

    /**
     * Display the specified attendance
     */
    public function show($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample data - implement actual logic later
        $attendance = Attendance::with([
        'user',             // Employee info
        'project',          // Assigned project
        'projectLocation'   // Location of project
        ])->findOrFail($id);

        

        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing attendance
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample data - implement actual logic later
        $attendance = Attendance::with([
        'user',             // Employee info
        'project',          // Assigned project
        'projectLocation'   // Location of project
        ])->findOrFail($id);

        $projects = Project::with(['manager', 'creator'])->get();
        $projectLocations = ProjectLocation::where('project_id', $attendance->project_id)->get();
        return view('admin.attendance.edit', compact('attendance', 'projects','projectLocations'));
    }

    /**
     * Update the specified attendance
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
        return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }
        $attendance = Attendance::with([
                'user',             // Employee info
                'project',          // Assigned project
                'projectLocation'   // Location of project
                ])->findOrFail($id);
    // Validation
       $validator = Validator::make($request->all(), [
        'user_id'              => 'required|exists:users,id',
        'date'                 => [
            'required',
            'date',
            Rule::unique('attendances')
                ->where(fn($q) => $q->where('user_id', $request->user_id))
                ->ignore($id),
        ],
        'check_in'             => 'required|date_format:H:i',
        'check_out'            => 'nullable|date_format:H:i|after:check_in',
        'check_in_location'    => 'nullable|string|max:255',
        'check_out_location'   => 'nullable|string|max:255',
        'location_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        'work_hours'        => 'nullable|numeric|min:0',
        'break_duration'       => 'nullable|integer|min:0',
        'overtime_hours'       => 'nullable|numeric|min:0',
        'status'               => 'required|in:present,absent,late,half_day,work_from_home',
        'project_id'           => 'nullable|exists:projects,id',
        'project_location_id'  => 'nullable|exists:project_locations,id',
        'approved_by'          => 'nullable|exists:users,id',
        'approved_at'          => 'nullable|date',
        'notes'                => 'nullable|string|max:1000',
       
    ], [
        'user_id.required' => 'Please select an employee.',
        'user_id.exists'   => 'The selected employee does not exist.',
        'date.required'    => 'Please select the attendance date.',
        'date.unique'      => 'This employee already has an attendance record for the selected date.',
        'check_in.required' => 'Please enter the check-in time.',
        'check_in.date_format' => 'The check-in time must be in HH:MM format.',
        'check_out.after'  => 'Check-out time must be after check-in time.',
        'status.required'  => 'Please select the attendance status.',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    try {
        $validated = $validator->validated();

        // Convert date + time to full datetime using Carbon
        $checkInDateTime = Carbon::parse($validated['date'] . ' ' . $validated['check_in']);
        $checkOutDateTime = isset($validated['check_out'])
            ? Carbon::parse($validated['date'] . ' ' . $validated['check_out'])
            : null;

        // Auto-calculate working hours if both check-in and check-out exist
        $workingHours = $checkOutDateTime
            ? $checkOutDateTime->diffInMinutes($checkInDateTime) / 60
            : $attendance->working_hours;

        // Handle location image upload
        if ($request->hasFile('location_image')) {
            if ($attendance->location_image) {
                Storage::disk('public')->delete($attendance->location_image);
            }
            $validated['location_image'] = $request->file('location_image')->store('attendance-images', 'public');
        }

        // Update record
        $attendance->update([
            'user_id'             => $validated['user_id'],
            'date'                => Carbon::parse($validated['date']),
            'check_in'       => $checkInDateTime,
            'check_out'      => $checkOutDateTime,
            'check_in_location'   => $validated['check_in_location'] ?? $attendance->check_in_location,
            'check_out_location'  => $validated['check_out_location'] ?? $attendance->check_out_location,
            'location_image'      => $validated['location_image'] ?? $attendance->location_image,
            'work_hours'          => $workingHours,
            'break_duration'      => $validated['break_duration'] ?? 0,
            'overtime_hours'      => $validated['overtime_hours'] ?? 0,
            'status'              => $validated['status'],
            'project_id'          => $validated['project_id'] ?? null,
            'project_location_id' => $validated['project_location_id'] ?? null,
            'approved_by'         => $validated['approved_by'] ?? null,
            'approved_at'         => isset($validated['approved_at'])
                                        ? Carbon::parse($validated['approved_at'])
                                        : $attendance->approved_at,
            'notes'               => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.attendance.index')
            ->with('success', 'Attendance record updated successfully.');

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Failed to update attendance record: ' . $e->getMessage()])
                     ->withInput();
    }
    }

    /**
     * Remove the specified attendance
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        // TODO: Implement actual attendance deletion logic
        return response()->json([
            'success' => true,
            'message' => 'Attendance record deleted successfully'
        ]);
    }
}
