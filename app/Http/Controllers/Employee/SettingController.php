<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /**
     * Display system settings
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied');
        }

        // Sample system settings
        $settings = (object)[
            'company' => (object)[
                'name' => 'Teqin Vally Construction',
                'address' => 'DLF City, Phase 3, Gurgaon',
                'phone' => '+91 98765 43210',
                'email' => 'info@teqinvally.com',
                'website' => 'www.teqinvally.com',
                'logo' => null
            ],
            'attendance' => (object)[
                'office_start_time' => '09:00',
                'office_end_time' => '18:00',
                'late_threshold_minutes' => 15,
                'half_day_threshold_hours' => 4,
                'overtime_threshold_hours' => 8,
                'gps_radius_meters' => 100,
                'auto_checkout_enabled' => true,
                'weekend_work_allowed' => true
            ],
            'leaves' => (object)[
                'annual_leave_days' => 21,
                'sick_leave_days' => 12,
                'casual_leave_days' => 7,
                'maternity_leave_days' => 180,
                'advance_booking_days' => 7,
                'auto_approval_enabled' => false
            ],
            'projects' => (object)[
                'default_project_duration_months' => 12,
                'approval_steps_required' => 12,
                'progress_update_frequency' => 'weekly',
                'budget_variance_threshold' => 10,
                'milestone_reminder_days' => 7,
                'resource_overallocation_threshold' => 100
            ],
            'notifications' => (object)[
                'email_notifications' => true,
                'sms_notifications' => false,
                'late_arrival_notifications' => true,
                'leave_approval_notifications' => true,
                'project_milestone_notifications' => true,
                'overtime_approval_notifications' => true
            ],
            'security' => (object)[
                'session_timeout_minutes' => 120,
                'password_expiry_days' => 90,
                'login_attempts_limit' => 5,
                'two_factor_enabled' => false,
                'ip_whitelist_enabled' => false
            ]
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole(['super-admin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $validator = Validator::make($request->all(), [
            'setting_type' => 'required|string|in:company,attendance,leaves,projects,notifications,security',
            // Add specific validation rules based on setting type
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement actual settings update logic
        return back()->with('success', 'Settings updated successfully');
    }

    /**
     * Update company settings
     */
    public function updateCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string|max:500',
            'company_phone' => 'required|string|max:20',
            'company_email' => 'required|email|max:255',
            'company_website' => 'nullable|url|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Handle logo upload and company settings update
        return back()->with('success', 'Company settings updated successfully');
    }

    /**
     * Update attendance settings
     */
    public function updateAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'office_start_time' => 'required|date_format:H:i',
            'office_end_time' => 'required|date_format:H:i|after:office_start_time',
            'late_threshold_minutes' => 'required|integer|min:1|max:60',
            'half_day_threshold_hours' => 'required|numeric|min:2|max:8',
            'overtime_threshold_hours' => 'required|numeric|min:6|max:12',
            'gps_radius_meters' => 'required|integer|min:10|max:1000',
            'auto_checkout_enabled' => 'boolean',
            'weekend_work_allowed' => 'boolean'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement attendance settings update
        return back()->with('success', 'Attendance settings updated successfully');
    }

    /**
     * Update project settings
     */
    public function updateProjects(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'default_project_duration_months' => 'required|integer|min:1|max:60',
            'approval_steps_required' => 'required|integer|min:3|max:20',
            'progress_update_frequency' => 'required|in:daily,weekly,biweekly,monthly',
            'budget_variance_threshold' => 'required|numeric|min:1|max:50',
            'milestone_reminder_days' => 'required|integer|min:1|max:30',
            'resource_overallocation_threshold' => 'required|integer|min:50|max:200'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // TODO: Implement project settings update
        return back()->with('success', 'Project settings updated successfully');
    }
}