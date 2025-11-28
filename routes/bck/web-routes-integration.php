<?php

/*
|--------------------------------------------------------------------------
| ADD THIS TO YOUR EXISTING routes/web.php FILE
|--------------------------------------------------------------------------
| Copy the content below and add it to your existing routes/web.php file
| after your current routes but before the require __DIR__.'/auth.php'; line
*/

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;

// Add these imports to the top of your existing routes/web.php file (after existing use statements)

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Your existing routes (dashboard, users, projects) should already be here

    // === ADD THESE NEW ROUTES ===

    // HR Management Routes
    Route::resource('attendance', AttendanceController::class);
    Route::resource('leaves', LeaveController::class);
    Route::resource('overtime', OvertimeController::class);
    Route::resource('shifts', ShiftController::class);

    // Project Management Routes (additional)
    Route::resource('approvals', ApprovalController::class);
    Route::resource('planning', PlanningController::class);
    Route::resource('resources', ResourceController::class);

    // Reports Routes
    Route::get('hr-reports', [ReportController::class, 'hrReports'])->name('hr-reports.index');
    Route::get('project-reports', [ReportController::class, 'projectReports'])->name('project-reports.index');

    // Settings Routes
    Route::resource('settings', SettingController::class)->only(['index', 'store']);

    // Additional approval routes
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('overtime/{overtime}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
    Route::post('overtime/{overtime}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');
});