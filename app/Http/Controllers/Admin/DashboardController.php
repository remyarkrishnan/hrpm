<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Check if user has admin role
        $user = Auth::user();

      //  if (!$user->hasRole(['super-admin', 'admin'])) {
            // User doesn't have admin role, redirect to appropriate dashboard
     //       if ($user->hasRole('project-manager')) {
     //           return redirect()->route('manager.dashboard');
     //       }
     //   }

        // Get dashboard statistics
        $stats = $this->getDashboardStats();
        $projects = Project::with(['manager'])->get();
        return view('admin.dashboard', compact('stats' , 'projects'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_employees' => User::count(),
            'active_employees' => User::where('status', true)->count(),
            'total_projects' => Project::count(), // Will be implemented when Project model is ready
            'active_projects' => Project::count(),
            'today_attendance' => 0, // Will be implemented when Attendance model is ready
            'this_month_attendance' => 0,
            'pending_leaves' => 0, // Will be implemented when Leave model is ready
        ];
    }

    /**
     * Get dashboard data for API
     */
    public function apiData()
    {
        $user = Auth::user();

        // Check API permissions
        if (!$user->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient permissions'
            ], 403);
        }

        $stats = $this->getDashboardStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
