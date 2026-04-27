<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">🎓</div>
        <div class="brand-text">
            JGSGS
            <span>Teacher Dashboard</span>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-label">Main</div>

        <a href="{{ route('teacher.dashboard') }}"
           class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">🏠</span> Dashboard
        </a>

        <a href="{{ route('teacher.exams.index') }}"
           class="nav-item {{ request()->routeIs('teacher.exams.*') ? 'active' : '' }}">
            <span class="nav-icon">📝</span> Create a Test
        </a>

        <a href="{{ route('teacher.materials.index') }}"
           class="nav-item {{ request()->routeIs('teacher.materials.*') ? 'active' : '' }}">
            <span class="nav-icon">📁</span> Study Materials
        </a>

        <a href="{{ route('teacher.notices.index') }}"
           class="nav-item {{ request()->routeIs('teacher.notices.*') ? 'active' : '' }}">
            <span class="nav-icon">🔕</span> Notices
        </a>


    </div>

    <div class="nav-section">
        <div class="nav-label">Students</div>

        <a href="{{ route('teacher.students.index') }}"
           class="nav-item {{ request()->routeIs('teacher.students.index') ? 'active' : '' }}">
            <span class="nav-icon">🧑‍🎓</span> My Students
        </a>

        <a href="{{ route('teacher.performance.index') }}"
           class="nav-item {{ request()->routeIs('teacher.performance.*') ? 'active' : '' }}">
            <span class="nav-icon">📈</span> Performance
        </a>
    </div>

    <div class="nav-section">
        <div class="nav-label">Account</div>

        <a href="{{ route('teacher.profile') }}"
           class="nav-item {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
            <span class="nav-icon">👤</span> My Profile
        </a>

        <form method="POST" action="{{ route('teacher.logout') }}">
            @csrf
            <button class="logout-btn" type="submit">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>

    <div class="sidebar-footer">
        <!-- <form method="POST" action="{{ route('student.logout') }}">
            @csrf
            <button class="logout-btn" type="submit">
                <span>🚪</span> Logout
            </button>
        </form> -->
    </div>
</aside>