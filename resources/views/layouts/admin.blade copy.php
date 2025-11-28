<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard - ' . config('app.name'))</title>

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
            /* RTL: sidebar on the right */
            right: 0;
            left: auto;
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
            /* RTL: use right border instead of left */
            border-right: 3px solid transparent;
            border-left: none;
        }

        .nav-item:hover,
        .nav-item.active {
            background: rgba(103, 80, 164, 0.08);
            border-right-color: var(--primary-color);
            color: var(--primary-color);
        }

        .nav-item i {
            font-size: 20px;
            vertical-align: middle;
            /* RTL: icon spacing on the left side of text */
            margin-left: 12px;
            margin-right: 0;
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
            /* RTL: push content from right side */
            margin-right: var(--sidebar-width);
            margin-left: 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            background: white;
            padding: 16px 24px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
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
            /* RTL: border on the right */
            border-right: 4px solid #4caf50;
            border-left: none;
        }

        .alert-error {
            background: #ffebee;
            color: #c62828;
            border-right: 4px solid #f44336;
            border-left: none;
        }

        /* Mobile menu button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 20px;
            /* RTL: button on the right */
            right: 20px;
            left: auto;
            z-index: 1001;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .sidebar {
                /* RTL: hide by sliding to the right */
                transform: translateX(100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-right: 0;
            }

            .mobile-menu-btn {
                display: flex;
                align-items: center;
                justify-content: center;
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

    <!-- Sidebar Navigation -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h1>{{ env('COMPANY_NAME', 'Teqin Valley') }}</h1>
            <p>Construction Management System</p>
        </div>

        <div class="sidebar-nav">
            <!-- Dashboard Section -->
            <div class="nav-section">
                <div class="nav-section-title">Dashboard</div>
                <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="material-icons">dashboard</i>
                    <span>Overview</span>
                </a>
            </div>

            <!-- HR Management Section -->
            <div class="nav-section">
                <div class="nav-section-title">HR Management</div>

                <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="material-icons">people</i>
                    <span>Employee Management</span>
                </a>

                <a href="{{ route('admin.attendance.index') }}" class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                    <i class="material-icons">schedule</i>
                    <span>Attendance Management</span>
                </a>

                <a href="{{ route('admin.leaves.index') }}" class="nav-item {{ request()->routeIs('admin.leaves.*') ? 'active' : '' }}">
                    <i class="material-icons">event_available</i>
                    <span>Leave Management</span>
                </a>

                <a href="{{ route('admin.overtime.index') }}" class="nav-item {{ request()->routeIs('admin.overtime.*') ? 'active' : '' }}">
                    <i class="material-icons">more_time</i>
                    <span>Overtime Management</span>
                </a>

                <a href="{{ route('admin.shifts.index') }}" class="nav-item {{ request()->routeIs('admin.shifts.*') ? 'active' : '' }}">
                    <i class="material-icons">access_time</i>
                    <span>Shift Management</span>
                </a>

                <a href="{{ route('admin.approvals.index') }}" class="nav-item {{ request()->routeIs('admin.approvals.*') ? 'active' : '' }}">
                    <i class="material-icons">approval</i>
                    <span>Approvals</span>
                </a>
            </div>

            <!-- Project Management Section -->
            <div class="nav-section">
                <div class="nav-section-title">Project Management</div>

                <a href="{{ route('admin.projects.index') }}" class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                    <i class="material-icons">engineering</i>
                    <span>Projects</span>
                </a>

                <a href="{{ route('admin.project-locations.index') }}" class="nav-item {{ request()->routeIs('admin.project-locations.*') ? 'active' : '' }}">
                    <i class="material-icons">place</i>
                    <span>Project Locations</span>
                </a>

                <a href="{{ route('admin.planning.index') }}" class="nav-item {{ request()->routeIs('admin.planning.*') ? 'active' : '' }}" style="display:none">
                    <i class="material-icons">timeline</i>
                    <span>Project Planning</span>
                </a>

                <a href="{{ route('admin.resources.index') }}" class="nav-item {{ request()->routeIs('admin.resources.*') ? 'active' : '' }}" style="display:none">
                    <i class="material-icons">assignment_ind</i>
                    <span>Resource Allocation</span>
                </a>
            </div>

            <!-- Reports Section -->
            <div class="nav-section">
                <div class="nav-section-title">Reports</div>

                <a style="display:none" href="{{ route('admin.hr-reports.index') }}" class="nav-item {{ request()->routeIs('admin.hr-reports.*') ? 'active' : '' }}">
                    <i class="material-icons">assessment</i>
                    <span>HR Reports</span>
                </a>

                <a href="{{ route('admin.project-reports.index') }}" class="nav-item {{ request()->routeIs('admin.project-reports.*') ? 'active' : '' }}">
                    <i class="material-icons">pie_chart</i>
                    <span>Project Reports</span>
                </a>
            </div>

            <!-- Settings Section -->
            <div class="nav-section">
                <div class="nav-section-title">Settings</div>
                <a href="{{ route('admin.profile.edit') }}" class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
                    <i class="material-icons">person</i>
                    <span>Profile</span>
                </a>
                <a style="display:none" href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="material-icons">settings</i>
                    <span>System Settings</span>
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
                    <p>{{ auth()->user()->getRoleNames()->first() ?? 'User' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="material-icons" style="font-size: 16px; vertical-align: middle; margin-left: 8px;">logout</i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h1>@yield('page-title', 'Dashboard')</h1>
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

            if (
                window.innerWidth <= 768 &&
                !sidebar.contains(e.target) &&
                !menuBtn.contains(e.target) &&
                sidebar.classList.contains('active')
            ) {
                sidebar.classList.remove('active');
            }
        });

        // Active navigation highlighting (fallback to route-based class)
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');

            navItems.forEach(item => {
                if (item.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
