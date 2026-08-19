@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'layouts.teacher.app')

@section('title', $student->name . ' — Report Card')

@section('content')

{{-- Top bar --}}
<div class="no-print" style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
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
    <form method="GET" style="display:flex; gap:14px; align-items:flex-end; flex-wrap:wrap;">
        <div>
            <label class="field-label">Term</label>
            <select class="field-input" name="term" style="min-width:160px;" required>
                <option value="">-- Select Term --</option>
                <option value="first" {{ $selectedTerm == 'first'  ? 'selected' : '' }}>First Term</option>
                <option value="second" {{ $selectedTerm == 'second' ? 'selected' : '' }}>Second Term</option>
                <option value="third" {{ $selectedTerm == 'third'  ? 'selected' : '' }}>Third Term</option>
            </select>
        </div>
        <div>
            <label class="field-label">Session</label>
            <select class="field-input" name="session" style="min-width:160px;" required>
                <option value="">-- Select Session --</option>
                @foreach($sessions as $session)
                <option value="{{ $session }}" {{ $selectedSession == $session ? 'selected' : '' }}>
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

{{-- Meta save form --}}
<div class="card no-print" style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700; font-size:14px;
                color:var(--blue-900); margin-bottom:16px;">✏️ Fill Report Card Details</div>

    <form method="POST" action="{{ auth()->user()->role === 'admin'
        ? route('admin.student_report_card.meta', $student->id)
        : route('teacher.student_report_card.meta', $student->id) }}">
        @csrf
        <input type="hidden" name="student_id" value="{{ $student->id }}">
        <input type="hidden" name="class_id" value="{{ $student->class_id }}">
        <input type="hidden" name="term" value="{{ $selectedTerm }}">
        <input type="hidden" name="session" value="{{ $selectedSession }}">

        {{-- Attendance -- auto calculated, shown read-only --}}
        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-bottom:20px; width:100%;">
            <div>
                <label class="field-label">Times School Opened</label>
                <div style="padding:10px 12px; background:var(--gray-50); border-radius:8px;
                    border:1px solid var(--gray-200); font-size:13.5px; font-weight:700;
                    color:var(--blue-900);">
                    {{ $attendanceStats['times_opened'] ?: '—' }}
                </div>
                <div style="font-size:11px; color:var(--gray-400); margin-top:4px;">
                    Set by admin in School Calendar
                </div>
            </div>
            <div>
                <label class="field-label">Times Present</label>
                <div style="padding:10px 12px; background:rgba(16,185,129,.06); border-radius:8px;
                    border:1px solid rgba(16,185,129,.2); font-size:13.5px; font-weight:700;
                    color:var(--green);">
                    {{ $attendanceStats['times_present'] ?: '—' }}
                </div>
                <div style="font-size:11px; color:var(--gray-400); margin-top:4px;">
                    Auto-counted from attendance records
                </div>
            </div>
            <div>
                <label class="field-label">Times Absent</label>
                <div style="padding:10px 12px; background:rgba(239,68,68,.06); border-radius:8px;
                    border:1px solid rgba(239,68,68,.2); font-size:13.5px; font-weight:700;
                    color:var(--red);">
                    {{ $attendanceStats['times_absent'] ?: '—' }}
                </div>
                <div style="font-size:11px; color:var(--gray-400); margin-top:4px;">
                    Auto-calculated: Opened − Present
                </div>
            </div>
        </div>{{-- end attendance grid --}}

        {{-- Behavioural Attributes --}}
        <div style="font-weight:700; font-size:13px; color:var(--blue-900); margin-bottom:12px; margin-top:8px;">
            Behavioural Attributes <span style="font-weight:400; color:var(--gray-400); font-size:12px;">(Rate 1–5)</span>
        </div>
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:20px; width:100%;">
            @foreach([
            'obedience' => 'Obedience', 'honesty' => 'Honesty',
            'self_control' => 'Self-Control', 'self_reliance' => 'Self-Reliance',
            'use_of_initiative' => 'Use of Initiative', 'punctuality' => 'Punctuality',
            'neatness' => 'Neatness', 'perseverance' => 'Perseverance',
            'attendance_rating' => 'Attendance', 'attentiveness' => 'Attentiveness',
            'courtesy' => 'Courtesy/Politeness', 'consideration' => 'Consideration for Others',
            'sociability' => 'Sociability/Team Player', 'consistency' => 'Consistency',
            'accept_responsibility' => 'Accept Responsibility',
            'reading_writing' => 'Reading & Writing', 'verbal_communication' => 'Verbal Communication',
            'sports_games' => 'Sports and Games', 'inquisitiveness' => 'Inquisitiveness',
            'dexterity' => 'Dexterity (Art & Music)',
            ] as $field => $label)
            <div>
                <label class="field-label">{{ $label }}</label>
                <select name="{{ $field }}" class="field-input">
                    <option value="">—</option>
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ $meta?->$field == $i ? 'selected' : '' }}>
                        {{ $i }}
                        </option>
                        @endfor
                </select>
            </div>
            @endforeach
        </div>

        {{-- Comments --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
            <div>
                <label class="field-label">Class Teacher's Comment</label>
                <textarea name="class_teacher_comment" class="field-input" rows="2"
                    placeholder="e.g. She is a well behaved student.">{{ $meta?->class_teacher_comment }}</textarea>
            </div>
            @if(auth()->user()->role === 'admin')
            <div>
                <label class="field-label">Principal's Comment</label>
                <textarea name="principal_comment" class="field-input" rows="2"
                    placeholder="e.g. A very good performance...">{{ $meta?->principal_comment }}</textarea>
            </div>
            @endif
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:16px;">
            <div>
                <label class="field-label">Promoted To / Repeated</label>
                <input type="text" name="promoted_repeated" class="field-input"
                    placeholder="e.g. Promoted to SSS2"
                    value="{{ $meta?->promoted_repeated }}">
            </div>
            <div>
                <label class="field-label">Next Term Begins On</label>
                <input type="text" name="next_term_date" class="field-input"
                    placeholder="e.g. 4TH MAY 2026"
                    value="{{ $meta?->next_term_date }}">
            </div>
        </div>

        <button type="submit"
            style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                       border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                       font-weight:600; cursor:pointer;">
            💾 Save Details
        </button>

        @if(session('success'))
        <span style="margin-left:12px; color:var(--green); font-size:13px; font-weight:600;">
            ✓ {{ session('success') }}
        </span>
        @endif
    </form>
</div>

{{-- ==================== PRINTABLE REPORT CARD ==================== --}}
<div class="print-area" id="reportCard">
    <div style="background:#fff; padding:20px; font-family:'Times New Roman', serif;
            font-size:12px; color:#000; max-width:900px; margin:0 auto;
            border:2px solid #000;">

        {{-- School Header --}}
        <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:10px; margin-bottom:10px;">
            <div style="font-size:20px; font-weight:900; letter-spacing:1px;">DUNAMIS GROUP OF SCHOOLS</div>
            <div style="font-size:11px; margin-top:3px;">
                14, Ademola Oke Crescent, Moshalasi Bus Stop, Alagbado, Lagos State
            </div>
            <div style="font-size:11px;">
                Tel: 08056518733, 07031949657 &nbsp; Email: Dunamisschool@yahoo.com
            </div>
            <div style="font-size:13px; font-weight:700; margin-top:6px; text-decoration:underline;">
                CONTINUOUS ASSESSMENT REPORT {{ $selectedSession }}
            </div>
        </div>

        {{-- Class & Term badges --}}
        <div style="display:flex; justify-content:flex-end; gap:8px; margin-bottom:8px;">
            <div style="border:1.5px solid #000; padding:4px 12px; font-weight:700; font-size:12px;">
                {{ $student->studentClass->name ?? '—' }}
            </div>
            <div style="border:1.5px solid #000; padding:4px 12px; font-weight:700; font-size:12px;">
                {{ strtoupper(\App\Models\SubjectScore::termLabel($selectedTerm)) }}
            </div>
        </div>

        {{-- Student Info & Attendance --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:0; border:1.5px solid #000; margin-bottom:8px;">
            <div style="border-right:1.5px solid #000;">
                <div style="background:#000; color:#fff; font-weight:700; padding:4px 8px; font-size:11px;">
                    STUDENT'S PERSONAL DATA
                </div>
                @php $nameParts = explode(' ', $student->name, 2); @endphp
                <table style="width:100%; border-collapse:collapse; font-size:11.5px;">
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:4px 8px; font-weight:700; width:40%;">SURNAME:</td>
                        <td style="padding:4px 8px; font-weight:700; text-transform:uppercase;">
                            {{ strtoupper($nameParts[0] ?? '') }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:4px 8px; font-weight:700;">OTHER NAME(S):</td>
                        <td style="padding:4px 8px; font-weight:700; text-transform:uppercase;">
                            {{ strtoupper($nameParts[1] ?? '') }}
                        </td>
                    </tr>
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:4px 8px; font-weight:700;">SEX:</td>
                        <td style="padding:4px 8px;">{{ strtoupper($student->gender ?? '—') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:4px 8px; font-weight:700;">ADMISSION NO:</td>
                        <td style="padding:4px 8px;">{{ $student->student_id }}</td>
                    </tr>
                </table>
            </div>

            <div>
                <div style="background:#000; color:#fff; font-weight:700; padding:4px 8px; font-size:11px;">
                    ATTENDANCE
                </div>
                <table style="width:100%; border-collapse:collapse; font-size:11.5px;">
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:4px 8px; font-weight:700; text-align:center;">Times Schl. Opened</td>
                        <td style="padding:4px 8px; font-weight:700; text-align:center;">Times Present</td>
                        <td style="padding:4px 8px; font-weight:700; text-align:center;">Times Absent</td>
                    </tr>
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:6px 8px; text-align:center; font-weight:700;">
                            {{ $attendanceStats['times_opened']  ?: '' }}
                        </td>
                        <td style="padding:6px 8px; text-align:center; font-weight:700;">
                            {{ $attendanceStats['times_present'] ?: '' }}
                        </td>
                        <td style="padding:6px 8px; text-align:center; font-weight:700;">
                            {{ $attendanceStats['times_absent']  ?: '' }}
                        </td>
                    </tr>
                </table>
                <div style="background:#000; color:#fff; font-weight:700; padding:4px 8px; font-size:11px; margin-top:4px;">
                    TERMINAL DURATION: {{ strtoupper($meta?->terminal_duration ?? '') }}
                </div>
                <table style="width:100%; border-collapse:collapse; font-size:11.5px;">
                    <tr style="border-bottom:1px solid #ccc;">
                        <td style="padding:4px 6px; font-weight:700; text-align:center;">Term Begins</td>
                        <td style="padding:4px 6px; font-weight:700; text-align:center;">Term Ends</td>
                        <td style="padding:4px 6px; font-weight:700; text-align:center;">Next Term Begins</td>
                    </tr>
                    <tr>
                        <td style="padding:5px 6px; text-align:center; font-weight:700; font-size:11px;">
                            {{ strtoupper($meta?->term_begins ?? '') }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:700; font-size:11px;">
                            {{ strtoupper($meta?->term_ends ?? '') }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:700; font-size:11px;">
                            {{ strtoupper($meta?->next_term_begins ?? '') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Grade Key --}}
        <div style="border:1.5px solid #000; margin-bottom:8px;">
            <div style="background:#000; color:#fff; font-weight:700; padding:3px 8px; font-size:11px; text-align:center;">
                GRADE
            </div>
            <div style="display:flex; justify-content:space-around; padding:4px 8px; font-size:11px; font-weight:600;">
                <span>A1(75-100) EXCELLENT</span>
                <span>B2(70-74) GOOD</span>
                <span>B3(65-69) GOOD</span>
                <span>C4(60-64), C5(55-59), C6(50-54) CREDITS</span>
                <span>D7(45-49), E8(40-44) PASS</span>
                <span>F9(0-39) WEAK</span>
            </div>
        </div>

        {{-- Academic Performance --}}
        <div style="border:1.5px solid #000; margin-bottom:8px;">
            <div style="background:#000; color:#fff; font-weight:700; padding:3px 8px; font-size:11px; text-align:center;">
                ACADEMIC PERFORMANCE
            </div>
            <table style="width:100%; border-collapse:collapse; font-size:11px;">
                <thead>
                    <tr style="border-bottom:1.5px solid #000;">
                        <th style="padding:5px 8px; text-align:left; border-right:1px solid #ccc; width:24%;">
                            Termly Percentage
                        </th>
                        <th colspan="3" style="padding:5px 8px; text-align:center; border-right:1px solid #ccc;">
                            MARKS OBTAINABLE
                        </th>
                        <th style="padding:5px 8px; text-align:center; border-right:1px solid #ccc; width:16%;">
                            GRADE (REMARKS)
                        </th>
                        <th colspan="3" style="padding:5px 8px; text-align:center;">
                            TERM SUMMARY
                        </th>
                    </tr>
                    <tr style="border-bottom:1px solid #ccc; background:#f5f5f5;">
                        <td style="padding:4px 8px; font-weight:800; font-size:16px; text-align:center;
                               border-right:1px solid #ccc; vertical-align:middle;" rowspan="2">
                            {{ $average }}%
                        </td>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ddd; width:9%;">CA /30</th>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ddd; width:9%;">EXAM /70</th>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ccc; width:9%;">SCORES /100</th>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ccc;"></th>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ddd; width:9%;">CLASS LOWEST</th>
                        <th style="padding:4px 6px; text-align:center; border-right:1px solid #ddd; width:9%;">CLASS HIGHEST</th>
                        <th style="padding:4px 6px; text-align:center; width:9%;">CLASS AVERAGE</th>
                    </tr>
                    <tr style="border-bottom:1px solid #ccc; background:#f5f5f5;">
                        <td style="padding:3px 6px; text-align:center; border-right:1px solid #ddd; font-size:10px;">
                            Max: 30%
                        </td>
                        <td style="padding:3px 6px; text-align:center; border-right:1px solid #ddd; font-size:10px;">
                            Max: 70%
                        </td>
                        <td style="padding:3px 6px; text-align:center; border-right:1px solid #ccc; font-size:10px;">
                            Max: 100%
                        </td>
                        <td style="border-right:1px solid #ccc;"></td>
                        <td style="padding:3px 6px; text-align:center; border-right:1px solid #ddd; font-size:10px;">
                            100%
                        </td>
                        <td style="padding:3px 6px; text-align:center; border-right:1px solid #ddd; font-size:10px;">
                            100%
                        </td>
                        <td style="padding:3px 6px; text-align:center; font-size:10px;">100%</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scores as $score)
                    @php $stats = $subjectStats[$score->subject] ?? ['lowest'=>'—','highest'=>'—','average'=>'—']; @endphp
                    <tr style="border-bottom:1px solid #ddd;">
                        <td style="padding:5px 8px; font-weight:600; border-right:1px solid #ccc;">
                            {{ $score->subject }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; border-right:1px solid #ddd;">
                            {{ $score->cbt_score ? number_format($score->cbt_score, 0) : '—' }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; border-right:1px solid #ddd;">
                            {{ $score->exam_score ? number_format($score->exam_score, 0) : '—' }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:700; border-right:1px solid #ccc;">
                            {{ number_format($score->total, 0) }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:700; border-right:1px solid #ccc;">
                            {{ $score->grade ?? '—' }}
                            @php
                            $gradeLabel = match($score->grade) {
                            'A1' => 'EXCELLENT', 'B2','B3' => 'GOOD',
                            'C4','C5','C6' => 'CREDIT',
                            'D7','E8' => 'PASS', 'F9' => 'WEAK', default => ''
                            };
                            @endphp
                            [{{ $gradeLabel }}]
                        </td>
                        <td style="padding:5px 6px; text-align:center; border-right:1px solid #ddd;">
                            {{ $stats['lowest'] }}
                        </td>
                        <td style="padding:5px 6px; text-align:center; border-right:1px solid #ddd;">
                            {{ $stats['highest'] }}
                        </td>
                        <td style="padding:5px 6px; text-align:center;">
                            {{ $stats['average'] }}
                        </td>
                    </tr>
                    @endforeach
                    <tr style="border-top:2px solid #000; background:#f5f5f5;">
                        <td style="padding:5px 8px; font-weight:700; border-right:1px solid #ccc;">
                            No in Class: {{ $totalStudents }}
                        </td>
                        <td colspan="2" style="padding:5px 8px; font-weight:700; text-align:right;
                                           border-right:1px solid #ccc;">
                            TOTAL MARKS OBTAINED:
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:800;
                               border-right:1px solid #ccc;">
                            {{ number_format($grandTotal, 0) }}
                        </td>
                        <td style="border-right:1px solid #ccc;"></td>
                        <td colspan="2" style="padding:5px 8px; font-weight:700; text-align:right;
                                           border-right:1px solid #ddd;">
                            Cumulative Percentage:
                        </td>
                        <td style="padding:5px 6px; text-align:center; font-weight:800;">
                            {{ $average }}%
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Behavioural Attributes --}}
        {{-- Keys to Rating --}}
        <div style="border:1.5px solid #000; margin-bottom:8px;">
            <div style="background:#000; color:#fff; font-weight:700; padding:3px 8px;
                    font-size:11px; text-align:center;">
                KEYS TO RATING ON OBSERVABLE BEHAVIOUR
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; font-size:10.5px;
                    border-bottom:1px solid #ccc;">
                <div style="padding:4px 8px; border-right:1px solid #ccc;">
                    5.) Maintain an excellent degree of observable traits
                </div>
                <div style="padding:4px 8px; border-right:1px solid #ccc;">
                    4.) Maintain high level of observable traits
                </div>
                <div style="padding:4px 8px;">
                    3.) Acceptable level of observable traits
                </div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; font-size:10.5px;">
                <div style="padding:4px 8px; border-right:1px solid #ccc;">
                    2.) Shows minimal regards for observable traits
                </div>
                <div style="padding:4px 8px;">
                    1.) Has no regard for observable traits
                </div>
            </div>
        </div>

        {{-- Behavioural Attributes --}}
        <div style="border:1.5px solid #000; margin-bottom:8px;">
            <div style="background:#000; color:#fff; font-weight:700; padding:3px 8px;
                    font-size:11px; text-align:center;">
                SKILLS DEVELOPMENT AND BEHAVIOURAL ATTRIBUTES
            </div>
            <table style="width:100%; border-collapse:collapse; font-size:11px;">
                <thead>
                    <tr style="border-bottom:1px solid #ccc; background:#f5f5f5;">
                        <th style="padding:4px 8px; text-align:left; width:22%; border-right:1px solid #ccc;">
                            PERSONAL DEVELOPMENT
                        </th>
                        <th style="padding:4px 6px; text-align:center; width:6%; border-right:1px solid #ccc;">
                            POINTS
                        </th>
                        <th style="padding:4px 8px; text-align:left; width:18%; border-right:1px solid #ccc;">
                            SENSE OF RESP.
                        </th>
                        <th style="padding:4px 6px; text-align:center; width:6%; border-right:1px solid #ccc;">
                            POINTS
                        </th>
                        <th style="padding:4px 8px; text-align:left; width:18%; border-right:1px solid #ccc;">
                            SOCIAL DEVELOPMENT
                        </th>
                        <th style="padding:4px 6px; text-align:center; width:6%; border-right:1px solid #ccc;">
                            POINTS
                        </th>
                        <th style="padding:4px 8px; text-align:left; width:18%; border-right:1px solid #ccc;">
                            PSYCHOMOTOR (SKILLS) DEV.
                        </th>
                        <th style="padding:4px 6px; text-align:center; width:6%;">POINTS</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $personal = ['obedience'=>'Obedience','honesty'=>'Honesty','self_control'=>'Self-Control','self_reliance'=>'Self-Reliance','use_of_initiative'=>'Use of Initiative'];
                    $sense = ['punctuality'=>'Punctuality','neatness'=>'Neatness','perseverance'=>'Perseverance','attendance_rating'=>'Attendance','attentiveness'=>'Attentiveness'];
                    $social = ['courtesy'=>'Courtesy/Politeness','consideration'=>'Consideration for others','sociability'=>'Sociability/Team Player','consistency'=>'Consistency','accept_responsibility'=>'Accept Responsibility'];
                    $psycho = ['reading_writing'=>'Reading & Writing Skills','verbal_communication'=>'Verbal Communication','sports_games'=>'Sports and Games','inquisitiveness'=>'Inquisitiveness','dexterity'=>'Dexterity (Art & Music)'];
                    $pKeys = array_keys($personal); $sKeys = array_keys($sense);
                    $soKeys = array_keys($social); $pyKeys = array_keys($psycho);
                    @endphp
                    @for($i = 0; $i < 5; $i++)
                        <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:4px 8px; border-right:1px solid #ccc;">
                            {{ array_values($personal)[$i] }}
                        </td>
                        <td style="padding:4px 6px; text-align:center; border-right:1px solid #ccc; font-weight:700;">
                            {{ $meta?->{$pKeys[$i]} ?? '' }}
                        </td>
                        <td style="padding:4px 8px; border-right:1px solid #ccc;">
                            {{ array_values($sense)[$i] }}
                        </td>
                        <td style="padding:4px 6px; text-align:center; border-right:1px solid #ccc; font-weight:700;">
                            {{ $meta?->{$sKeys[$i]} ?? '' }}
                        </td>
                        <td style="padding:4px 8px; border-right:1px solid #ccc;">
                            {{ array_values($social)[$i] }}
                        </td>
                        <td style="padding:4px 6px; text-align:center; border-right:1px solid #ccc; font-weight:700;">
                            {{ $meta?->{$soKeys[$i]} ?? '' }}
                        </td>
                        <td style="padding:4px 8px; border-right:1px solid #ccc;">
                            {{ array_values($psycho)[$i] }}
                        </td>
                        <td style="padding:4px 6px; text-align:center; font-weight:700;">
                            {{ $meta?->{$pyKeys[$i]} ?? '' }}
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>

        {{-- Remarks --}}
        <div style="border:1.5px solid #000; margin-bottom:8px;">
            <div style="background:#000; color:#fff; font-weight:700; padding:3px 8px;
                    font-size:11px; text-align:center;">
                REMARKS AND CONCLUSION
            </div>
            <table style="width:100%; border-collapse:collapse; font-size:11.5px;">
                <tr style="border-bottom:1px solid #ccc;">
                    <td style="padding:5px 8px; font-weight:700; width:30%; border-right:1px solid #ccc;">
                        Class Teacher's Comments:
                    </td>
                    <td style="padding:5px 8px; width:40%; border-right:1px solid #ccc;">
                        {{ $meta?->class_teacher_comment ?? '' }}
                    </td>
                    <td style="padding:5px 8px; font-weight:700; width:30%; text-align:right;">
                        Signature(Class Teacher): ___________
                    </td>
                </tr>
                <tr style="border-bottom:1px solid #ccc;">
                    <td style="padding:5px 8px; font-weight:700; border-right:1px solid #ccc;">
                        Principal's Comments:
                    </td>
                    <td style="padding:5px 8px; border-right:1px solid #ccc;">
                        {{ $meta?->principal_comment ?? '' }}
                    </td>
                    <td style="padding:5px 8px; font-weight:700; text-align:right;">
                        Signature(Principal): ___________
                    </td>
                </tr>
                <tr style="border-bottom:1px solid #ccc;">
                    <td style="padding:5px 8px; font-weight:700; border-right:1px solid #ccc;">
                        Promoted To/ Repeated:
                    </td>
                    <td colspan="2" style="padding:5px 8px;">
                        {{ $meta?->promoted_repeated ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3" style="padding:5px 8px; font-weight:700; text-align:center;">
                        Next Term Begins On: {{ strtoupper($meta?->next_term_date ?? '') }}
                    </td>
                </tr>
            </table>
        </div>

    </div>
</div>
{{-- ==================== END PRINTABLE CARD ==================== --}}

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
    .field-label {
        display: block;
        font-size: 12.5px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 5px;
    }

    .field-input {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        font-size: 13.5px;
        font-family: 'DM Sans', sans-serif;
        color: var(--gray-700);
        background: #fff;
        box-sizing: border-box;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
    }

    textarea.field-input {
        resize: vertical;
    }

    @media print {

        .no-print,
        .sidebar,
        .topbar {
            display: none !important;
        }

        .main {
            margin-left: 0 !important;
        }

        .page {
            padding: 0 !important;
        }

        body {
            background: white !important;
        }

        .print-area {
            display: block !important;
        }

        @page {
            margin: 10mm;
        }
    }
</style>
@endpush