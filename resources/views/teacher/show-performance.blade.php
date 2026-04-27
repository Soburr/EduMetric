@extends('layouts.teacher.app')

@section('title', 'Test Performance — '.$test->title)

@section('content')

{{-- BACK BUTTON --}}
<a href="{{ route('teacher.performance.index') }}"
   style="display:inline-flex; align-items:center; gap:6px; font-size:13px;
          font-weight:600; color:var(--blue-600); text-decoration:none; margin-bottom:20px;">
    ← Back to Performance
</a>

{{-- PAGE HEADER --}}
<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">
        📈 {{ $test->title }}
    </div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        {{ $test->subject }} &nbsp;·&nbsp; {{ $test->schoolClass?->name ?? '—' }}
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="perf-grid" style="display:grid; grid-template-columns:repeat(2,1fr);
            gap:12px; margin-bottom:28px;">

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">📬</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:var(--blue-900);">{{ $submitted }}</div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Submitted</div>
    </div>

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">⏳</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:{{ $notSubmitted > 0 ? 'var(--red)' : 'var(--green)' }};">
            {{ $notSubmitted }}
        </div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Not Submitted</div>
    </div>

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">🎯</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:{{ $avgScore >= 50 ? 'var(--green)' : 'var(--red)' }};">
            {{ $submitted > 0 ? $avgScore.'%' : '—' }}
        </div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Avg. Score</div>
    </div>

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">⬆️</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:var(--green);">
            {{ $submitted > 0 ? $highest.'%' : '—' }}
        </div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Highest</div>
    </div>

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">⬇️</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:var(--red);">
            {{ $submitted > 0 ? $lowest.'%' : '—' }}
        </div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Lowest</div>
    </div>

    <div class="card" style="text-align:center; padding:18px 14px;">
        <div style="font-size:24px; margin-bottom:5px;">✅</div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:24px; color:{{ $passRate >= 50 ? 'var(--green)' : 'var(--red)' }};">
            {{ $submitted > 0 ? $passRate.'%' : '—' }}
        </div>
        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Pass Rate</div>
    </div>

</div>

{{-- SCORE DISTRIBUTION CHART --}}
@if($submitted > 0)
    <div class="card">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:15px; color:var(--blue-900); margin-bottom:4px;">
            📊 Score Distribution
        </div>
        <div style="font-size:12.5px; color:var(--gray-400); margin-bottom:20px;">
            A (70–100) · B (60–69) · C (50–59) · D (44–49) · E (40–43) · F (0–39)
        </div>
        <div style="position:relative; height:260px; max-width:100%; margin:0 auto;">
            <canvas id="distChart"></canvas>
        </div>
    </div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>No submissions yet for this test.</p>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
    @media (min-width: 640px) {
        .perf-grid { grid-template-columns: repeat(3, 1fr) !important; }
    }
    @media (min-width: 1024px) {
        .perf-grid { grid-template-columns: repeat(6, 1fr) !important; }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
@if($submitted > 0)
    const ctx = document.getElementById('distChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['A (70–100)', 'B (60–69)', 'C (50–59)', 'D (44–49)', 'E (40–43)', 'F (0–39)'],
            datasets: [{
                data: [
                    {{ $distribution['A'] }},
                    {{ $distribution['B'] }},
                    {{ $distribution['C'] }},
                    {{ $distribution['D'] }},
                    {{ $distribution['E'] }},
                    {{ $distribution['F'] }},
                ],
                backgroundColor: [
                    'rgba(22,163,74,0.85)',
                    'rgba(37,99,235,0.85)',
                    'rgba(234,179,8,0.85)',
                    'rgba(249,115,22,0.85)',
                    'rgba(168,85,247,0.85)',
                    'rgba(220,38,38,0.85)',
                ],
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12, family: 'inherit' },
                        padding: 16,
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed} student${ctx.parsed !== 1 ? 's' : ''}`
                    }
                }
            }
        }
    });
@endif
</script>
@endpush