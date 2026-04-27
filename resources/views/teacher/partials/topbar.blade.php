<div class="topbar">
    <div class="topbar-left">
        <button class="hamburger" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="topbar-right">
        <div class="user-chip">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-sub">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>
</div>