@extends('layouts.teacher.app')

@section('title', 'Dashboard')

@section('content')

    {{-- ── HERO BANNER ── --}}
    <div class="hero" style="margin-bottom:28px;">
        <div class="hero-text">
            <div class="hero-date">{{ now()->format('l, F j, Y') }}</div>
            <div class="hero-title">Welcome back, {{ explode(' ', auth()->user()->name)[0] }}! 👋</div>
            <div class="hero-sub">
                @if($profile?->is_class_teacher && $profile->assignedClass)
                    Class Teacher — {{ $profile->assignedClass->name }}
                    &nbsp;·&nbsp;
                @endif
                {{ implode(', ', $profile?->subjects ?? []) }}
            </div>
        </div>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <div class="hero-badge">
                <div class="hero-badge-num">{{ $totalTests }}</div>
                <div class="hero-badge-label">Tests Created</div>
            </div>
            @if($profile?->is_class_teacher)
                <div class="hero-badge">
                    <div class="hero-badge-num">{{ $classStudentCount }}</div>
                    <div class="hero-badge-label">My Students</div>
                </div>
            @endif
        </div>
    </div>

    {{-- ── STAT TILES ── --}}
    <div class="grid-4" style="margin-bottom:28px;">
        <div class="stat-tile active-border">
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
            <div class="stat-label">Notices Posted</div>
        </div>
    </div>

    {{-- ── QUICK ACCESS ── --}}
    <div class="section">
        <div class="section-head">
            <div class="section-title">Quick Access</div>
        </div>
        <div class="grid-2" style="margin-bottom:28px;">

            <div class="module-card" onclick="window.location='{{ route('teacher.exams.create') }}'">
                <div class="module-card-head">
                    <div class="module-icon-wrap blue">📝</div>
                    <span class="module-badge badge-blue">New</span>
                </div>
                <div>
                    <div class="module-title">Create a Test</div>
                    <div class="module-desc">Set new questions for any of your classes.</div>
                </div>
                <a href="{{ route('teacher.exams.create') }}" class="module-btn">▶ Start</a>
            </div>

            <div class="module-card" onclick="window.location='{{ route('teacher.exams.index') }}'">
                <div class="module-card-head">
                    <div class="module-icon-wrap green">📋</div>
                    <span class="module-badge {{ $activeTests > 0 ? 'badge-green' : 'badge-gray' }}">
                        {{ $activeTests }} Active
                    </span>
                </div>
                <div>
                    <div class="module-title">My Exams</div>
                    <div class="module-desc">View, activate, and manage your existing tests.</div>
                </div>
                <a href="{{ route('teacher.exams.index') }}" class="module-btn">📋 View</a>
            </div>

            <div class="module-card" onclick="window.location='{{ route('teacher.materials.index') }}'">
                <div class="module-card-head">
                    <div class="module-icon-wrap amber">📁</div>
                    <span class="module-badge badge-amber">{{ $totalMaterials }} Files</span>
                </div>
                <div>
                    <div class="module-title">Study Materials</div>
                    <div class="module-desc">Upload and manage learning resources for your classes.</div>
                </div>
                <a href="{{ route('teacher.materials.index') }}" class="module-btn">📁 Manage</a>
            </div>

            <div class="module-card" onclick="window.location='{{ route('teacher.students.index') }}'">
                <div class="module-card-head">
                    <div class="module-icon-wrap indigo">👥</div>
                    @if($profile?->is_class_teacher)
                        <span class="module-badge badge-blue">{{ $classStudentCount }} Students</span>
                    @else
                        <span class="module-badge badge-gray">Subject View</span>
                    @endif
                </div>
                <div>
                    <div class="module-title">My Students</div>
                    <div class="module-desc">
                        @if($profile?->is_class_teacher)
                            View all students in {{ $profile->assignedClass->name ?? 'your class' }}.
                        @else
                            View students who have taken your tests.
                        @endif
                    </div>
                </div>
                <a href="{{ route('teacher.students.index') }}" class="module-btn">👥 View</a>
            </div>

        </div>
    </div>

    {{-- ── RECENT ACTIVITY + TESTS ── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:24px;"
         class="bottom-grid">

        {{-- Recent submissions --}}
        <div class="card">
            <div class="card-title">
                📬 Recent Submissions
                <a href="{{ route('teacher.exams.index') }}" class="see-all">View all →</a>
            </div>
            @if($recentSubmissions->count() > 0)
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($recentSubmissions as $submission)
                        <div style="display:flex; align-items:center; justify-content:space-between;
                                    padding:10px 12px; background:var(--gray-50); border-radius:10px;
                                    border:1px solid var(--gray-100); gap:10px; flex-wrap:wrap;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:32px; height:32px; border-radius:8px;
                                            background:var(--blue-600); display:flex; align-items:center;
                                            justify-content:center; font-weight:700; font-size:11px;
                                            color:#fff; flex-shrink:0;
                                            font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ strtoupper(substr($submission->student->name ?? '?', 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:var(--blue-900);">
                                        {{ $submission->student->name ?? '—' }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ $submission->test->title ?? '—' }}
                                    </div>
                                </div>
                            </div>
                            <div style="text-align:right; flex-shrink:0;">
                                <div style="font-weight:700; font-size:13.5px;
                                    color:{{ $submission->percentage >= 70 ? 'var(--green)' : ($submission->percentage >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                                    {{ $submission->percentage }}%
                                </div>
                                <div style="font-size:11px; color:var(--gray-400); margin-top:1px;">
                                    {{ $submission->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:20px;">
                    <div class="empty-icon" style="font-size:28px;">📭</div>
                    <p style="font-size:13px;">No submissions yet.</p>
                </div>
            @endif
        </div>

        {{-- Recent tests --}}
        <div class="card">
            <div class="card-title">
                📝 Recent Tests
                <a href="{{ route('teacher.exams.index') }}" class="see-all">View all →</a>
            </div>
            @if($recentTests->count() > 0)
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($recentTests as $test)
                        <a href="{{ route('teacher.exams.submissions', $test->id) }}"
                           style="text-decoration:none;">
                            <div style="display:flex; align-items:center; justify-content:space-between;
                                        padding:10px 12px; background:var(--gray-50); border-radius:10px;
                                        border:1px solid var(--gray-100); gap:10px; flex-wrap:wrap;
                                        transition:background .2s;"
                                 onmouseover="this.style.background='var(--blue-50)'"
                                 onmouseout="this.style.background='var(--gray-50)'">
                                <div>
                                    <div style="font-size:13px; font-weight:600; color:var(--blue-900);">
                                        {{ $test->title }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ $test->subject }} &nbsp;·&nbsp;
                                        {{ $test->schoolClass->name ?? '—' }} &nbsp;·&nbsp;
                                        {{ $test->questions_count }} Qs
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
                                    <span style="font-size:12px; color:var(--gray-400);">
                                        {{ $test->submissions_count }} submitted
                                    </span>
                                    <span class="module-badge {{ $test->is_active ? 'badge-green' : 'badge-gray' }}">
                                        {{ $test->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:20px;">
                    <div class="empty-icon" style="font-size:28px;">📭</div>
                    <p style="font-size:13px;">No tests created yet.</p>
                </div>
            @endif
        </div>

    </div>

    {{-- ── PERFORMANCE CHART + NOTICES ── --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;" class="bottom-grid">

        {{-- Performance snapshot --}}
        <div class="card">
            <div class="card-title">
                📊 Performance Snapshot
                <a href="{{ route('teacher.performance.index') }}" class="see-all">Full view →</a>
            </div>
            @if($classComparison->count() > 0)
                <div style="position:relative; height:200px;">
                    <canvas id="dashChart"></canvas>
                </div>
            @else
                <div class="empty-state" style="padding:20px;">
                    <div class="empty-icon" style="font-size:28px;">📊</div>
                    <p style="font-size:13px;">No performance data yet.</p>
                </div>
            @endif
        </div>

        {{-- Recent notices --}}
        <div class="card">
            <div class="card-title">
                📢 Recent Notices
                <a href="{{ route('teacher.notices.index') }}" class="see-all">See all →</a>
            </div>
            @if($recentNotices->count() > 0)
                <div style="display:flex; flex-direction:column; gap:10px;">
                    @foreach($recentNotices as $notice)
                        <div style="padding:12px 14px; background:var(--gray-50); border-radius:10px;
                                    border-left:3px solid {{ $notice->class_id ? 'var(--blue-500)' : 'var(--amber)' }};
                                    border-top:1px solid var(--gray-100);
                                    border-right:1px solid var(--gray-100);
                                    border-bottom:1px solid var(--gray-100);">
                            <div style="font-size:13px; font-weight:600; color:var(--blue-900); margin-bottom:3px;">
                                {{ $notice->title }}
                            </div>
                            <div style="font-size:12px; color:var(--gray-500);
                                        white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ Str::limit($notice->content, 60) }}
                            </div>
                            <div style="font-size:11px; color:var(--gray-400); margin-top:5px;">
                                {{ $notice->class_id ? ($notice->schoolClass->name ?? 'Class') : '🏫 School-wide' }}
                                &nbsp;·&nbsp;
                                {{ $notice->created_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:14px;">
                    <a href="{{ route('teacher.notices.index') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px;
                              background:var(--blue-600); color:#fff; border-radius:9px;
                              font-size:13px; font-weight:600; text-decoration:none;">
                        📢 Post New Notice
                    </a>
                </div>
            @else
                <div class="empty-state" style="padding:20px;">
                    <div class="empty-icon" style="font-size:28px;">📭</div>
                    <p style="font-size:13px;">No notices posted yet.</p>
                </div>
                <div style="margin-top:14px;">
                    <a href="{{ route('teacher.notices.index') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:9px 18px;
                              background:var(--blue-600); color:#fff; border-radius:9px;
                              font-size:13px; font-weight:600; text-decoration:none;">
                        📢 Post First Notice
                    </a>
                </div>
            @endif
        </div>

    </div>

@endsection

@push('styles')
<style>
    @media (max-width: 768px) {
        .bottom-grid { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 640px) {
        .grid-4 { grid-template-columns: repeat(2, 1fr) !important; }
        .grid-2 { grid-template-columns: 1fr !important; }
    }
</style>
@endpush

@push('scripts')
@if($classComparison->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('dashChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! $classComparison->pluck('name')->toJson() !!},
            datasets: [{
                label: 'Avg. Score (%)',
                data: {!! $classComparison->pluck('avg')->toJson() !!},
                backgroundColor: {!! $classComparison->map(fn($c) =>
                    $c['avg'] >= 70 ? 'rgba(22,163,74,0.8)' :
                    ($c['avg'] >= 40 ? 'rgba(37,99,235,0.8)' : 'rgba(220,38,38,0.8)')
                )->toJson() !!},
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ` ${ctx.parsed.y}% avg` } }
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