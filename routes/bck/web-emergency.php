<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function() {
        $user = auth()->user();

        // Redirect based on user role (without middleware)
        if ($user->hasRole(['super-admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->hasRole('project-manager')) {
            return redirect()->route('manager.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
        }
    })->name('dashboard');
});

// Admin Routes (WITHOUT role middleware temporarily)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/api-data', [DashboardController::class, 'apiData'])->name('dashboard.api');

    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Profile
    Route::get('/profile', function() {
        return view('admin.profile.index');
    })->name('profile');
});

// Project Manager Routes
Route::middleware(['auth'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', function() {
        return view('manager.dashboard');
    })->name('dashboard');
});

// Employee Routes  
Route::middleware(['auth'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', function() {
        return view('employee.dashboard');
    })->name('dashboard');
});