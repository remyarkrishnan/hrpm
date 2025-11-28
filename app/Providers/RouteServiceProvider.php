<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('web') // Ensure 'web' middleware is used for employee routes
                ->prefix('employee')
                ->name('employee.')
                ->group(base_path('routes/employee-routes.php')); // Path to your employee-specific routes file

            Route::middleware('web') // Ensure 'web' middleware is used for manager routes
                ->prefix('manager')
                ->name('manager.')
                ->group(base_path('routes/manager-routes.php')); // Path to your manager-specific routes file

        });
    }
}
