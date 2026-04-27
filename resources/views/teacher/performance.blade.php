@extends('layouts.teacher.app')

@section('title', 'Performance')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">
        📊 Performance
    </div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Overview of how your classes and tests are performing
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="perf-grid" style="margin-bottom:28px;">

    <div class="card" style="text-align:center; padding:20px 16px;">
        <div style="font-size:28px; margin-bottom:6px;">📝</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:26px; color:var(--blue-900);">{{ $totalTests }}</div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">Tests Created</div>
    </div>

    <div class="card" style="text-align:center; padding:20px 16px;">
        <div style="font-size:28px; margin-bottom:6px;">📬</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:26px; color:var(--blue-900);">{{ $totalSubmissions }}</div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">Total Submissions</div>
    </div>

    <div class="card" style="text-align:center; padding:20px 16px;">
        <div style="font-size:28px; margin-bottom:6px;">🎯</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:26px; color:{{ ($overallAvg ?? 0) >= 50 ? 'var(--green)' : 'var(--red)' }};">
            {{ $overallAvg !== null ? round($overallAvg).'%' : '—' }}
        </div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">Overall Avg. Score</div>
    </div>

    <div class="card" style="text-align:center; padding:20px 16px;">
        <div style="font-size:28px; margin-bottom:6px;">🏆</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:20px; color:var(--blue-900); line-height:1.2; margin-bottom:2px;">
            {{ $bestClass ? $bestClass['name'] : '—' }}
        </div>
        @if($bestClass)
            <div style="font-size:11.5px; color:var(--gray-400);">{{ $bestClass['avg'] }}% avg</div>
        @endif
        <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">Best Class</div>
    </div>

    <div class="card" style="text-align:center; padding:20px 16px;">
        <div style="font-size:28px; margin-bottom:6px;">⚠️</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:26px; color:{{ $atRiskCount > 0 ? 'var(--red)' : 'var(--green)' }};">
            {{ $atRiskCount }}
        </div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">At-Risk Students</div>
    </div>

</div>

{{-- CLASS COMPARISON CHART --}}
@if($classComparison->count() > 0)
    <div class="card" style="margin-bottom:28px;">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:15px; color:var(--blue-900); margin-bottom:4px;">
            📊 Class Comparison
        </div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-bottom:20px;">
            Average score per class across all your tests
        </div>
        <div style="position:relative; height:220px; max-width:100%;">
            <canvas id="classChart"></canvas>
        </div>
    </div>
@endif

{{-- TEST LIST --}}
@if($tests->count() > 0)
    <div class="card">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:15px; color:var(--blue-900); margin-bottom:16px;">
            🗂 Your Tests
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:12px 16px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Test
                        </th>
                        <th class="hide-mobile" style="padding:12px 16px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Class
                        </th>
                        <th class="hide-mobile" style="padding:12px 16px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Submissions
                        </th>
                        <th style="padding:12px 16px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Avg.
                        </th>
                        <th style="padding:12px 16px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tests as $test)
                        @php
                            $avg = round($test->submissions->avg('percentage') ?? 0);
                            $sub = $test->submissions->count();
                        @endphp
                        <tr style="border-bottom:1px solid var(--gray-100); transition:background .15s;"
                            onmouseover="this.style.background='var(--blue-50)'"
                            onmouseout="this.style.background=''">

                            <td style="padding:12px 16px;">
                                <div style="font-weight:600; color:var(--blue-900);">
                                    {{ $test->title }}
                                </div>
                                <div style="font-size:11.5px; color:var(--gray-400); margin-top:2px;">
                                    {{ $test->subject }}
                                    {{-- Show class inline on mobile since class column is hidden --}}
                                    <span class="show-mobile">
                                        &nbsp;·&nbsp; {{ $test->schoolClass?->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            <td class="hide-mobile" style="padding:12px 16px; color:var(--gray-500);">
                                {{ $test->schoolClass?->name ?? '—' }}
                            </td>

                            <td class="hide-mobile" style="padding:12px 16px; text-align:center;
                                       font-weight:700; color:var(--blue-900);">
                                {{ $sub }}
                            </td>

                            <td style="padding:12px 16px; text-align:center;">
                                @if($sub > 0)
                                    <span style="font-weight:700;
                                        color:{{ $avg >= 70 ? 'var(--green)' : ($avg >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                                        {{ $avg }}%
                                    </span>
                                @else
                                    <span style="color:var(--gray-400); font-size:12px;">—</span>
                                @endif
                            </td>

                            <td style="padding:12px 16px; text-align:center;">
                                @if($sub > 0)
                                    <a href="{{ route('teacher.performance.show', $test->id) }}"
                                       style="padding:6px 13px; border-radius:7px; font-size:12px;
                                              font-weight:600; text-decoration:none;
                                              background:var(--blue-50); color:var(--blue-600);
                                              border:1.5px solid var(--blue-100); white-space:nowrap;">
                                        📈 Details
                                    </a>
                                @else
                                    <span style="font-size:12px; color:var(--gray-300);">No data</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>No tests yet. Create a test to start seeing performance data.</p>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
    /* Summary grid — 2 cols mobile, 3 cols tablet, 5 cols desktop */
    .perf-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (min-width: 640px) {
        .perf-grid { grid-template-columns: repeat(3, 1fr); }
    }

    @media (min-width: 1024px) {
        .perf-grid { grid-template-columns: repeat(5, 1fr); }
    }

    /* Hide on mobile */
    @media (max-width: 640px) {
        .hide-mobile { display: none !important; }
        .show-mobile { display: inline !important; }
    }

    /* Show only on mobile */
    .show-mobile { display: none; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($classComparison->count() > 0)
    const ctx = document.getElementById('classChart').getContext('2d');
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
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y}% average`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => v + '%' },
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });
@endif
</script>
@endpush