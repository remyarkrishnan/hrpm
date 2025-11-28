<!DOCTYPE html>
<html lang="en" >
    @php
        $locale = session('locale', config('app.locale'));
        app()->setLocale($locale);
    @endphp
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.Dashboard - :app', ['app' => config('app.name')]))</title>

    <!-- Material Design 3 -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6750A4;
            --primary-light: #7B68C8;
            --primary-dark: #5A4A94;
            --secondary-color: #625B71;
            --surface-color: #FFFBFE;
            --on-surface: #1C1B1F;
            --sidebar-width: 280px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Roboto', sans-serif; 
            background: #f5f5f5;
            display: flex;
            min-height: 100vh;
        }
        .language-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 24px;
            }

            .language-dropdown button {
            background: #fff;
            border: 1px solid #eee;
            border-radius: 25px;
            padding: 8px 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            transition: box-shadow 0.2s;
            }

            .language-dropdown .lang-flag {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            vertical-align: middle;
            }

            .language-dropdown .arrow {
            font-size: 14px;
            margin-left: 2px;
            }

            .language-dropdown .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 40px;
            background: #fff;
            min-width: 150px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            border-radius: 8px;
            z-index: 9999;
            overflow: hidden;
            }

            .language-dropdown .dropdown-menu div {
            padding: 12px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            white-space: nowrap;
            transition: background 0.2s;
            }

            .language-dropdown .dropdown-menu div:hover {
            background: #f6f6f6;
            }
        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 24px 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h1 {
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .sidebar-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            padding: 16px 0;
        }

        .nav-section {
            margin-bottom: 24px;
        }

        .nav-section-title {
            padding: 8px 20px;
            font-size: 12px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .nav-item {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(103, 80, 164, 0.08);
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .nav-item i {
            margin-right: 12px;
            font-size: 20px;
            vertical-align: middle;
        }

        .nav-item span {
            vertical-align: middle;
            font-weight: 500;
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid #eee;
            background: #f9f9f9;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 500;
        }

        .user-details h4 {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
        }

        .user-details p {
            font-size: 12px;
            color: #666;
        }

        .logout-btn {
            width: 100%;
            padding: 8px 16px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.2s;
        }

        .logout-btn:hover {
            background: var(--primary-dark);
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            background: white;
            padding: 16px 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: between;
            align-items: center;
            position: relative;
        }

        .page-title h1 {
            font-size: 24px;
            font-weight: 500;
            color: var(--on-surface);
        }

        .content {
            flex: 1;
            padding: 24px;
            overflow-y: auto;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        .alert {
            padding: 16px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: #e8f5e8;
            color: #2e7d32;
            border-left: 4px solid #4caf50;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }

        .language-dropdown {
            position: absolute;
            top: 16px;
            right: 32px; /* LTR: right corner */
            z-index: 20;
        }

        body.rtl .language-dropdown {
            right: auto;
            left: 32px; /* RTL: left corner */
        }

        body.rtl {
            direction: rtl;
        }

        body.rtl .sidebar {
            right: 0;
            left: auto;
        }

        body.rtl .main-content {
            margin-right: var(--sidebar-width);
            margin-left: 0;
        }

        body.rtl .nav-item {
            border-right: 3px solid transparent;
            border-left: none;
        }

        body.rtl .nav-item:hover,
        body.rtl .nav-item.active {
            border-right-color: var(--primary-color);
            border-left-color: transparent;
        }

        body.rtl .nav-item i {
            margin-left: 12px;
            margin-right: 0;
        }

        body.rtl .alert-success,
        body.rtl .alert-error {
            border-right: 4px solid;
            border-left: none;
        }

        body.rtl .mobile-menu-btn {
            right: 20px;
            left: auto;
        }

        /* Mobile sidebar RTL */
        @media (max-width: 768px) {
            body.rtl .sidebar {
                transform: translateX(100%);
            }

            body.rtl .sidebar.active {
                transform: translateX(0);
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <i class="material-icons">menu</i>
    </button>

    <!-- Sidebar Navigation - Complete SRS Implementation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h1>{{ env('COMPANY_NAME', 'Teqin Valley') }}</h1>
            <p>{{ __('messages.Construction Management System') }}</p>
        </div>

        <div class="sidebar-nav">
            <!-- Dashboard Section -->
            <div class="nav-section">
                <div class="nav-section-title">{{ __('messages.Dashboard') }}</div>
                <a href="{{ route('employee.dashboard') }}" class="nav-item {{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                    <i class="material-icons">dashboard</i>
                    <span>{{ __('messages.Overview') }}</span>
                </a>
            
                <!-- Employee Management (Implemented) -->
                <a href="{{ route('employee.users.index') }}" class="nav-item {{ request()->routeIs('employee.users.*') ? 'active' : '' }}">
                    <i class="material-icons">people</i>
                    <span>{{ __('messages.Employee Details') }}</span>
                </a>

                <!-- Attendance Management (SRS 4.4) -->
                <a href="{{ route('employee.attendance.index') }}" class="nav-item {{ request()->routeIs('employee.attendance.*') ? 'active' : '' }}">
                    <i class="material-icons">schedule</i>
                    <span>{{ __('messages.Attendance') }}</span>
                </a>

                <!-- Leave/Vacation Management (SRS 4.2) -->
                

                <!-- Overtime Management -->
                <a href="{{ route('employee.overtime.index') }}" class="nav-item {{ request()->routeIs('employee.overtime.*') ? 'active' : '' }}">
                    <i class="material-icons">more_time</i>
                    <span>{{ __('messages.Overtime') }}</span>
                </a>

               
            </div>

            <!-- Settings Section -->
            <div class="nav-section">
                <div class="nav-section-title">{{ __('messages.Esurface') }}</div>
                <a href="{{ route('employee.loans.index') }}" class="nav-item {{ request()->routeIs('employee.loans') ? 'active' : '' }}">
                    <i class="material-icons">person</i>
                    <span>{{ __('messages.Loan Requests') }}</span>
                </a>
               <a href="{{ route('employee.leaves.index') }}" class="nav-item {{ request()->routeIs('employee.leaves.*') ? 'active' : '' }}">
                    <i class="material-icons">event_available</i>
                    <span>{{ __('messages.Leave Requests') }}</span>
                </a>
                <a href="{{ route('employee.trainings.index') }}" class="nav-item {{ request()->routeIs('employee.trainings.*') ? 'active' : '' }}">
                    <i class="material-icons">event_available</i>
                    <span>{{ __('messages.Training Requests') }}</span>
                </a>
                <a href="{{ route('employee.documents.index') }}" class="nav-item {{ request()->routeIs('employee.documents.*') ? 'active' : '' }}">
                    <i class="material-icons">event_available</i>
                    <span>{{ __('messages.Document Request') }}</span>
                </a>

                  <a href="{{ route('employee.payslip.export') }}" class="nav-item {{ request()->routeIs('employee.payslip.*') ? 'active' : '' }}">
                    <i class="material-icons">event_available</i>
                    <span>{{ __('messages.Generate Payslip') }}</span>
                </a>
            </div>
			 <!-- Settings Section -->
            <div class="nav-section" >
                <div class="nav-section-title">{{ __('messages.Settings') }}</div>
                <a href="{{ route('employee.profile.edit') }}" class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="material-icons">person</i>
                    <span>{{ __('messages.Profile') }}</span>
                </a>
               
            </div>
			
			
        </div>

        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-details">
                    <h4>{{ auth()->user()->name }}</h4>
                    <p>{{ auth()->user()->getRoleNames()->first() ?? __('messages.User') }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-right: 8px;">logout</i>
                    {{ __('messages.Logout') }}
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h1>@yield('page-title', __('messages.Dashboard'))</h1>
            </div>
            <div class="language-dropdown">
                <button id="lang-btn" onclick="toggleDropdown()">
                    <img src="/images/flags/us.jpg" id="current-flag" alt="English" class="lang-flag">
                    <span id="current-lang">EN</span>
                    <span class="arrow">&#9660;</span>
                </button>
                <div class="dropdown-menu" id="lang-menu">
                    <div onclick="setLanguage('en')">
                    <img src="/images/flags/us.jpg" class="lang-flag" alt="English"> EN
                    </div>
                    <div onclick="setLanguage('ar')">
                    <img src="/images/flags/sa.jpg" class="lang-flag" alt="Arabic"> العربية
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="material-icons">check_circle</i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="material-icons">error</i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <i class="material-icons">error</i>
                    <div>
                        @foreach($errors->all() as $error)
                            {{ $error }}@if(!$loop->last)<br>@endif
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <script>
        // Initialize Material Components
        mdc.autoInit();

        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const menuBtn = document.querySelector('.mobile-menu-btn');

            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !menuBtn.contains(e.target) && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });

        // Active navigation highlighting
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });

        function toggleDropdown() {
        document.getElementById('lang-menu').style.display =
            document.getElementById('lang-menu').style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown if clicking outside
        document.addEventListener('click', function(e) {
        if (!e.target.closest('.language-dropdown')) {
            document.getElementById('lang-menu').style.display = 'none';
        }
        });

        // Change language and flag/icon when a language is selected
        function applyLanguage(lang) {
            // only update layout + UI, NO fetch, NO reload
            if (lang === 'ar') {
                document.documentElement.setAttribute('lang', 'ar');
                document.documentElement.setAttribute('dir', 'rtl');
                document.body.classList.add('rtl');
                document.getElementById('current-flag').src = '/images/flags/sa.jpg';
                document.getElementById('current-lang').textContent = 'العربية';
            } else {
                document.documentElement.setAttribute('lang', 'en');
                document.documentElement.setAttribute('dir', 'ltr');
                document.body.classList.remove('rtl');
                document.getElementById('current-flag').src = '/images/flags/us.jpg';
                document.getElementById('current-lang').textContent = 'EN';
            }
        }

        // called when user clicks in dropdown
        function setLanguage(lang) {
            localStorage.setItem('language', lang);
            document.getElementById('lang-menu').style.display = 'none';

            applyLanguage(lang); // update layout immediately

            fetch("{{ route('change.language') }}", {   // use your actual route name
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                },
                body: JSON.stringify({ locale: lang })
            }).then(response => {
                if (response.ok) {
                    window.location.reload(); // reload ONCE after user change
                } else {
                    console.error('Failed to change language');
                }
            });
        }

        // on initial load, just apply from localStorage
        document.addEventListener('DOMContentLoaded', () => {
            const lang = localStorage.getItem('language') || 'en';
            applyLanguage(lang);   // no fetch, no reload
        });
    </script>

    @stack('scripts')
</body>
</html>
