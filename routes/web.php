<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\OvertimeController;
use App\Http\Controllers\Admin\PlanningController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ProjectStepController;
use App\Http\Controllers\Admin\ProjectReportController;
use App\Http\Controllers\Admin\ProjectSubplanController;
use App\Http\Controllers\Admin\ProjectLocationController;
use App\Http\Controllers\Admin\ProjectProgressController;
use App\Http\Controllers\Admin\ResourceOverviewController;
use App\Http\Controllers\Admin\ResourceAllocationController;


// require base_path('routes/employee-routes.php');
// require base_path('routes/manager-routes.php');
// require base_path('routes/supervisor-routes.php');
// Route::middleware('web')->group(function () {
    require base_path('routes/employee-routes.php');
    require base_path('routes/manager-routes.php');
    require base_path('routes/supervisor-routes.php');
// });
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware('set_locale')->group(function () {
    Route::get('/locale-test', function () {
        dd('SETLOCALE IS WORKING!');
    });

    Route::post('/change-language', [LanguageController::class, 'changeLanguage'])
        ->name('change.language');
});
Route::get('/debug-lang', function () {
    return [
        'current_locale' => app()->getLocale(),
        'config_locale' => config('app.locale'),
        'session_locale' => session('locale'),
        'dashboard_en' => trans('messages.dashboard', [], 'en'),
        'dashboard_ar' => trans('messages.dashboard', [], 'ar'),
        'dashboard_default' => __('messages.dashboard'),
    ];
});
// Home route - redirect based on authentication status
Route::get('/', function () {
    if (auth()->check()) {
        // User is logged in, redirect to appropriate dashboard
        $user = auth()->user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('project-manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }elseif ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }
    }

    // User is not logged in, redirect to login
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard route - redirect based on user role
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('project-manager')) {
            return redirect()->route('manager.dashboard');
        } elseif ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }elseif ($user->hasRole('supervisor')) {
            return redirect()->route('supervisor.dashboard');
        }
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Check if user has admin role in the controller instead of middleware
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api-data', [DashboardController::class, 'apiData'])->name('dashboard.api');

    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Profile
    Route::get('/profile', function () {
        return view('admin.profile.index');
    })->name('profile');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    //Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');


    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Project Management Routes - Add these
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{project}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::post('/projects/{project}/status', [ProjectController::class, 'updateStatus'])->name('projects.update-status');
    Route::get('/projects/{project}/locations', [ProjectController::class, 'getLocations'])->name('projects.locations');

    Route::put('project-steps/{id}', [ProjectStepController::class, 'update'])->name('project_steps.update');

    // Project Subplans (linked to each Step)
    Route::prefix('projects/steps/{stepId}/subplans')->name('subplans.')->group(function () {
        Route::get('/', [ProjectSubplanController::class, 'index'])->name('index');
        Route::get('/create', [ProjectSubplanController::class, 'create'])->name('create');
        Route::post('/', [ProjectSubplanController::class, 'store'])->name('store');
        
    });

    Route::get('/projects/subplans/{id}/edit', [ProjectSubplanController::class, 'edit'])->name('subplans.edit');
    Route::put('/projects/subplans/{id}', [ProjectSubplanController::class, 'update'])->name('subplans.update');
    Route::delete('/projects/subplans/{id}', [ProjectSubplanController::class, 'destroy'])->name('subplans.destroy');
    
    
    // Resource Allocation routes
    Route::prefix('subplans/{subplanId}/allocations')->name('allocations.')->group(function () {
    Route::get('/', [ResourceAllocationController::class, 'index'])->name('index');
    Route::post('/', [ResourceAllocationController::class, 'store'])->name('store');
    Route::put('/{id}', [ResourceAllocationController::class, 'update'])->name('update');
    Route::delete('/{id}', [ResourceAllocationController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('projects/subplans/{subplanId}/resources')->name('subplans.resources.')->group(function () {
    Route::get('/', [ResourceAllocationController::class, 'index'])->name('index');
    Route::post('/', [ResourceAllocationController::class, 'store'])->name('store');
    Route::delete('/{id}', [ResourceAllocationController::class, 'destroy'])->name('destroy');
    });

    //Route::get('/projects/resources/overview', [ResourceOverviewController::class, 'index'])->name('projects.resources.overview');
    Route::get('/projects/{projectId}/resources', [ResourceOverviewController::class, 'index'])->name('projects.resources.show');
    Route::get('/projects/{project}/progress', [ProjectProgressController::class, 'show'])->name('projects.progress.show');

    Route::get('project-reports', [ProjectReportController::class, 'index'])->name('project-reports.index');
   


    Route::get('/project-locations', [ProjectLocationController::class, 'index'])->name('project-locations.index');
    Route::get('/project-locations/create', [ProjectLocationController::class, 'create'])->name('project-locations.create');
    Route::post('/project-locations', [ProjectLocationController::class, 'store'])->name('project-locations.store');
    Route::get('/project-locations/{projectLocation}', [ProjectLocationController::class, 'show'])->name('project-locations.show');
    Route::get('/project-locations/{projectLocation}/edit', [ProjectLocationController::class, 'edit'])->name('project-locations.edit');
    Route::put('/project-locations/{projectLocation}', [ProjectLocationController::class, 'update'])->name('project-locations.update');
    Route::delete('/project-locations/{projectLocation}', [ProjectLocationController::class, 'destroy'])->name('project-locations.destroy');


    // HR Management Routes
    Route::resource('attendance', AttendanceController::class);
    Route::resource('leaves', LeaveController::class);
    Route::resource('overtime', OvertimeController::class);
    Route::resource('shifts', ShiftController::class);

    // Project Management Routes (additional)
    Route::resource('approvals', ApprovalController::class);

    Route::post('/approvals/{id}/approve', [ApprovalController::class, 'approve'])->name('admin.approvals.approve');
    Route::post('/approvals/{id}/reject', [ApprovalController::class, 'reject'])->name('admin.approvals.reject');
    // Admin approval details
    Route::get('/approvals/leave/{id}', [ApprovalController::class, 'viewLeave'])->name('admin.approvals.viewLeave');
    Route::get('/approvals/loan/{id}', [ApprovalController::class, 'viewLoan'])->name('admin.approvals.viewLoan');
    Route::get('/approvals/training/{id}', [ApprovalController::class, 'viewTraining'])->name('admin.approvals.viewTraining');
    Route::get('/approvals/document/{id}', [ApprovalController::class, 'viewDocument'])->name('admin.approvals.viewDocument');


    Route::resource('planning', PlanningController::class);
    Route::resource('resources', ResourceController::class);

    // Reports Routes
    Route::get('hr-reports', [ReportController::class, 'hrReports'])->name('hr-reports.index');
    //Route::get('project-reports', [ReportController::class, 'projectReports'])->name('project-reports.index');

    // Settings Routes
    Route::resource('settings', SettingController::class)->only(['index', 'store']);

    // Approval routes
    Route::post('leaves/{leave}/approve', [LeaveController::class, 'approve'])->name('leaves.approve');
    Route::post('leaves/{leave}/reject', [LeaveController::class, 'reject'])->name('leaves.reject');
    Route::post('leaves/{leave}/assign-manager', [LeaveController::class, 'assignManager'])->name('leaves.assignManager');
    Route::post('leaves/{leave}/manager-action', [LeaveController::class, 'managerAction'])->name('leaves.managerAction');
    Route::post('overtime/{overtime}/approve', [OvertimeController::class, 'approve'])->name('overtime.approve');
    Route::post('overtime/{overtime}/reject', [OvertimeController::class, 'reject'])->name('overtime.reject');
    
	//profile updates
	Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

});

/*
|--------------------------------------------------------------------------
| Project Manager Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function () {
        // Simple manager dashboard
        $user = auth()->user();
        $stats = [
            'total_employees' => \App\Models\User::count(),
            'active_employees' => \App\Models\User::where('status', true)->count(),
            'total_projects' => 0, // Add project count when projects are implemented
            'active_projects' => 0,
            'today_attendance' => 0, // Add attendance count when implemented
            'this_month_attendance' => 0,
            'pending_leaves' => 0, // Add leave count when implemented
        ];

        return view('manager.dashboard', compact('stats'));
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Employee Routes  
|--------------------------------------------------------------------------
*/

