<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;

// Public API routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
});

// Protected API routes
Route::middleware('auth:api')->group(function () {
    // Auth endpoints
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::post('/refresh', [LoginController::class, 'refresh']);
        Route::get('/me', [LoginController::class, 'me']);
    });

    // User info
    Route::get('/user', function (Request $request) {
        return response()->json([
            'success' => true,
            'data' => $request->user()->load('roles', 'permissions')
        ]);
    });

    // Admin API routes (check roles in controller instead of middleware)
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'apiData']);

        // Users
        Route::apiResource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus']);
    });

    // Location-based endpoints
    Route::prefix('location')->group(function () {
        Route::post('/validate-geofence', function(Request $request) {
            // Geofence validation logic
            return response()->json([
                'success' => true,
                'message' => 'Location validated successfully',
                'within_geofence' => true
            ]);
        });
    });

    // Attendance endpoints
    Route::prefix('attendance')->group(function () {
        Route::post('/check-in', function(Request $request) {
            // Attendance check-in logic
            return response()->json([
                'success' => true,
                'message' => 'Checked in successfully'
            ]);
        });

        Route::post('/check-out', function(Request $request) {
            // Attendance check-out logic
            return response()->json([
                'success' => true,
                'message' => 'Checked out successfully'
            ]);
        });
    });
});