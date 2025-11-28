<?php

namespace App\Http\Controllers\Employee;

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
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        $attendanceCounts = Attendance::selectRaw("
                SUM(status = 'present') as present,
                SUM(status = 'absent') as absent,
                SUM(status = 'late') as late,
                SUM(status = 'half_day') as half_day,
                SUM(status = 'work_from_home') as work_from_home,
                SUM(status = 'leave') as leave_count
            ")
            ->where('user_id', auth()->id())
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

        $query->where('user_id', auth()->id());

        $attendances = $query->orderBy('date', 'desc')->paginate(15);

        return view('employee.attendance.index', compact('attendances'));
    }

    /**
     * Show the form for creating new attendance
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }
        $userId = auth()->id();
        $today = Carbon::today()->format('Y-m-d');

        $attendance = Attendance::with('projectLocation')->where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();
        $action = 'checkin';
        $disabled = false;
        $selectedProject = null;
        $selectedLocation = null;
        $selectedLocationid = null;

        if ($attendance) {
            if ($attendance->check_in && $attendance->check_in_location && !$attendance->check_out) {
                $action = 'checkout';
                $selectedProject = $attendance->project_id;
                $selectedLocation = $attendance->projectLocation->location_name;
                $selectedLocationid = $attendance->projectLocation->id;
                $disabled = true; // disable project/location select
            } elseif ($attendance->check_out) {
                $action = 'checked_out';
                $selectedProject = $attendance->project_id;
                $selectedLocation = $attendance->projectLocation->location_name;
                $selectedLocationid = $attendance->projectLocation->id;
                $disabled = true;
            }
        }

        $projects = Project::with(['manager', 'creator'])->get();

        return view('employee.attendance.create', compact('projects', 'action', 'disabled', 'selectedProject', 'selectedLocation', 'attendance', 'selectedLocationid'));
    }

    /**
     * Store a newly created attendance
     */
    public function storeold(Request $request)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        // 🧩 Validate input
        $validator = Validator::make($request->all(), [
           
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
            
            // Combine latitude and longitude if available
            //if ($request->filled('latitude') && $request->filled('longitude')) {
            //    $data['check_in_location'] = $request->latitude . ',' . $request->longitude;
               // dd($data['check_in_location']);
           // }

            //if ($request->filled('latitude') && $request->filled('longitude')) {
            //$data['check_in_location'] = $request->latitude . ',' . $request->longitude;
            //}


           if ($request->filled('latitude') && $request->filled('longitude')) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            // Fetch the geofence coordinates from the database
            $location = DB::table('project_locations')
                ->select('geofence_coordinates')
                ->where('id', $request->project_location_id)
                ->first();

            if ($location && $location->geofence_coordinates) {
                // Decode the geofence coordinates JSON into an array of points
                $geofenceCoordinates = json_decode($location->geofence_coordinates, true);

                // Implement Ray-Casting algorithm to check if the point is inside the polygon
                if ($this->pointInPolygon($latitude, $longitude, $geofenceCoordinates)) {
                    $data['check_in_location'] = $latitude . ',' . $longitude; // Store location if inside geofence
                } else {
                    return back()->withErrors(['error' => 'The selected location is outside the project location .'])->withInput();
                }
            } else {
                return back()->withErrors(['error' => 'Geofence data is not available for this location.'])->withInput();
            }
        }

            $data['user_id'] = auth()->id();
            $data['created_by'] = auth()->id();

            // 💾 Save the attendance
            Attendance::create($data);

            return redirect()
                ->route('employee.attendance.index')
                ->with('success', 'Attendance record created successfully.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create attendance record: ' . $e->getMessage()])->withInput();
        }
    }
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        try {
            $userId = auth()->id();
            $today = Carbon::today()->format('Y-m-d');
            $currentTime = Carbon::now();

            // 🔍 Check if user already checked in today
            $attendance = Attendance::where('user_id', $userId)
                ->whereDate('date', $today)
                ->first();

            if (is_null($request->latitude) || is_null($request->longitude)) {
               return back()->withErrors(['error' => 'Live location is required before checking in or out.'])->withInput();
               
            }

            // Check geofence validation if location is sent
            if ($request->filled('latitude') && $request->filled('longitude')) {
                $latitude = $request->latitude;
                $longitude = $request->longitude;

                $location = DB::table('project_locations')
                    ->select('geofence_coordinates')
                    ->where('id', $request->project_location_id)
                    ->first();

                if ($location && $location->geofence_coordinates) {
                    $geofenceCoordinates = json_decode($location->geofence_coordinates, true);

                    if (!$this->pointInPolygon($latitude, $longitude, $geofenceCoordinates)) {
                        return back()->withErrors(['error' => 'You are outside the project location.'])->withInput();
                    }

                    $locationValue = $latitude . ',' . $longitude;
                } else {
                    return back()->withErrors(['error' => 'Geofence data not found for this project location.'])->withInput();
                }
            }


            // 🟩 CASE 1: No record for today → create new check-in
            if (!$attendance) {
                $data = [
                    'user_id'            => $userId,
                    'date'               => $today,
                    'check_in'           => $currentTime,
                    'check_in_location'  => $locationValue ?? null,
                    'status'             => 'present',
                    'project_id'         => $request->project_id,
                    'project_location_id'=> $request->project_location_id,
                    'created_by'         => $userId,
                ];


                if ($request->hasFile('location_image') && $request->file('location_image')->isValid()) {
                $path = $request->file('location_image')->store('attendance-images', 'public');
                $data['check_in_location_image'] = $path;
                }

                Attendance::create($data);

                return redirect()
                    ->route('employee.attendance.index')
                    ->with('success', 'Checked in successfully at ' . $currentTime->format('H:i:s'));
            }

            // 🟨 CASE 2: Already checked in → perform check-out
            if ($attendance && empty($attendance->check_out)) {
                $attendance->update([
                    'check_out'          => $currentTime,
                    'check_out_location' => $locationValue ?? null,
                ]);

                if ($request->hasFile('location_image') && $request->file('location_image')->isValid()) {
                $path = $request->file('location_image')->store('attendance-images', 'public');
                $attendance->check_out_location_image = $path;
                }

                // Optionally calculate working hours
                $attendance->work_hours = $attendance->check_out->diffInMinutes($attendance->check_in) / 60;
                $attendance->save();

                return redirect()
                    ->route('employee.attendance.index')
                    ->with('success', 'Checked out successfully at ' . $currentTime->format('H:i:s'));
            }

            // 🟥 CASE 3: Already checked out → prevent multiple checkouts
            return back()->withErrors(['error' => 'You have already checked out today.']);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Attendance failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function pointInPolygon($lat, $lng, $polygon)
  {
    $numPoints = count($polygon);
    $inside = false;

    // Loop through the polygon edges
    $x1 = $polygon[0]['lat'];
    $y1 = $polygon[0]['lng'];

    for ($i = 1; $i <= $numPoints; $i++) {
        $x2 = $polygon[$i % $numPoints]['lat'];
        $y2 = $polygon[$i % $numPoints]['lng'];

        // Check if point is inside polygon using Ray-Casting
        if ($lng > min($y1, $y2)) {
            if ($lng <= max($y1, $y2)) {
                if ($lat <= max($x1, $x2)) {
                    if ($y1 != $y2) {
                        $xinters = ($lng - $y1) * ($x2 - $x1) / ($y2 - $y1) + $x1;
                        if ($x1 == $x2 || $lat <= $xinters) {
                            $inside = !$inside;
                        }
                    }
                }
            }
        }

        $x1 = $x2;
        $y1 = $y2;
    }

    return $inside;
}

    /**
     * Display the specified attendance
     */
    public function show($id)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        // Sample data - implement actual logic later
         $attendance = Attendance::with([
        'user',             // Employee info
        'project',          // Assigned project
        'projectLocation'   // Location of project
        ])->findOrFail($id);

        return view('employee.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing attendance
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        $attendance = Attendance::with([
        'user',             // Employee info
        'project',          // Assigned project
        'projectLocation'   // Location of project
        ])->findOrFail($id);

        $projects = Project::with(['manager', 'creator'])->get();
        $projectLocations = ProjectLocation::where('project_id', $attendance->project_id)->get();
        return view('employee.attendance.edit', compact('attendance', 'projects','projectLocations'));

    }

    /**
     * Update the specified attendance
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        $attendance = Attendance::with([
                'user',             // Employee info
                'project',          // Assigned project
                'projectLocation'   // Location of project
                ])->findOrFail($id);
    // Validation
       $validator = Validator::make($request->all(), [
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

        if ($request->filled('latitude') && $request->filled('longitude')) {
            $validated['check_in_location']  = $request->latitude . ',' . $request->longitude;
        }

        // Update record
        $attendance->update([
           
            'date'                => Carbon::parse($validated['date']),
            'check_in'            => $checkInDateTime,
            'check_out'           => $checkOutDateTime,
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

        return redirect()->route('employee.attendance.index')
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
        if (!auth()->user()->hasRole(['employee'])) {
            return redirect()->route('employee.dashboard')->with('error', 'Access denied');
        }

        // TODO: Implement actual attendance deletion logic
        return response()->json([
            'success' => true,
            'message' => 'Attendance record deleted successfully'
        ]);
    }
}
