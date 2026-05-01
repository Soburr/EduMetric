@extends('layouts.teacher.app')

@section('title', 'Score Entry')

@section('content')

    <div style="margin-bottom:24px;">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:20px; color:var(--blue-900);">
            📋 Score Entry
        </div>
        <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
            Enter CA and Exam scores for your students
        </div>
    </div>

    {{-- Filter form --}}
    <div class="card" style="margin-bottom:24px;">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:14px; color:var(--blue-900); margin-bottom:16px;">
            🔍 Select Class & Term
        </div>
        <form method="GET" action="{{ route('teacher.scores.index') }}"
            style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; align-items:flex-end;" class="filter-grid">
            <div>
                <label class="field-label">Class</label>
                <select class="field-input" name="class_id" required>
                    <option value="">-- Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label">Subject</label>
                <select class="field-input" name="subject" required>
                    <option value="">-- Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                            {{ $subject }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="field-label">Term</label>
                <select class="field-input" name="term" required>
                    <option value="">-- Term --</option>
                    <option value="first" {{ request('term') == 'first' ? 'selected' : '' }}>First Term</option>
                    <option value="second" {{ request('term') == 'second' ? 'selected' : '' }}>Second Term</option>
                    <option value="third" {{ request('term') == 'third' ? 'selected' : '' }}>Third Term</option>
                </select>
            </div>
            <div>
                <label class="field-label">Session</label>
                <select class="field-input" name="session" required>
                    <option value="">-- Session --</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session }}" {{ request('session') == $session ? 'selected' : '' }}>
                            {{ $session }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column:1/-1; display:flex; gap:10px;">
                <button type="submit" style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                   border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                   font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                    🔍 Load Students
                </button>
                @if(request('class_id'))
                    <a href="{{ route('teacher.scores.index') }}" class="btn-secondary">✕ Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Score entry grid --}}
    @if($selectedClass && $students->count() > 0)

        <div style="display:flex; align-items:center; justify-content:space-between;
                        flex-wrap:wrap; gap:12px; margin-bottom:16px;">
            <div>
                <span style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                                 font-size:15px; color:var(--blue-900);">
                    {{ $selectedClass->name }} — {{ $selectedSubject }}
                </span>
                <span style="font-size:13px; color:var(--gray-400); margin-left:8px;">
                    {{ \App\Models\SubjectScore::termLabel($selectedTerm) }},
                    {{ $selectedSession }}
                </span>
            </div>
            <div style="display:flex; gap:10px; font-size:12.5px; color:var(--gray-400);">
                <span style="background:var(--blue-50); color:var(--blue-600); padding:4px 10px;
                                 border-radius:6px; font-weight:600;">
                    CBT /20 (auto)
                </span>
                <span style="background:rgba(245,158,11,.1); color:var(--amber); padding:4px 10px;
                                 border-radius:6px; font-weight:600;">
                    CA /30
                </span>
                <span style="background:rgba(16,185,129,.1); color:var(--green); padding:4px 10px;
                                 border-radius:6px; font-weight:600;">
                    Exam /50
                </span>
            </div>
        </div>

        <form method="POST" action="{{ route('teacher.scores.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ request('class_id') }}">
            <input type="hidden" name="subject" value="{{ $selectedSubject }}">
            <input type="hidden" name="term" value="{{ $selectedTerm }}">
            <input type="hidden" name="session" value="{{ $selectedSession }}">

            <div class="card" style="padding:0; overflow:hidden; margin-bottom:20px;">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                        <thead>
                            <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                                <th
                                    style="padding:12px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                                    #</th>
                                <th
                                    style="padding:12px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                                    Student</th>
                                <th
                                    style="padding:12px 16px; text-align:center; font-weight:700; color:var(--blue-600); font-family:'Plus Jakarta Sans',sans-serif;">
                                    CBT /20</th>
                                <th
                                    style="padding:12px 16px; text-align:center; font-weight:700; color:var(--amber); font-family:'Plus Jakarta Sans',sans-serif;">
                                    CA /30</th>
                                <th
                                    style="padding:12px 16px; text-align:center; font-weight:700; color:var(--green); font-family:'Plus Jakarta Sans',sans-serif;">
                                    Exam /50</th>
                                <th
                                    style="padding:12px 16px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                                    Total /100</th>
                                <th
                                    style="padding:12px 16px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                                    Grade</th>
                                <th
                                    style="padding:12px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">
                                    Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $i => $student)
                                            @php $score = $scores[$student->id] ?? null; @endphp
                                            <tr style="border-bottom:1px solid var(--gray-100);" id="row_{{ $student->id }}">

                                                <td style="padding:10px 16px; color:var(--gray-400); font-size:12px;">
                                                    {{ $i + 1 }}
                                                </td>

                                                <td style="padding:10px 16px;">
                                                    <div style="font-weight:600; color:var(--blue-900); font-size:13.5px;">
                                                        {{ $student->name }}
                                                    </div>
                                                    <div style="font-size:11.5px; color:var(--gray-400);">
                                                        {{ $student->student_id }}
                                                    </div>
                                                </td>

                                                {{-- CBT — read only --}}
                                                <td style="padding:10px 16px; text-align:center;">
                                                    <input type="hidden" name="scores[{{ $student->id }}][cbt_score]"
                                                        value="{{ $score?->cbt_score ?? 0 }}">
                                                    <span style="font-weight:700; color:var(--blue-600); font-size:14px;">
                                                        {{ $score?->cbt_score ?? '0.00' }}
                                                    </span>
                                                </td>

                                                {{-- CA — editable --}}
                                                <td style="padding:8px 10px; text-align:center;">
                                                    <input type="number" name="scores[{{ $student->id }}][ca_score]"
                                                        value="{{ $score?->ca_score }}" min="0" max="30" step="0.5" placeholder="—"
                                                        oninput="calcTotal({{ $student->id }})" style="width:70px; padding:6px 8px; border-radius:7px;
                                                                          border:1.5px solid var(--gray-200); text-align:center;
                                                                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                                                                          transition:border-color .2s;"
                                                        onfocus="this.style.borderColor='var(--amber)'"
                                                        onblur="this.style.borderColor='var(--gray-200)'">
                                                </td>

                                                {{-- Exam — editable --}}
                                                <td style="padding:8px 10px; text-align:center;">
                                                    <input type="number" name="scores[{{ $student->id }}][exam_score]"
                                                        value="{{ $score?->exam_score }}" min="0" max="50" step="0.5" placeholder="—"
                                                        oninput="calcTotal({{ $student->id }})" style="width:70px; padding:6px 8px; border-radius:7px;
                                                                          border:1.5px solid var(--gray-200); text-align:center;
                                                                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                                                                          transition:border-color .2s;"
                                                        onfocus="this.style.borderColor='var(--green)'"
                                                        onblur="this.style.borderColor='var(--gray-200)'">
                                                </td>

                                                {{-- Total — live calculated --}}
                                                <td style="padding:10px 16px; text-align:center;">
                                                    <span id="total_{{ $student->id }}"
                                                        style="font-family:'Plus Jakarta Sans',sans-serif;
                                                                         font-weight:800; font-size:15px;
                                                                         color:{{ ($score?->total ?? 0) >= 70 ? 'var(--green)' : (($score?->total ?? 0) >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                                                        {{ $score?->total ?? '—' }}
                                                    </span>
                                                </td>

                                                {{-- Grade --}}
                                                <td style="padding:10px 16px; text-align:center;">
                                                    <span id="grade_{{ $student->id }}" class="module-badge {{ match ($score?->grade) {
                                    'A' => 'badge-green',
                                    'B', 'C' => 'badge-blue',
                                    'D', 'E' => 'badge-amber',
                                    default => 'badge-gray'
                                } }}">
                                                        {{ $score?->grade ?? '—' }}
                                                    </span>
                                                </td>

                                                {{-- Remark --}}
                                                <td style="padding:8px 10px;">
                                                    <input type="text" name="scores[{{ $student->id }}][remark]" value="{{ $score?->remark }}"
                                                        placeholder="Optional remark…" style="width:100%; min-width:120px; padding:6px 10px;
                                                                          border-radius:7px; border:1.5px solid var(--gray-200);
                                                                          font-size:12.5px; font-family:inherit; color:var(--gray-700);">
                                                </td>

                                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex; gap:12px; align-items:center;">
                <button type="submit" class="btn-primary">
                    💾 Save All Scores
                </button>
                <span style="font-size:13px; color:var(--gray-400);">
                    {{ $students->count() }} students · CBT scores are auto-filled from online tests
                </span>
            </div>

        </form>

    @elseif(request('class_id') && $students->count() === 0)
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No students found in this class.</p>
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
            box-sizing: border-box;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        .field-input:focus {
            outline: none;
            border-color: var(--blue-500);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .1);
        }

        @media (max-width: 900px) {
            .filter-grid {
                grid-template-columns: 1fr 1fr !important;
            }
        }

        @media (max-width: 560px) {
            .filter-grid {
                grid-template-columns: 1fr !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Live total + grade calculation
        const cbtScores = {
            @foreach($students as $student)
                {{ $student->id }}: {{ $scores[$student->id]?->cbt_score ?? 0 }},
            @endforeach
        };

        function calcTotal(studentId) {
            const cbt = parseFloat(cbtScores[studentId] || 0);
            const ca = parseFloat(document.querySelector(`input[name="scores[${studentId}][ca_score]"]`)?.value || 0);
            const exam = parseFloat(document.querySelector(`input[name="scores[${studentId}][exam_score]"]`)?.value || 0);
            const total = Math.min(100, cbt + ca + exam);

            const totalEl = document.getElementById(`total_${studentId}`);
            const gradeEl = document.getElementById(`grade_${studentId}`);

            totalEl.textContent = total.toFixed(2);
            totalEl.style.color = total >= 70 ? 'var(--green)' : total >= 40 ? 'var(--blue-600)' : 'var(--red)';

            const grade = total >= 70 ? 'A' : total >= 60 ? 'B' : total >= 50 ? 'C' : total >= 45 ? 'D' : total >= 40 ? 'E' : 'F';
            gradeEl.textContent = grade;
            gradeEl.className = 'module-badge ' + (['A'].includes(grade) ? 'badge-green' : ['B', 'C'].includes(grade) ? 'badge-blue' : ['D', 'E'].includes(grade) ? 'badge-amber' : 'badge-gray');
        }
    </script>
@endpush