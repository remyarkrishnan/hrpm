<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HR System - Teqin Valley')</title>

    <!-- Material Design 3 -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">

    <style>
        :root {
            --mdc-theme-primary: #6750A4;
            --mdc-theme-secondary: #625B71;
            --mdc-theme-surface: #FFFBFE;
            --mdc-theme-on-surface: #1C1B1F;
        }

        body { 
            font-family: 'Roboto', sans-serif; 
            margin: 0; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }

        .auth-wrapper {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
            color: white;
        }

        .brand-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 500;
        }

        .brand-header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .auth-card { 
            background: white; 
            padding: 32px; 
            border-radius: 12px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.1); 
            width: 100%; 
        }

        .auth-title {
            text-align: center;
            margin-bottom: 24px;
            font-size: 20px;
            font-weight: 500;
            color: var(--mdc-theme-on-surface);
        }

        .mdc-text-field { 
            width: 100%; 
            margin: 16px 0; 
        }

        .mdc-button { 
            width: 100%; 
            height: 48px;
            margin: 24px 0 16px 0; 
        }

        .default-accounts {
            background: #f8f9fa;
            padding: 16px;
            border-radius: 8px;
            margin-top: 16px;
            font-size: 12px;
            color: #666;
        }

        .default-accounts h4 {
            margin: 0 0 8px 0;
            font-size: 14px;
            color: #333;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-wrapper">
        <div class="brand-header">
            <h1>Teqin Valley</h1>
            <p>HR & Project Management System</p>
        </div>

        <!-- Alerts -->
        @if($errors->any())
            <div class="alert alert-error">
                @foreach($errors->all() as $error)
                    {{ $error }}@if(!$loop->last)<br>@endif
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
        // Initialize Material Components
        mdc.autoInit();

        // Initialize text fields
        const textFields = document.querySelectorAll('.mdc-text-field');
        textFields.forEach(textField => {
            new mdc.textField.MDCTextField(textField);
        });

        // Initialize buttons
        const buttons = document.querySelectorAll('.mdc-button');
        buttons.forEach(button => {
            new mdc.ripple.MDCRipple(button);
        });
    </script>

    @stack('scripts')
</body>
</html>