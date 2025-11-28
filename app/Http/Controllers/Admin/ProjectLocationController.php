<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectLocation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectLocationController extends Controller
{
    /**
     * Display a listing of project locations
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $query = ProjectLocation::with('project');

        // Search by location name or project name
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('location_name', 'like', "%{$search}%")
                  ->orWhereHas('project', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->get('project_id'));
        }

        $locations = $query->latest()->paginate(15);
        $projects = Project::all();

        return view('admin.project_locations.index', compact('locations', 'projects'));
    }

    /**
     * Show the form for creating a new project location
     */
    public function create()
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $projects = Project::all();
        return view('admin.project_locations.create', compact('projects'));
    }

    /**
     * Store a newly created project location
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'location_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
            'geofence_coordinates' => 'required|json', // Required polygon data
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            ProjectLocation::create($request->all());
            return redirect()->route('admin.project-locations.index')
                             ->with('success', 'Project location created successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to create location: ' . $e->getMessage()])
                         ->withInput();
        }
    }

    /**
     * Show the form for editing the specified project location
     */
    public function edit(ProjectLocation $projectLocation)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        $projects = Project::all();
        return view('admin.project_locations.edit', compact('projectLocation', 'projects'));
    }

    /**
     * Update the specified project location
     */
    public function update(Request $request, ProjectLocation $projectLocation)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'project_id' => 'required|exists:projects,id',
            'location_name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'geofence_coordinates' => 'required|json',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $projectLocation->update($request->all());
            return redirect()->route('admin.project-locations.index')
                             ->with('success', 'Project location updated successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update location: ' . $e->getMessage()])
                         ->withInput();
        }
    }

    /**
     * Remove the specified project location
     */
    public function destroy(ProjectLocation $projectLocation)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        try {
            $projectLocation->delete();
            return response()->json([
                'success' => true,
                'message' => 'Project location deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete location: ' . $e->getMessage()
            ], 500);
        }
    }
}
