@extends('layouts.teacher.app')

@section('title', 'Submissions – ' . $test->title)

@section('content')

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between;
                flex-wrap:wrap; gap:12px; margin-bottom:24px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:var(--blue-900);">
                📋 Submissions
            </div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                {{ $test->title }} &nbsp;·&nbsp; {{ $test->subject }} &nbsp;·&nbsp;
                {{ $test->schoolClass->name ?? '—' }}
            </div>
        </div>
        <a href="{{ route('teacher.exams.index') }}"
           style="padding:9px 18px; border-radius:9px; border:1.5px solid var(--gray-200);
                  color:var(--gray-500); font-size:13.5px; font-weight:600;
                  text-decoration:none; font-family:'Plus Jakarta Sans',sans-serif;">
            ← Back to Exams
        </a>
    </div>

    {{-- Summary tiles --}}
    <div class="grid-4" style="margin-bottom:24px;">
        <div class="stat-tile">
            <div class="stat-icon blue">👥</div>
            <div class="stat-num">{{ $totalStudents }}</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-tile active-border">
            <div class="stat-icon green">✅</div>
            <div class="stat-num">{{ $submissions->count() }}</div>
            <div class="stat-label">Submitted</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon amber">⏳</div>
            <div class="stat-num">{{ $totalStudents - $submissions->count() }}</div>
            <div class="stat-label">Not Submitted</div>
        </div>
        <div class="stat-tile">
            <div class="stat-icon blue">📈</div>
            <div class="stat-num">{{ $avgScore }}%</div>
            <div class="stat-label">Class Average</div>
        </div>
    </div>

    {{-- Submissions table --}}
    @if($submissions->count() > 0)
        <div class="card" style="padding:0; overflow:hidden;">

            {{-- Search + export bar --}}
            <div style="padding:16px 20px; border-bottom:1px solid var(--gray-100);
                        display:flex; align-items:center; justify-content:space-between;
                        flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:8px;
                            background:var(--gray-100); border-radius:9px; padding:8px 14px;
                            width:260px;">
                    <span style="color:var(--gray-400);">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search by name or ID…"
                           oninput="filterTable()"
                           style="border:none; background:none; outline:none;
                                  font-size:13.5px; font-family:inherit;
                                  color:var(--gray-700); width:100%;">
                </div>
                <div style="font-size:13px; color:var(--gray-400);">
                    {{ $submissions->count() }} result(s)
                </div>
            </div>

            <table style="width:100%; border-collapse:collapse; font-size:13.5px;" id="submissionsTable">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:13px 20px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            #
                        </th>
                        <th style="padding:13px 20px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Full Name
                        </th>
                        <th style="padding:13px 20px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Student ID
                        </th>
                        <th style="padding:13px 20px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Score
                        </th>
                        <th style="padding:13px 20px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Percentage
                        </th>
                        <th style="padding:13px 20px; text-align:center; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Grade
                        </th>
                        <th style="padding:13px 20px; text-align:left; font-weight:700;
                                   color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                            Date Submitted
                        </th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @foreach($submissions as $index => $submission)
                        <tr class="table-row"
                            style="border-bottom:1px solid var(--gray-100);
                                   transition:background .15s;"
                            onmouseover="this.style.background='var(--blue-50)'"
                            onmouseout="this.style.background=''"  >

                            <td style="padding:13px 20px; color:var(--gray-400);
                                       font-size:12px;">
                                {{ $index + 1 }}
                            </td>

                            <td style="padding:13px 20px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div style="width:32px; height:32px; border-radius:8px;
                                                background:var(--blue-700); display:flex;
                                                align-items:center; justify-content:center;
                                                font-family:'Plus Jakarta Sans',sans-serif;
                                                font-weight:700; font-size:12px; color:#fff;
                                                flex-shrink:0;">
                                        {{ strtoupper(substr($submission->student->name, 0, 2)) }}
                                    </div>
                                    <span style="font-weight:600; color:var(--blue-900);"
                                          class="student-name">
                                        {{ $submission->student->name }}
                                    </span>
                                </div>
                            </td>

                            <td style="padding:13px 20px; color:var(--gray-500);"
                                class="student-id">
                                {{ $submission->student->student_id }}
                            </td>

                            <td style="padding:13px 20px; text-align:center;
                                       font-weight:700; color:var(--blue-900);">
                                {{ $submission->score }} / {{ $submission->total }}
                            </td>

                            <td style="padding:13px 20px; text-align:center;">
                                @php $pct = $submission->percentage; @endphp
                                <span style="font-weight:700;
                                    color:{{ $pct >= 75 ? 'var(--green)' : ($pct >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                                    {{ $pct }}%
                                </span>
                            </td>

                            <td style="padding:13px 20px; text-align:center;">
                                @php
                                    $grade = match(true) {
                                        $pct >= 80 => 'A',
                                        $pct >= 70 => 'B',
                                        $pct >= 60 => 'C',
                                        $pct >= 50 => 'D',
                                        default    => 'F',
                                    };
                                    $badgeClass = match($grade) {
                                        'A'     => 'badge-green',
                                        'B','C' => 'badge-blue',
                                        default => 'badge-amber',
                                    };
                                @endphp
                                <span class="module-badge {{ $badgeClass }}">{{ $grade }}</span>
                            </td>

                            <td style="padding:13px 20px; color:var(--gray-400); font-size:12.5px;">
                                {{ $submission->created_at->format('M d, Y') }}<br>
                                <span style="font-size:11.5px;">
                                    {{ $submission->created_at->format('h:i A') }}
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No submissions yet for this exam.</p>
                <p style="margin-top:6px; font-size:12.5px; color:var(--gray-400);">
                    Students will appear here once they submit.
                </p>
            </div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    function filterTable() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const rows  = document.querySelectorAll('#tableBody .table-row');

        rows.forEach(row => {
            const name = row.querySelector('.student-name')?.textContent.toLowerCase() ?? '';
            const id   = row.querySelector('.student-id')?.textContent.toLowerCase() ?? '';
            row.style.display = (name.includes(query) || id.includes(query)) ? '' : 'none';
        });
    }
</script>
@endpush