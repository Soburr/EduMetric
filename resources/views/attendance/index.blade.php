@extends(auth()->user()->role === 'admin' ? 'admin.layouts.app' : 'layouts.teacher.app')

@section('title', 'Attendance')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">🗓️ Attendance</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Mark and track daily class attendance
    </div>
</div>

{{-- Filter --}}
<div class="card" style="margin-bottom:24px;">
    <form method="GET"
          style="display:grid; grid-template-columns:repeat(4,1fr); gap:14px; align-items:flex-end;"
          class="filter-grid">

        @if(auth()->user()->role === 'admin')
            <div>
                <label class="field-label">Class</label>
                <select class="field-input" name="class_id" required>
                    <option value="">-- Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                                {{ $selectedClassId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @else
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <div>
                <label class="field-label">Class</label>
                <div style="padding:10px 12px; background:var(--gray-50); border-radius:8px;
                            font-size:13.5px; font-weight:600; color:var(--blue-900);">
                    {{ $selectedClass->name ?? '—' }}
                </div>
            </div>
        @endif

        <div>
            <label class="field-label">Date</label>
            <input type="date" name="date" class="field-input"
                   value="{{ $selectedDate }}" max="{{ date('Y-m-d') }}" required>
        </div>

        <div>
            <label class="field-label">Term</label>
            <select class="field-input" name="term" required>
                <option value="first"  {{ $selectedTerm == 'first'  ? 'selected' : '' }}>First Term</option>
                <option value="second" {{ $selectedTerm == 'second' ? 'selected' : '' }}>Second Term</option>
                <option value="third"  {{ $selectedTerm == 'third'  ? 'selected' : '' }}>Third Term</option>
            </select>
        </div>

        <div>
            <label class="field-label">Session</label>
            <select class="field-input" name="session" required>
                @foreach($sessions as $session)
                    <option value="{{ $session }}"
                            {{ $selectedSession == $session ? 'selected' : '' }}>
                        {{ $session }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="grid-column:1/-1;">
            <button type="submit"
                    style="background:var(--blue-900); color:#fff; border:none; padding:10px 20px;
                           border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                           font-weight:600; cursor:pointer;">
                🔍 Load Attendance
            </button>
        </div>
    </form>
</div>

@if(session('success'))
    <div style="background:rgba(16,185,129,.1); border:1px solid rgba(16,185,129,.2);
                color:#065f46; border-radius:8px; padding:12px 16px; margin-bottom:20px;
                font-size:13.5px; font-weight:600;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($selectedClass && $students->count() > 0)

    <div style="background:var(--blue-900); color:#fff; border-radius:12px;
                padding:18px 24px; margin-bottom:20px; display:flex;
                align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:17px;">
                {{ $selectedClass->name }} — {{ \Carbon\Carbon::parse($selectedDate)->format('jS F Y') }}
            </div>
            <div style="font-size:13px; opacity:.7; margin-top:3px;">
                {{ $students->count() }} Students &nbsp;·&nbsp;
                Everyone is marked present by default — untick to mark absent
            </div>
        </div>
    </div>

    @if(auth()->user()->role === 'teacher')
    <form method="POST" action="{{ route('teacher.attendance.store') }}" id="attendanceForm">
        @csrf
        <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
        <input type="hidden" name="date"     value="{{ $selectedDate }}">
        <input type="hidden" name="term"     value="{{ $selectedTerm }}">
        <input type="hidden" name="session"  value="{{ $selectedSession }}">
    @endif

        <div class="card" style="padding:0; overflow:hidden; margin-bottom:20px;">
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:12px 16px; text-align:left; font-weight:700; color:var(--blue-900);">#</th>
                        <th style="padding:12px 16px; text-align:left; font-weight:700; color:var(--blue-900);">Student</th>
                        <th style="padding:12px 16px; text-align:center; font-weight:700; color:var(--blue-900);">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $i => $student)
                        @php
                            $record   = $records[$student->id] ?? null;
                            $isAbsent = $record && $record->status === 'absent';
                        @endphp
                        <tr style="border-bottom:1px solid var(--gray-100);" id="row_{{ $student->id }}">
                            <td style="padding:12px 16px; color:var(--gray-400); font-size:12px;">{{ $i + 1 }}</td>
                            <td style="padding:12px 16px;">
                                <div style="font-weight:600; color:var(--blue-900); font-size:13.5px;">
                                    {{ $student->name }}
                                </div>
                                <div style="font-size:11.5px; color:var(--gray-400);">
                                    {{ $student->student_id }}
                                </div>
                            </td>
                            <td style="padding:12px 16px; text-align:center;">
                                @if(auth()->user()->role === 'teacher')
                                    <label style="display:inline-flex; align-items:center; gap:8px; cursor:pointer;
                                                  padding:8px 16px; border-radius:8px;
                                                  background:{{ $isAbsent ? 'rgba(239,68,68,.08)' : 'rgba(16,185,129,.08)' }};
                                                  border:1.5px solid {{ $isAbsent ? 'rgba(239,68,68,.25)' : 'rgba(16,185,129,.25)' }};"
                                           id="label_{{ $student->id }}">
                                        <input type="checkbox" name="absent[]" value="{{ $student->id }}"
                                               {{ $isAbsent ? 'checked' : '' }}
                                               onchange="toggleStatus({{ $student->id }})"
                                               style="display:none;">
                                        <span id="status_{{ $student->id }}"
                                              style="font-weight:700; font-size:12.5px;
                                                     color:{{ $isAbsent ? 'var(--red)' : 'var(--green)' }};">
                                            {{ $isAbsent ? '✕ Absent' : '✓ Present' }}
                                        </span>
                                    </label>
                                @else
                                    <span style="font-weight:700; font-size:12.5px;
                                                 color:{{ $isAbsent ? 'var(--red)' : 'var(--green)' }};">
                                        {{ $isAbsent ? '✕ Absent' : ($record ? '✓ Present' : '— Not marked') }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if(auth()->user()->role === 'teacher')
            <button type="submit"
                    style="background:var(--blue-900); color:#fff; border:none; padding:11px 24px;
                           border-radius:8px; font-size:13.5px; font-family:'DM Sans',sans-serif;
                           font-weight:600; cursor:pointer;">
                💾 Save Attendance
            </button>
    </form>
    @endif

@elseif($selectedClassId)
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
    .field-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-bottom:5px; }
    .field-input {
        width:100%; padding:10px 12px; border-radius:8px; border:1px solid var(--gray-200);
        font-size:13.5px; font-family:'DM Sans',sans-serif; color:var(--gray-700);
        background:#fff; box-sizing:border-box;
    }
    .field-input:focus { outline:none; border-color:var(--blue-500); box-shadow:0 0 0 3px rgba(59,130,246,.1); }
    @media (max-width:900px) { .filter-grid { grid-template-columns:1fr 1fr !important; } }
    @media (max-width:560px) { .filter-grid { grid-template-columns:1fr !important; } }
</style>
@endpush

@push('scripts')
<script>
    function toggleStatus(studentId) {
        const checkbox = document.querySelector(`input[name="absent[]"][value="${studentId}"]`);
        const label    = document.getElementById(`label_${studentId}`);
        const status   = document.getElementById(`status_${studentId}`);

        if (checkbox.checked) {
            status.textContent = '✕ Absent';
            status.style.color = 'var(--red)';
            label.style.background = 'rgba(239,68,68,.08)';
            label.style.borderColor = 'rgba(239,68,68,.25)';
        } else {
            status.textContent = '✓ Present';
            status.style.color = 'var(--green)';
            label.style.background = 'rgba(16,185,129,.08)';
            label.style.borderColor = 'rgba(16,185,129,.25)';
        }
    }
</script>
@endpush