<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
       $locale = Session::get('locale');
        app()->setLocale($locale);  // Force override
        $currentLocale = app()->getLocale();

        return $next($request);
    }
}
