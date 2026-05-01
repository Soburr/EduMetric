@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'layouts.teacher.app')

@section('title', 'Broadsheet')

@section('content')

    <div style="display:flex; align-items:center; justify-content:space-between;
                flex-wrap:wrap; gap:12px; margin-bottom:24px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:var(--blue-900);">📊 Broadsheet</div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                Full class result sheet per term
            </div>
        </div>
        @if($selectedClass && $broadsheet->count() > 0)
            <button onclick="window.print()" style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                       border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                       font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px;">
                🖨️ Print
            </button>
        @endif
    </div>

    {{-- Filter --}}
    <div class="card" style="margin-bottom:24px;">
        <form method="GET" action="{{ route('teacher.broadsheet.index') }}"
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

            <div>
                <button type="submit" style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                   border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                   font-weight:600; cursor:pointer; display:flex; align-items:center; gap:6px; width:100%;">
                    📊 Load Broadsheet
                </button>
            </div>

        </form>
    </div>

    {{-- Broadsheet table --}}
    @if($selectedClass && $broadsheet->count() > 0)

        {{-- Header info --}}
        <div style="background:var(--blue-900); color:#fff; border-radius:12px;
                        padding:18px 24px; margin-bottom:20px; display:flex;
                        align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
            <div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:17px;">
                    {{ $selectedClass->name }} — {{ \App\Models\SubjectScore::termLabel($selectedTerm) }}
                </div>
                <div style="font-size:13px; opacity:.7; margin-top:3px;">
                    Session: {{ $selectedSession }} &nbsp;·&nbsp;
                    {{ $broadsheet->count() }} Students &nbsp;·&nbsp;
                    {{ $subjects->count() }} Subjects
                </div>
            </div>
            <div style="font-size:12px; opacity:.6;">
                CBT/20 · CA/30 · Exam/50 · Total/100
            </div>
        </div>

        <div style="overflow-x:auto;" class="print-area">
            <table style="width:100%; border-collapse:collapse; font-size:12.5px; min-width:800px;">

                {{-- Header row --}}
                <thead>
                    {{-- Subject names row --}}
                    <tr style="background:var(--blue-900); color:#fff;">
                        <th style="padding:10px 14px; text-align:left; font-family:'Plus Jakarta Sans',sans-serif;
                                       font-weight:700; white-space:nowrap; min-width:180px;">
                            Student
                        </th>
                        @foreach($subjects as $subject)
                            <th colspan="4" style="padding:10px 8px; text-align:center; font-weight:700;
                                               border-left:2px solid rgba(255,255,255,.15); font-size:12px;
                                               white-space:nowrap; font-family:'Plus Jakarta Sans',sans-serif;">
                                {{ $subject }}
                            </th>
                        @endforeach
                        <th style="padding:10px 12px; text-align:center; font-weight:700;
                                       border-left:2px solid rgba(255,255,255,.2);
                                       font-family:'Plus Jakarta Sans',sans-serif; white-space:nowrap;">
                            Total
                        </th>
                        <th style="padding:10px 12px; text-align:center; font-weight:700;
                                       font-family:'Plus Jakarta Sans',sans-serif;">Avg</th>
                        <th style="padding:10px 12px; text-align:center; font-weight:700;
                                       font-family:'Plus Jakarta Sans',sans-serif;">Pos</th>
                    </tr>

                    {{-- Sub-header: CBT / CA / Exam / Total per subject --}}
                    <tr style="background:var(--blue-800); color:rgba(255,255,255,.8); font-size:11px;">
                        <th style="padding:6px 14px;"></th>
                        @foreach($subjects as $subject)
                            <th style="padding:6px 6px; text-align:center; border-left:2px solid rgba(255,255,255,.1);">CBT</th>
                            <th style="padding:6px 6px; text-align:center;">CA</th>
                            <th style="padding:6px 6px; text-align:center;">Exam</th>
                            <th style="padding:6px 6px; text-align:center;">Tot</th>
                        @endforeach
                        <th style="padding:6px 12px; border-left:2px solid rgba(255,255,255,.1);"></th>
                        <th style="padding:6px 12px;"></th>
                        <th style="padding:6px 12px;"></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($broadsheet as $row)
                        <tr style="border-bottom:1px solid var(--gray-100);
                                           background:{{ $loop->odd ? '#fff' : 'var(--gray-50)' }};"
                            onmouseover="this.style.background='var(--blue-50)'"
                            onmouseout="this.style.background='{{ $loop->odd ? '#fff' : 'var(--gray-50)' }}'">

                            {{-- Student name --}}
                            <td style="padding:10px 14px; font-weight:600; color:var(--blue-900);
                                               white-space:nowrap;">
                                {{ $row['student']->name }}
                                <div style="font-size:11px; color:var(--gray-400); font-weight:400;">
                                    {{ $row['student']->student_id }}
                                </div>
                            </td>

                            {{-- Subject scores --}}
                            @foreach($subjects as $subject)
                                        @php $s = $row['scores'][$subject] ?? null; @endphp
                                        <td style="padding:8px 6px; text-align:center; color:var(--gray-500);
                                   border-left:2px solid var(--gray-100);">
                                            {{ $s?->cbt_score ? number_format($s->cbt_score, 0) : '—' }}
                                        </td>
                                        <td style="padding:8px 6px; text-align:center; color:var(--gray-500);">
                                            {{ $s?->ca_score ? number_format($s->ca_score, 0) : '—' }}
                                        </td>
                                        <td style="padding:8px 6px; text-align:center; color:var(--gray-500);">
                                            {{ $s?->exam_score ? number_format($s->exam_score, 0) : '—' }}
                                        </td>
                                        <td
                                            style="padding:8px 6px; text-align:center; font-weight:700;
                                                        color:{{ ($s?->total ?? 0) >= 70 ? 'var(--green)' : (($s?->total ?? 0) >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                                            {{ $s ? number_format($s->total, 0) : '—' }}
                                            @if($s?->grade)

                                            @endif
                                        </td>
                            @endforeach

                            {{-- Grand total --}}
                            <td style="padding:10px 12px; text-align:center; font-weight:800;
                                               font-family:'Plus Jakarta Sans',sans-serif; font-size:14px;
                                               color:var(--blue-900); border-left:2px solid var(--gray-200);">
                                {{ number_format($row['grand_total'], 0) }}
                            </td>

                            {{-- Average --}}
                            <td
                                style="padding:10px 12px; text-align:center; font-weight:700;
                                               color:{{ $row['average'] >= 70 ? 'var(--green)' : ($row['average'] >= 40 ? 'var(--blue-600)' : 'var(--red)') }}">
                                {{ number_format($row['average'], 0) }}%
                            </td>

                            {{-- Position --}}
                            <td style="padding:10px 12px; text-align:center;">
                                <span style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                                     font-size:14px; color:var(--blue-900);">
                                    {{ $row['position'] ?? '—' }}
                                    @if(($row['position'] ?? 0) <= 3)
                                        <span style="font-size:13px;">
                                            {{ $row['position'] == 1 ? '🥇' : ($row['position'] == 2 ? '🥈' : '🥉') }}
                                        </span>
                                    @endif
                                </span>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

        {{-- Legend --}}
        <div style="margin-top:16px; display:flex; gap:16px; flex-wrap:wrap; font-size:12px; color:var(--gray-400);">
            <span>🟢 A = 70–100</span>
            <span>🔵 B = 60–69</span>
            <span>🔵 C = 50–59</span>
            <span>🟡 D = 45–49</span>
            <span>🟡 E = 40–44</span>
            <span>🔴 F = 0–39</span>
        </div>

    @elseif(request('class_id'))
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No scores found for this selection. Make sure scores have been entered first.</p>
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

        @media print {

            .sidebar,
            .topbar,
            form,
            .btn-secondary,
            .btn-primary {
                display: none !important;
            }

            .page {
                padding: 0 !important;
            }

            .main {
                margin-left: 0 !important;
            }

            body {
                background: white !important;
            }

            .print-area {
                overflow: visible !important;
            }
        }
    </style>
@endpush