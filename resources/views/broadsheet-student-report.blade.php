@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'layouts.teacher.app')

@section('title', $student->name . ' — Report Card')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div style="display:flex; align-items:center; gap:12px;">
        <a href="javascript:history.back()"
           style="background:var(--blue-900); color:#fff; border:none; padding:9px 16px;
                  border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;
                  text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
            ← Back
        </a>
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:var(--blue-900);">📄 {{ $student->name }}'s Report Card</div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                {{ $student->studentClass->name ?? '—' }} · ID: {{ $student->student_id }}
            </div>
        </div>
    </div>
    @if($scores->count() > 0)
        <button onclick="window.print()"
                style="background:var(--blue-900); color:#fff; border:none; padding:9px 16px;
                       border-radius:8px; font-size:13px; font-family:'DM Sans',sans-serif;
                       font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
            🖨️ Print
        </button>
    @endif
</div>

{{-- Term selector --}}
<div class="card no-print" style="margin-bottom:24px;">
    <form method="GET"
          style="display:flex; gap:14px; align-items:flex-end; flex-wrap:wrap;">
        <div>
            <label class="field-label">Term</label>
            <select class="field-input" name="term" style="min-width:160px;" required>
                <option value="">-- Select Term --</option>
                <option value="first"  {{ $selectedTerm == 'first'  ? 'selected' : '' }}>First Term</option>
                <option value="second" {{ $selectedTerm == 'second' ? 'selected' : '' }}>Second Term</option>
                <option value="third"  {{ $selectedTerm == 'third'  ? 'selected' : '' }}>Third Term</option>
            </select>
        </div>
        <div>
            <label class="field-label">Session</label>
            <select class="field-input" name="session" style="min-width:160px;" required>
                <option value="">-- Select Session --</option>
                @foreach($sessions as $session)
                    <option value="{{ $session }}"
                            {{ $selectedSession == $session ? 'selected' : '' }}>
                        {{ $session }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit"
                style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                       border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                       font-weight:600; cursor:pointer;">
            📄 View Report
        </button>
    </form>
</div>

@if($scores->count() > 0)
    <div class="print-area">
    <div class="card" style="padding:28px;">

        {{-- Student header --}}
        <div style="display:flex; align-items:center; justify-content:space-between;
                    flex-wrap:wrap; gap:16px; margin-bottom:24px; padding-bottom:20px;
                    border-bottom:2px solid var(--blue-900);">
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="width:56px; height:56px; border-radius:14px; background:var(--blue-700);
                            display:flex; align-items:center; justify-content:center;
                            font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:22px; color:#fff; flex-shrink:0;">
                    {{ strtoupper(substr($student->name, 0, 2)) }}
                </div>
                <div>
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                font-size:18px; color:var(--blue-900);">{{ $student->name }}</div>
                    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                        ID: {{ $student->student_id }} &nbsp;·&nbsp;
                        Class: {{ $student->studentClass->name ?? '—' }}
                    </div>
                </div>
            </div>
            <div style="text-align:right;">
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                            font-size:15px; color:var(--blue-900);">
                    {{ \App\Models\SubjectScore::termLabel($selectedTerm) }}
                </div>
                <div style="font-size:13px; color:var(--gray-400); margin-top:3px;">
                    Session: {{ $selectedSession }}
                </div>
            </div>
        </div>

        {{-- Scores table --}}
        <table style="width:100%; border-collapse:collapse; font-size:13.5px; margin-bottom:24px;">
            <thead>
                <tr style="background:var(--blue-900); color:#fff;">
                    <th style="padding:12px 16px; text-align:left; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Subject</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">CBT /20</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">CA /30</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Exam /50</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Total /100</th>
                    <th style="padding:12px 16px; text-align:center; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Grade</th>
                    <th style="padding:12px 16px; text-align:left; font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;">Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $score)
                    <tr style="border-bottom:1px solid var(--gray-100);
                               background:{{ $loop->odd ? '#fff' : 'var(--gray-50)' }};">
                        <td style="padding:11px 16px; font-weight:600; color:var(--blue-900);">
                            {{ $score->subject }}
                        </td>
                        <td style="padding:11px 16px; text-align:center; color:var(--blue-600); font-weight:600;">
                            {{ $score->cbt_score ? number_format($score->cbt_score, 0) : '—' }}
                        </td>
                        <td style="padding:11px 16px; text-align:center; color:var(--gray-600);">
                            {{ $score->ca_score ? number_format($score->ca_score, 0) : '—' }}
                        </td>
                        <td style="padding:11px 16px; text-align:center; color:var(--gray-600);">
                            {{ $score->exam_score ? number_format($score->exam_score, 0) : '—' }}
                        </td>
                        <td style="padding:11px 16px; text-align:center; font-weight:800;
                            font-family:'Plus Jakarta Sans',sans-serif; font-size:15px;
                            color:{{ $score->total >= 70 ? 'var(--green)' : ($score->total >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                            {{ number_format($score->total, 0) }}
                        </td>
                        <td style="padding:11px 16px; text-align:center;">
                            <span style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                         font-size:16px;
                                         color:{{ $score->grade === 'A' ? 'var(--green)' : ($score->grade === 'F' ? 'var(--red)' : 'var(--blue-600)') }}">
                                {{ $score->grade ?? '—' }}
                            </span>
                        </td>
                        <td style="padding:11px 16px; font-size:12.5px; color:var(--gray-500); font-style:italic;">
                            {{ $score->remark ?? '—' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Summary --}}
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:20px;">
            <div style="background:var(--blue-50); border:1px solid var(--blue-100);
                        border-radius:12px; padding:16px; text-align:center;">
                <div style="font-size:11.5px; color:var(--gray-400); margin-bottom:6px;">Subjects</div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:24px; color:var(--blue-900);">{{ $scores->count() }}</div>
            </div>
            <div style="background:var(--blue-50); border:1px solid var(--blue-100);
                        border-radius:12px; padding:16px; text-align:center;">
                <div style="font-size:11.5px; color:var(--gray-400); margin-bottom:6px;">Total Score</div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:24px; color:var(--blue-900);">{{ number_format($grandTotal, 0) }}</div>
            </div>
            <div style="border-radius:12px; padding:16px; text-align:center;
                        background:{{ $average >= 70 ? 'rgba(16,185,129,.1)' : ($average >= 40 ? 'var(--blue-50)' : 'rgba(239,68,68,.08)') }};
                        border:1px solid {{ $average >= 70 ? 'rgba(16,185,129,.2)' : ($average >= 40 ? 'var(--blue-100)' : 'rgba(239,68,68,.15)') }};">
                <div style="font-size:11.5px; color:var(--gray-400); margin-bottom:6px;">Average</div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:24px;
                            color:{{ $average >= 70 ? 'var(--green)' : ($average >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                    {{ $average }}%
                </div>
            </div>
            <div style="background:var(--blue-50); border:1px solid var(--blue-100);
                        border-radius:12px; padding:16px; text-align:center;">
                <div style="font-size:11.5px; color:var(--gray-400); margin-bottom:6px;">Class Position</div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:24px; color:var(--blue-900);">
                    {{ $position ?? '—' }}
                    @if($totalStudents)
                        <span style="font-size:13px; font-weight:500; color:var(--gray-400);">
                            / {{ $totalStudents }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Grading key --}}
        <div style="padding-top:16px; border-top:1px solid var(--gray-100);
                    display:flex; gap:16px; flex-wrap:wrap; font-size:12px; color:var(--gray-400);">
            <strong style="color:var(--gray-500);">Key:</strong>
            <span>A = 70–100</span><span>B = 60–69</span><span>C = 50–59</span>
            <span>D = 45–49</span><span>E = 40–44</span><span>F = 0–39</span>
        </div>

    </div>
    </div>

@elseif($selectedTerm && $selectedSession)
    <div class="card">
        <div class="empty-state">
            <div class="empty-icon">📭</div>
            <p>No results available for this term yet.</p>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
    .field-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
    .field-input {
        padding:10px 12px; border-radius:8px; border:1px solid var(--gray-200);
        font-size:13.5px; font-family:'DM Sans',sans-serif; color:var(--gray-700);
        background:#fff; box-sizing:border-box;
    }
    .field-input:focus { outline:none; border-color:var(--blue-500); box-shadow:0 0 0 3px rgba(59,130,246,.1); }

    @media print {
        .sidebar, .topbar, .no-print, button { display:none !important; }
        .main { margin-left:0 !important; }
        .page { padding:0 !important; }
        body  { background:white !important; }
        .print-area { display:block !important; }
        .card { box-shadow:none !important; border:1px solid #ccc !important; }
        table { page-break-inside:auto; }
        tr    { page-break-inside:avoid; }
    }
</style>
@endpush