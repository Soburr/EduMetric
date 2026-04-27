@extends('layouts.app')

@section('title', 'Academic Performance')

@section('content')

    <div class="section-head" style="margin-bottom:20px;">
        <div class="section-title">📈 Academic Performance</div>
    </div>

    <div class="grid-2">

        <!-- Per-subject breakdown -->
        <div class="card">
            <div class="card-title">Subject Scores</div>
            @include('student.partials.performance-bars', ['performance' => $performance ?? []])
        </div>

        <!-- Summary stats -->
        <div class="card">
            <div class="card-title">Summary</div>
            <div style="display:flex; flex-direction:column; gap:14px;">

                <div class="stat-tile">
                    <div class="stat-icon blue">🏆</div>
                    <div class="stat-num">{{ $avgPerformance ?? '—' }}%</div>
                    <div class="stat-label">Overall Average</div>
                </div>

                <div class="stat-tile">
                    <div class="stat-icon green">✅</div>
                    <div class="stat-num">{{ $completedTestsCount ?? 0 }}</div>
                    <div class="stat-label">Tests Taken</div>
                </div>

                <div class="stat-tile">
                    <div class="stat-icon amber">📊</div>
                    <div class="stat-num">{{ $bestSubject ?? '—' }}</div>
                    <div class="stat-label">Best Subject</div>
                </div>

            </div>
        </div>

    </div>

    <!-- Detailed per-test history -->
    @if(isset($results) && count($results) > 0)
        <div class="card" style="margin-top:20px;">
            <div class="card-title">Test History</div>
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:12px 16px; text-align:left; font-weight:600; color:var(--blue-900);">Subject</th>
                        <th style="padding:12px 16px; text-align:left; font-weight:600; color:var(--blue-900);">Test</th>
                        <th style="padding:12px 16px; text-align:center; font-weight:600; color:var(--blue-900);">Score</th>
                        <th style="padding:12px 16px; text-align:left; font-weight:600; color:var(--blue-900);">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $result)
                        <tr style="border-bottom:1px solid var(--gray-100);">
                            <td style="padding:12px 16px;">{{ $result->subject }}</td>
                            <td style="padding:12px 16px; color:var(--gray-500);">{{ $result->test_title }}</td>
                            <td style="padding:12px 16px; text-align:center; font-weight:700;
                                color:{{ $result->percentage >= 75 ? 'var(--green)' : ($result->percentage >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                                {{ $result->percentage }}%
                            </td>
                            <td style="padding:12px 16px; color:var(--gray-400); font-size:12px;">
                                {{ \Carbon\Carbon::parse($result->created_at)->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

@endsection