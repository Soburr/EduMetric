<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – JGSGS Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🛡️</div>
        <div class="brand-text">
            JGSGS
            <span>Admin Panel</span>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
           class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Management</div>
        <a href="{{ route('admin.classes.index') }}"
           class="nav-item {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
            <span class="nav-icon">🏫</span> Classes
        </a>
        <a href="{{ route('admin.teachers.index') }}"
           class="nav-item {{ request()->routeIs('admin.teachers.*') ? 'active' : '' }}">
            <span class="nav-icon">👨‍🏫</span> Teachers
        </a>
        <a href="{{ route('admin.students.index') }}"
           class="nav-item {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Students
        </a>
        <a href="{{ route('admin.notices.index') }}"
           class="nav-item {{ request()->routeIs('admin.notices.*') ? 'active' : '' }}">
            <span class="nav-icon">📢</span> Notices
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Account</div>
        <a href="{{ route('admin.profile') }}"
           class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> My Profile
        </a>
                <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="logout-btn" type="submit">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>

    <div class="sidebar-footer">
        <!-- <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button class="logout-btn" type="submit">
                <span>🚪</span> Logout
            </button>
        </form> -->
    </div>
</aside>

<!-- Main -->
<div class="main">

    <!-- Topbar -->
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
        </div>
        <div class="topbar-right">
            <div class="user-chip">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-sub">Administrator</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page content -->
    <div class="page">
        @if(session('success'))
            <div style="background:#dcfce7; color:#15803d; padding:12px 16px;
                        border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background:#fee2e2; color:#dc2626; padding:12px 16px;
                        border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
                ❌ {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </div>

</div>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('open');
        document.getElementById('overlay').classList.toggle('open');
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('open');
        document.getElementById('overlay').classList.remove('open');
    }
</script>
@stack('scripts')
</body>
</html>