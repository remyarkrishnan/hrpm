<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Admin Routes - Complete HR & Project Management System
|--------------------------------------------------------------------------
| All routes for the construction company management system
| Based on SRS requirements for HR and Project Management modules
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');

    // === HR MANAGEMENT MODULE (SRS Module 1) ===

    // Employee Management (Already implemented)
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Attendance Management (SRS 4.4 - GPS based attendance)
    Route::resource('attendance', AttendanceController::class);
    Route::post('attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::post('attendance/{attendance}/reject', [AttendanceController::class, 'reject'])->name('attendance.reject');

    // Leave Management (SRS 4.2 - Vacation/Leave Management)
    Route::resource('leaves', LeaveController::class);
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('/leaves/{id}/assign-manager', [LeaveController::class, 'assignManager'])->name('leaves.assignManager');

    // Overtime Management
    Route::resource('overtime', OvertimeController::class);
    Route::post('overtime/{overtime}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
    Route::post('overtime/{overtime}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');

    // Shift Management (SRS 4.3 - Shift Assignment)
    Route::resource('shifts', ShiftController::class);
    Route::post('shifts/{shift}/assign', [ShiftController::class, 'assignEmployees'])->name('shifts.assign');
    Route::delete('shifts/{shift}/unassign/{user}', [ShiftController::class, 'unassignEmployee'])->name('shifts.unassign');

    // === PROJECT MANAGEMENT MODULE (SRS Module 2) ===

    // Project Creation & Management (SRS 5.1 - Already implemented)
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');

    // Project Approval Process (SRS 5.2 - 12-Step Workflow)
    Route::resource('approvals', ApprovalController::class)->except(['create']);
    Route::get('approvals/project/{project}', [ApprovalController::class, 'projectApprovals'])->name('approvals.project');
    Route::post('approvals/{approval}/approve', [ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{approval}/reject', [ApprovalController::class, 'reject'])->name('approvals.reject');

    // Project Planning (SRS 5.3 - Sub-plans, Timelines, Milestones)
    Route::resource('planning', PlanningController::class);
    Route::get('planning/project/{project}', [PlanningController::class, 'projectPlanning'])->name('planning.project');
    Route::post('planning/{planning}/milestone', [PlanningController::class, 'addMilestone'])->name('planning.milestone');

    // Resource Allocation (SRS 5.4 - Employee to Project Assignment)
    Route::resource('resources', ResourceController::class);
    Route::get('resources/project/{project}', [ResourceController::class, 'projectResources'])->name('resources.project');
    Route::post('resources/assign', [ResourceController::class, 'assignToProject'])->name('resources.assign');
    Route::delete('resources/unassign/{project}/{user}', [ResourceController::class, 'unassignFromProject'])->name('resources.unassign');

    // === REPORTS MODULE ===

    // HR Reports (SRS 4.5)
    Route::get('hr-reports', [ReportController::class, 'hrReports'])->name('hr-reports.index');
    Route::get('hr-reports/attendance', [ReportController::class, 'attendanceReport'])->name('hr-reports.attendance');
    Route::get('hr-reports/leaves', [ReportController::class, 'leaveReport'])->name('hr-reports.leaves');
    Route::get('hr-reports/overtime', [ReportController::class, 'overtimeReport'])->name('hr-reports.overtime');
    Route::get('hr-reports/export/{type}', [ReportController::class, 'exportHrReport'])->name('hr-reports.export');

    // Project Reports (SRS 5.6)
    Route::get('project-reports', [ReportController::class, 'projectReports'])->name('project-reports.index');
    Route::get('project-reports/progress', [ReportController::class, 'progressReport'])->name('project-reports.progress');
    Route::get('project-reports/allocation', [ReportController::class, 'allocationReport'])->name('project-reports.allocation');
    Route::get('project-reports/export/{type}', [ReportController::class, 'exportProjectReport'])->name('project-reports.export');

    // === SETTINGS MODULE ===

    // System Settings
    Route::resource('settings', SettingController::class)->only(['index', 'store']);
    Route::post('settings/company', [SettingController::class, 'updateCompany'])->name('settings.company');
    Route::post('settings/attendance', [SettingController::class, 'updateAttendance'])->name('settings.attendance');
    Route::post('settings/projects', [SettingController::class, 'updateProjects'])->name('settings.projects');
});

/*
|--------------------------------------------------------------------------
| API Routes for Mobile App Integration
|--------------------------------------------------------------------------
| Routes for mobile attendance app with GPS tracking
*/

Route::prefix('api/mobile')->middleware(['auth:sanctum'])->group(function () {

    // Mobile Attendance API
    Route::post('attendance/checkin', [AttendanceController::class, 'mobileCheckin'])->name('api.attendance.checkin');
    Route::post('attendance/checkout', [AttendanceController::class, 'mobileCheckout'])->name('api.attendance.checkout');
    Route::get('attendance/status', [AttendanceController::class, 'mobileStatus'])->name('api.attendance.status');

    // Employee Info API
    Route::get('employee/profile', [UserController::class, 'mobileProfile'])->name('api.employee.profile');
    Route::get('employee/shifts', [ShiftController::class, 'mobileShifts'])->name('api.employee.shifts');

    // Project Info API  
    Route::get('projects/assigned', [ProjectController::class, 'mobileProjects'])->name('api.projects.assigned');
});
