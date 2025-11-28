<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $request->validate(['locale' => 'required|string|in:en,ar']);
        
        $locale = $request->locale;
        Session::put('locale', $locale);  // Save for NEXT request only
        
        // Log::info("Controller input locale: $locale");
        // Log::info("Controller session after put: " . Session::get('locale'));
        
       
        return response()->json(['success' => true]);
    }

}

