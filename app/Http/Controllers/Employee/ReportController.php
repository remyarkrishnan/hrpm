<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    /**
     * Display HR reports dashboard
     */
    public function hrReports(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample HR statistics
        $hrStats = (object)[
            'total_employees' => 48,
            'present_today' => 45,
            'on_leave_today' => 3,
            'attendance_rate' => 93.75,
            'pending_leave_requests' => 7,
            'overtime_hours_month' => 324.5,
            'departments' => [
                (object)['name' => 'Construction', 'employees' => 25, 'attendance_rate' => 96.0],
                (object)['name' => 'Engineering', 'employees' => 12, 'attendance_rate' => 91.7],
                (object)['name' => 'Safety', 'employees' => 6, 'attendance_rate' => 100.0],
                (object)['name' => 'Administration', 'employees' => 5, 'attendance_rate' => 80.0]
            ],
            'monthly_attendance' => [
                (object)['month' => 'Jan', 'rate' => 94.2],
                (object)['month' => 'Feb', 'rate' => 92.8],
                (object)['month' => 'Mar', 'rate' => 95.1],
                (object)['month' => 'Apr', 'rate' => 93.5],
                (object)['month' => 'May', 'rate' => 91.9],
                (object)['month' => 'Jun', 'rate' => 94.7]
            ]
        ];

        return view('admin.hr-reports.index', compact('hrStats'));
    }

    /**
     * Display project reports dashboard
     */
    public function projectReports(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample project statistics
        $projectStats = (object)[
            'total_projects' => 12,
            'active_projects' => 8,
            'completed_projects' => 3,
            'delayed_projects' => 2,
            'overall_progress' => 67.3,
            'total_budget' => 15750000,
            'spent_budget' => 8420000,
            'budget_utilization' => 53.5,
            'projects_by_status' => [
                (object)['status' => 'In Progress', 'count' => 8, 'percentage' => 66.7],
                (object)['status' => 'Completed', 'count' => 3, 'percentage' => 25.0],
                (object)['status' => 'On Hold', 'count' => 1, 'percentage' => 8.3]
            ],
            'projects_by_type' => [
                (object)['type' => 'Residential', 'count' => 5, 'budget' => 7500000],
                (object)['type' => 'Commercial', 'count' => 4, 'budget' => 6200000],
                (object)['type' => 'Infrastructure', 'count' => 2, 'budget' => 1800000],
                (object)['type' => 'Industrial', 'count' => 1, 'budget' => 250000]
            ],
            'resource_allocation' => [
                (object)['department' => 'Construction', 'allocated' => 25, 'utilization' => 92.0],
                (object)['department' => 'Engineering', 'allocated' => 12, 'utilization' => 87.5],
                (object)['department' => 'Safety', 'allocated' => 6, 'utilization' => 100.0]
            ]
        ];

        return view('admin.project-reports.index', compact('projectStats'));
    }

    /**
     * Generate attendance report
     */
    public function attendanceReport(Request $request)
    {
        $period = $request->get('period', 'current_month');

        // Sample attendance report data
        $attendanceData = collect([
            (object)[
                'employee_name' => 'Rajesh Kumar',
                'department' => 'Construction',
                'present_days' => 22,
                'absent_days' => 1,
                'late_days' => 3,
                'attendance_rate' => 95.7
            ],
            (object)[
                'employee_name' => 'Priya Singh',
                'department' => 'Engineering',
                'present_days' => 21,
                'absent_days' => 2,
                'late_days' => 1,
                'attendance_rate' => 91.3
            ]
        ]);

        return view('admin.hr-reports.attendance', compact('attendanceData', 'period'));
    }

    /**
     * Generate leave report
     */
    public function leaveReport(Request $request)
    {
        $year = $request->get('year', date('Y'));

        // Sample leave report data
        $leaveData = collect([
            (object)[
                'employee_name' => 'Rajesh Kumar',
                'sick_leave' => 3,
                'casual_leave' => 5,
                'annual_leave' => 12,
                'total_taken' => 20,
                'balance' => 10
            ],
            (object)[
                'employee_name' => 'Priya Singh',
                'sick_leave' => 2,
                'casual_leave' => 4,
                'annual_leave' => 8,
                'total_taken' => 14,
                'balance' => 16
            ]
        ]);

        return view('admin.hr-reports.leaves', compact('leaveData', 'year'));
    }

    /**
     * Generate overtime report
     */
    public function overtimeReport(Request $request)
    {
        $month = $request->get('month', date('Y-m'));

        // Sample overtime report data
        $overtimeData = collect([
            (object)[
                'employee_name' => 'Rajesh Kumar',
                'regular_hours' => 176,
                'overtime_hours' => 24.5,
                'overtime_rate' => 1.5,
                'overtime_pay' => 18375
            ],
            (object)[
                'employee_name' => 'Amit Sharma',
                'regular_hours' => 176,
                'overtime_hours' => 18.0,
                'overtime_rate' => 1.5,
                'overtime_pay' => 13500
            ]
        ]);

        return view('admin.hr-reports.overtime', compact('overtimeData', 'month'));
    }

    /**
     * Generate progress report for projects
     */
    public function progressReport(Request $request)
    {
        $period = $request->get('period', 'current_quarter');

        // Sample progress report data
        $progressData = collect([
            (object)[
                'project_name' => 'Residential Complex - Phase 2',
                'start_date' => '2025-01-15',
                'end_date' => '2025-12-31',
                'progress' => 67.5,
                'status' => 'On Track',
                'budget_used' => 3375000,
                'budget_total' => 5000000
            ],
            (object)[
                'project_name' => 'Commercial Mall Construction',
                'start_date' => '2025-03-01',
                'end_date' => '2026-02-28',
                'progress' => 35.2,
                'status' => 'Delayed',
                'budget_used' => 2200000,
                'budget_total' => 6200000
            ]
        ]);

        return view('admin.project-reports.progress', compact('progressData', 'period'));
    }

    /**
     * Generate resource allocation report
     */
    public function allocationReport(Request $request)
    {
        $month = $request->get('month', date('Y-m'));

        // Sample allocation report data
        $allocationData = collect([
            (object)[
                'employee_name' => 'Rajesh Kumar',
                'department' => 'Construction',
                'projects' => 'Residential Complex',
                'allocation' => 100,
                'utilization' => 95.5,
                'efficiency' => 'High'
            ],
            (object)[
                'employee_name' => 'Priya Singh',
                'department' => 'Engineering',
                'projects' => 'Commercial Mall, Bridge Project',
                'allocation' => 85,
                'utilization' => 78.3,
                'efficiency' => 'Medium'
            ]
        ]);

        return view('admin.project-reports.allocation', compact('allocationData', 'month'));
    }

    /**
     * Export HR report
     */
    public function exportHrReport($type, Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'hr-manager'])) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // TODO: Implement actual export logic (Excel, PDF, CSV)
        $filename = "hr_report_{$type}_" . date('Y-m-d') . '.csv';

        // Sample CSV content
        $csvContent = "Employee,Department,Attendance Rate,Leave Days,Status\n";
        $csvContent .= "Rajesh Kumar,Construction,95.7%,3,Active\n";
        $csvContent .= "Priya Singh,Engineering,91.3%,2,Active\n";

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }

    /**
     * Export project report
     */
    public function exportProjectReport($type, Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin', 'project-manager'])) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // TODO: Implement actual export logic (Excel, PDF, CSV)
        $filename = "project_report_{$type}_" . date('Y-m-d') . '.csv';

        // Sample CSV content
        $csvContent = "Project,Progress,Budget Used,Status,End Date\n";
        $csvContent .= "Residential Complex,67.5%,₹33.75L,On Track,2025-12-31\n";
        $csvContent .= "Commercial Mall,35.2%,₹22.0L,Delayed,2026-02-28\n";

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\""
        ]);
    }
}