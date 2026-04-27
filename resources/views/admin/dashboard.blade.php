@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Hero --}}
    <div class="hero" style="margin-bottom:28px;">
        <div class="hero-text">
            <div class="hero-date">{{ now()->format('l, F j, Y') }}</div>
            <div class="hero-title">Welcome back, {{ explode(' ', auth()->user()->name)[1] }}! 🛡️</div>
            <div class="hero-sub">System Administrator — Full access</div>
        </div>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $totalStudents }}</div>
                <div class="hero-badge-label">Students</div>
            </div>
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $totalTeachers }}</div>
                <div class="hero-badge-label">Teachers</div>
            </div>
        </div>
    </div>

    {{-- Stat tiles --}}
    <div class="grid-4" style="margin-bottom:28px;">
        <div class="stat-tile active-border">
            <div class="stat-icon blue">👥</div>
            <div class="stat-num">{{ $totalStudents }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon green">👨‍🏫</div>
            <div class="stat-num">{{ $totalTeachers }}</div>
            <div class="stat-label">Total Teachers</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon amber">🏫</div>
            <div class="stat-num">{{ $totalClasses }}</div>
            <div class="stat-label">Classes</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon blue">📝</div>
            <div class="stat-num">{{ $totalTests }}</div>
            <div class="stat-label">Tests Created</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon green">📬</div>
            <div class="stat-num">{{ $totalSubmissions }}</div>
            <div class="stat-label">Submissions</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon amber">📁</div>
            <div class="stat-num">{{ $totalMaterials }}</div>
            <div class="stat-label">Materials</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon blue">📢</div>
            <div class="stat-num">{{ $totalNotices }}</div>
            <div class="stat-label">Notices</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon green">🎯</div>
            <div class="stat-num">{{ $overallAvg !== null ? $overallAvg . '%' : '—' }}</div>
            <div class="stat-label">Overall Avg.</div>
        </div>
    </div>

    {{-- Quick access --}}
    <div class="section">
        <div class="section-head">
            <div class="section-title">Quick Access</div>
        </div>
        <div class="grid-4" style="margin-bottom:28px;">

            <a href="{{ route('admin.classes.index') }}" class="admin-quick-card">
                <div style="font-size:28px; margin-bottom:10px;">🏫</div>
                <div class="admin-quick-title">Classes</div>
                <div class="admin-quick-count">{{ $totalClasses }} total</div>
            </a>

            <a href="{{ route('admin.teachers.index') }}" class="admin-quick-card">
                <div style="font-size:28px; margin-bottom:10px;">👨‍🏫</div>
                <div class="admin-quick-title">Teachers</div>
                <div class="admin-quick-count">{{ $totalTeachers }} total</div>
            </a>

            <a href="{{ route('admin.students.index') }}" class="admin-quick-card">
                <div style="font-size:28px; margin-bottom:10px;">👥</div>
                <div class="admin-quick-title">Students</div>
                <div class="admin-quick-count">{{ $totalStudents }} total</div>
            </a>

            <a href="{{ route('admin.notices.index') }}" class="admin-quick-card">
                <div style="font-size:28px; margin-bottom:10px;">📢</div>
                <div class="admin-quick-title">Notices</div>
                <div class="admin-quick-count">{{ $totalNotices }} total</div>
            </a>

        </div>
    </div>

    {{-- Recent registrations + performance --}}
    <div class="bottom-grid" style="margin-bottom:24px;">

        {{-- Recent students --}}
        <div class="card">
            <div class="card-title">
                👥 Recent Students
                <a href="{{ route('admin.students.index') }}" class="see-all">View all →</a>
            </div>
            @if($recentStudents->count() > 0)
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($recentStudents as $student)
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    padding:10px 12px; background:var(--gray-50); border-radius:10px;
                                    border:1px solid var(--gray-100);">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:8px;
                                            background:var(--blue-600); display:flex; align-items:center;
                                            justify-content:center; font-weight:700; font-size:11px;
                                            color:#fff; flex-shrink:0;
                                            font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:var(--blue-900);">
                                        {{ $student->name }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ $student->student_id }} &nbsp;·&nbsp;
                                        {{ $student->studentClass->name ?? '—' }}
                                    </div>
                                </div>
                            </div>
                            <div style="font-size:11.5px; color:var(--gray-400);">
                                {{ $student->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:16px;">
                    <div class="empty-icon">📭</div>
                    <p>No students yet.</p>
                </div>
            @endif
        </div>

        {{-- Recent teachers --}}
        <div class="card">
            <div class="card-title">
                👨‍🏫 Recent Teachers
                <a href="{{ route('admin.teachers.index') }}" class="see-all">View all →</a>
            </div>
            @if($recentTeachers->count() > 0)
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($recentTeachers as $teacher)
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    padding:10px 12px; background:var(--gray-50); border-radius:10px;
                                    border:1px solid var(--gray-100);">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:8px;
                                            background:var(--blue-800); display:flex; align-items:center;
                                            justify-content:center; font-weight:700; font-size:11px;
                                            color:#fff; flex-shrink:0;
                                            font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:var(--blue-900);">
                                        {{ $teacher->name }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ implode(', ', $teacher->teacherProfile?->subjects ?? []) ?: '—' }}
                                    </div>
                                </div>
                            </div>
                            <div style="font-size:11.5px; color:var(--gray-400);">
                                {{ $teacher->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:16px;">
                    <div class="empty-icon">📭</div>
                    <p>No teachers yet.</p>
                </div>
            @endif
        </div>

    </div>

    {{-- Class performance overview --}}
    @if($classPerformance->count() > 0)
        <div class="card">
            <div class="card-title">
                📊 Class Performance Overview
            </div>
            <div style="position:relative; height:240px;">
                <canvas id="adminChart"></canvas>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
@if($classPerformance->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('adminChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! $classPerformance->pluck('name')->toJson() !!},
            datasets: [{
                label: 'Avg. Score (%)',
                data: {!! $classPerformance->pluck('avg')->toJson() !!},
                backgroundColor: {!! $classPerformance->map(fn($c) =>
                    $c['avg'] >= 70 ? 'rgba(22,163,74,0.8)' :
                    ($c['avg'] >= 40 ? 'rgba(37,99,235,0.8)' : 'rgba(220,38,38,0.8)')
                )->toJson() !!},
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y}% average` } }
            },
            scales: {
                y: {
                    beginAtZero: true, max: 100,
                    ticks: { callback: v => v + '%' },
                    grid: { color: 'rgba(0,0,0,0.04)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endif
@endpush