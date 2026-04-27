@extends('admin.layouts.app')

@section('title', 'Students')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">👥 Students</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        View and manage all student accounts
    </div>
</div>

{{-- Filter bar --}}
<div class="card" style="margin-bottom:20px; padding:16px 20px;">
    <form method="GET" action="{{ route('admin.students.index') }}"
          style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">

        <div style="flex:1; min-width:180px;">
            <label class="field-label">Search</label>
            <input class="field-input" type="text" name="search"
                   placeholder="Name or Student ID…"
                   value="{{ request('search') }}">
        </div>

        <div style="min-width:160px;">
            <label class="field-label">Filter by Class</label>
            <select class="field-input" name="class_id">
                <option value="">All Classes</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}"
                            {{ request('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-primary" style="padding:10px 20px;">
            🔍 Search
        </button>

        @if(request('search') || request('class_id'))
            <a href="{{ route('admin.students.index') }}" class="btn-secondary" style="padding:10px 16px;">
                ✕ Clear
            </a>
        @endif

    </form>
</div>

{{-- Results count --}}
<div style="font-size:13px; color:var(--gray-400); margin-bottom:12px;">
    {{ $students->count() }} student(s) found
</div>

@if($students->count() > 0)
    <div class="card" style="padding:0; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
            <thead>
                <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">#</th>
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Student</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Class</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Tests</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Avg. Score</th>
                    <th style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $i => $student)
                    <tr style="border-bottom:1px solid var(--gray-100); transition:background .15s;"
                        onmouseover="this.style.background='var(--blue-50)'"
                        onmouseout="this.style.background=''">

                        <td style="padding:12px 20px; color:var(--gray-400); font-size:12px;">{{ $i + 1 }}</td>

                        <td style="padding:12px 20px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:9px; background:var(--blue-600);
                                            display:flex; align-items:center; justify-content:center;
                                            font-weight:700; font-size:12px; color:#fff; flex-shrink:0;
                                            font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600; color:var(--blue-900);">
                                        {{ $student->name }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ $student->student_id }}
                                        <span class="show-mobile">
                                            &nbsp;·&nbsp; {{ $student->studentClass->name ?? '—' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="hide-mobile" style="padding:12px 20px; color:var(--gray-500);">
                            {{ $student->studentClass->name ?? '—' }}
                        </td>

                        <td class="hide-mobile" style="padding:12px 20px; text-align:center;
                                   font-weight:700; color:var(--blue-900);">
                            {{ $student->tests_taken }}
                        </td>

                        <td class="hide-mobile" style="padding:12px 20px; text-align:center;">
                            @if($student->avg_performance !== null)
                                <span style="font-weight:700;
                                    color:{{ $student->avg_performance >= 75 ? 'var(--green)' : ($student->avg_performance >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                                    {{ $student->avg_performance }}%
                                </span>
                            @else
                                <span style="color:var(--gray-400); font-size:12px;">—</span>
                            @endif
                        </td>

                        <td style="padding:12px 20px;">
                            <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">

                                <button onclick="togglePasswordForm('s{{ $student->id }}')"
                                        class="btn-secondary"
                                        style="padding:6px 10px; font-size:11.5px; white-space:nowrap;">
                                    🔐 Password
                                </button>

                                <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}"
                                      onsubmit="return confirm('Delete {{ $student->name }}? All their submissions will be deleted too.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger"
                                            style="padding:6px 10px; font-size:11.5px;">
                                        🗑
                                    </button>
                                </form>

                            </div>

                            {{-- Password reset form --}}
                            <div id="pwForm_s{{ $student->id }}"
                                 style="display:none; margin-top:10px; padding:12px;
                                        background:var(--gray-50); border-radius:10px;
                                        border:1px solid var(--gray-200);">
                                <form method="POST" action="{{ route('admin.students.password', $student->id) }}">
                                    @csrf @method('PUT')
                                    <div style="margin-bottom:8px;">
                                        <label class="field-label">New Password</label>
                                        <input class="field-input" type="password" name="new_password"
                                               placeholder="Min. 6 characters" required
                                               style="padding:8px 10px; font-size:13px;">
                                    </div>
                                    <div style="margin-bottom:10px;">
                                        <label class="field-label">Confirm</label>
                                        <input class="field-input" type="password"
                                               name="new_password_confirmation"
                                               placeholder="Repeat password" required
                                               style="padding:8px 10px; font-size:13px;">
                                    </div>
                                    <div style="display:flex; gap:8px;">
                                        <button type="submit" class="btn-primary"
                                                style="padding:7px 14px; font-size:12.5px;">
                                            ✅ Reset
                                        </button>
                                        <button type="button"
                                                onclick="togglePasswordForm('s{{ $student->id }}')"
                                                class="btn-secondary"
                                                style="padding:7px 14px; font-size:12.5px;">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>

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
            <p>No students found.</p>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    function togglePasswordForm(id) {
        const el = document.getElementById(`pwForm_${id}`);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }
</script>
@endpush