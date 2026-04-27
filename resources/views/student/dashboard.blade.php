@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- HERO -->
    <div class="hero">
        <div class="hero-text">
            <div class="hero-date">{{ now()->format('l, F j, Y') }}</div>
            <div class="hero-title">Welcome back, {{ explode(' ', $user->name)[0] }}! 👋</div>
            <div class="hero-sub">Class: {{ $user->class_id }} &nbsp;·&nbsp; ID: {{ $user->student_id }}</div>
        </div>
        <div class="hero-badge">
            <div class="hero-badge-num">{{ $activeCoursesCount ?? 0 }}</div>
            <div class="hero-badge-label">Active Courses</div>
        </div>
    </div>

    <!-- STAT TILES -->
    <div class="section">
        <div class="grid-4">
            <div class="stat-tile active-border">
                <div class="stat-icon blue">📝</div>
                <div class="stat-num">{{ $availableTestsCount ?? 0 }}</div>
                <div class="stat-label">Available Tests</div>
            </div>
            <div class="stat-tile">
                <div class="stat-icon green">✅</div>
                <div class="stat-num">{{ $completedTestsCount ?? 0 }}</div>
                <div class="stat-label">Tests Completed</div>
            </div>
            <div class="stat-tile">
                <div class="stat-icon amber">📁</div>
                <div class="stat-num">{{ $materialsCount ?? 0 }}</div>
                <div class="stat-label">Study Materials</div>
            </div>
            <div class="stat-tile">
                <div class="stat-icon blue">📈</div>
                <div class="stat-num">{{ $avgPerformance ?? '—' }}%</div>
                <div class="stat-label">Avg. Performance</div>
            </div>
        </div>
    </div>

    <!-- QUICK ACCESS MODULES -->
    <div class="section">
        <div class="section-head">
            <div class="section-title">Quick Access</div>
        </div>
        <div class="grid-2">

            <!-- Take a Test -->
            <div class="module-card">
                <div class="module-card-head">
                    <div class="module-icon-wrap blue">📝</div>
                    @if(!empty($availableTestsCount) && $availableTestsCount > 0)
                        <span class="module-badge badge-green">{{ $availableTestsCount }} Available</span>
                    @else
                        <span class="module-badge badge-gray">No Active Test</span>
                    @endif
                </div>
                <div>
                    <div class="module-title">Take a Test</div>
                    <div class="module-desc">
                        @if(!empty($availableTestsCount) && $availableTestsCount > 0)
                            You have {{ $availableTestsCount }} test(s) ready. Click below to begin.
                        @else
                            No tests have been uploaded by your teacher yet. Check back later.
                        @endif
                    </div>
                </div>
                @if(!empty($availableTestsCount) && $availableTestsCount > 0)
                    <a href="{{ route('student.exam') }}" class="module-btn">▶ Start Test</a>
                @else
                    <button class="module-btn outline" disabled>No Tests Yet</button>
                @endif
            </div>

            <!-- View Results -->
            <div class="module-card">
                <div class="module-card-head">
                    <div class="module-icon-wrap green">📊</div>
                    @if(!empty($completedTestsCount) && $completedTestsCount > 0)
                        <span class="module-badge badge-blue">{{ $completedTestsCount }} Results</span>
                    @else
                        <span class="module-badge badge-gray">No Results</span>
                    @endif
                </div>
                <div>
                    <div class="module-title">View Results</div>
                    <div class="module-desc">
                        @if(!empty($completedTestsCount) && $completedTestsCount > 0)
                            Your graded test results are ready. Review your scores and feedback.
                        @else
                            No results available yet. Complete a test to see your scores here.
                        @endif
                    </div>
                </div>
                <a href="{{ route('student.results') }}" class="module-btn">📄 See Results</a>
            </div>

            <!-- Study Materials -->
            <div class="module-card">
                <div class="module-card-head">
                    <div class="module-icon-wrap amber">📁</div>
                    @if(!empty($materialsCount) && $materialsCount > 0)
                        <span class="module-badge badge-amber">{{ $materialsCount }} Files</span>
                    @else
                        <span class="module-badge badge-gray">No Files</span>
                    @endif
                </div>
                <div>
                    <div class="module-title">Study Materials</div>
                    <div class="module-desc">
                        @if(!empty($materialsCount) && $materialsCount > 0)
                            Your teachers have shared learning resources. Download and study.
                        @else
                            No materials uploaded yet. Your teachers will post resources here.
                        @endif
                    </div>
                </div>
                <a href="{{ route('student.materials') }}" class="module-btn">📥 Browse Files</a>
            </div>

            <!-- Academic Performance -->
            <div class="module-card">
                <div class="module-card-head">
                    <div class="module-icon-wrap indigo">📈</div>
                    <span class="module-badge badge-blue">Live</span>
                </div>
                <div>
                    <div class="module-title">Academic Performance</div>
                    <div class="module-desc">Track your subject scores based on completed tests.</div>
                </div>
                <a href="{{ route('student.performance') }}" class="module-btn">📈 View Details</a>
            </div>

        </div>
    </div>

    <!-- BOTTOM: Performance + Notices -->
    <div class="grid-2">

        <!-- Performance preview -->
        <div class="card">
            <div class="card-title">
                Overall Performance
                <a href="{{ route('student.performance') }}" class="see-all">View all →</a>
            </div>
            @include('student.partials.performance-bars', ['performance' => $performance ?? []])
        </div>

        <!-- Notices -->
        <div class="card">
            <div class="card-title">
                Daily Notices
                <a href="#" class="see-all">See all →</a>
            </div>
            @include('student.partials.notices', ['notices' => $notices ?? []])
        </div>

    </div>

@endsection