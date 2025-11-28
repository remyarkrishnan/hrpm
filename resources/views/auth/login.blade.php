@extends('layouts.auth')

@section('title', 'Login - ' . env('COMPANY_NAME', 'Teqin Vally'))

@section('content')
<div class="auth-card">
    <h2 class="auth-title">Welcome Back</h2>

    <form action="{{ route('login.post') }}" method="POST">
        @csrf

        <!-- Email Field -->
        <div class="mdc-text-field mdc-text-field--outlined">
            <input type="email" id="email" name="email" class="mdc-text-field__input" 
                   value="{{ old('email') }}" required>
            <div class="mdc-notched-outline">
                <div class="mdc-notched-outline__leading"></div>
                <div class="mdc-notched-outline__notch">
                    <label for="email" class="mdc-floating-label">Email Address</label>
                </div>
                <div class="mdc-notched-outline__trailing"></div>
            </div>
        </div>

        <!-- Password Field -->
        <div class="mdc-text-field mdc-text-field--outlined">
            <input type="password" id="password" name="password" class="mdc-text-field__input" required>
            <div class="mdc-notched-outline">
                <div class="mdc-notched-outline__leading"></div>
                <div class="mdc-notched-outline__notch">
                    <label for="password" class="mdc-floating-label">Password</label>
                </div>
                <div class="mdc-notched-outline__trailing"></div>
            </div>
        </div>

        <!-- Remember Me -->
        <div class="mdc-form-field" style="margin: 16px 0;">
            <div class="mdc-checkbox">
                <input type="checkbox" class="mdc-checkbox__native-control" id="remember" name="remember">
                <div class="mdc-checkbox__background">
                    <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                        <path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                    </svg>
                    <div class="mdc-checkbox__mixedmark"></div>
                </div>
                <div class="mdc-checkbox__ripple"></div>
            </div>
            <label for="remember">Remember me</label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="mdc-button mdc-button--raised">
            <span class="mdc-button__label">Sign In</span>
        </button>
    </form>

    <!-- Default Accounts Info (Configurable) -->
    @if(env('SHOW_DEFAULT_ACCOUNTS', true))
    <div class="default-accounts">
        <h4>🔑 Test Accounts - {{ env('COMPANY_NAME', 'Teqin Vally') }}</h4>
        <div><strong>Super Admin:</strong> {{ env('SUPER_ADMIN_EMAIL', 'superadmin@teqinvalley.in') }}</div>
        <div><strong>Admin:</strong> {{ env('ADMIN_EMAIL', 'admin@teqinvalley.in') }}</div>
        <div><strong>Manager:</strong> manager@{{ env('COMPANY_DOMAIN', 'teqinvalley.in') }}</div>
        <div><strong>Employee:</strong> employee@{{ env('COMPANY_DOMAIN', 'teqinvalley.in') }}</div>
        <div style="margin-top: 8px;"><strong>Password:</strong> {{ env('ADMIN_PASSWORD', 'Admin@123456') }} (for all accounts)</div>
        <div style="margin-top: 12px; font-size: 11px; color: #888;">
            This section can be hidden by setting SHOW_DEFAULT_ACCOUNTS=false in .env
        </div>
    </div>
    @endif
</div>
@endsection