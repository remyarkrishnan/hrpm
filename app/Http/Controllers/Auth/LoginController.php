<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        // Check if user exists and is active
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            $message = 'User not found';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 404);
            }
            return back()->withErrors(['email' => $message])->withInput();
        }

        if (!$user->status) {
            $message = 'Account is deactivated';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 403);
            }
            return back()->withErrors(['email' => $message])->withInput();
        }

        // Attempt to authenticate using default 'web' guard
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user->update(['last_login_at' => now()]);

            if ($request->wantsJson()) {
                try {
                    $token = JWTAuth::fromUser($user);
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful',
                        'data' => [
                            'user' => $user->load('roles', 'permissions'),
                            'token' => $token,
                            'token_type' => 'Bearer',
                            'expires_in' => config('jwt.ttl', 60) * 60
                        ]
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Login successful (JWT unavailable)',
                        'data' => [
                            'user' => $user->load('roles', 'permissions'),
                        ]
                    ]);
                }
            }

            return redirect()->intended(route('dashboard'));
        }

        $message = 'Invalid credentials';
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], 401);
        }
        return back()->withErrors(['email' => $message])->withInput();
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        if ($request->wantsJson()) {
            try {
                if (JWTAuth::getToken()) {
                    JWTAuth::invalidate(JWTAuth::getToken());
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Successfully logged out'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to logout'
                ], 500);
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Refresh JWT token
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());

            return response()->json([
                'success' => true,
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl', 60) * 60
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed'
            ], 401);
        }
    }

    /**
     * Get authenticated user
     */
    public function me()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user->load('roles', 'permissions'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'roles' => $user->getRoleNames()
            ]
        ]);
    }
}