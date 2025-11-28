<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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

        // Get dashboard statistics
        $stats = $this->getDashboardStats();

        return view('employee.dashboard', compact('stats'));
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        return [
            'total_employees' => User::count(),
            'active_employees' => User::where('status', true)->count(),
            'total_projects' => 0, // Will be implemented when Project model is ready
            'active_projects' => 0,
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