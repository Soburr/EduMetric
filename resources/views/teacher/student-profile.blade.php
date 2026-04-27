@extends('layouts.teacher.app')

@section('title', $student->name . ' – Profile')

@section('content')

<div style="max-width:780px; margin:0 auto;">

    {{-- Back button --}}
    <a href="{{ route('teacher.students.index') }}"
       style="display:inline-flex; align-items:center; gap:7px; font-size:13.5px;
              font-weight:600; color:var(--gray-500); text-decoration:none; margin-bottom:20px;">
        ← Back to My Students
    </a>

    @if(session('success'))
        <div style="background:#dcfce7; color:#15803d; padding:12px 16px;
                    border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Profile header --}}
    <div class="card" style="margin-bottom:20px; display:flex; align-items:center;
                              justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:56px; height:56px; border-radius:14px; background:var(--blue-700);
                        display:flex; align-items:center; justify-content:center;
                        font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:#fff; flex-shrink:0;">
                {{ strtoupper(substr($student->name, 0, 2)) }}
            </div>
            <div>
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                            font-size:18px; color:var(--blue-900);">
                    {{ $student->name }}
                </div>
                <div style="font-size:13px; color:var(--gray-400); margin-top:3px;">
                    ID: {{ $student->student_id }}
                    &nbsp;·&nbsp;
                    Class: {{ $student->studentClass->name ?? '—' }}
                </div>
            </div>
        </div>

        {{-- Overall badge --}}
        <div style="text-align:center; background:var(--blue-50); border:1.5px solid var(--blue-100);
                    border-radius:12px; padding:12px 20px;">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:24px; color:var(--blue-700);">
                {{ $avgPerformance !== null ? $avgPerformance . '%' : '—' }}
            </div>
            <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">Overall Avg.</div>
        </div>
    </div>

    <div class="grid-2" style="margin-bottom:20px;">

        {{-- Performance per subject --}}
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Subject Performance</div>
            @if($performance->count() > 0)
                <div class="perf-row">
                    @foreach($performance as $subject => $data)
                        <div class="perf-item">
                            <div class="perf-top">
                                <span class="perf-subj">{{ $subject }}</span>
                                <span class="perf-pct">{{ $data['avg'] }}%</span>
                            </div>
                            <div class="perf-track">
                                <div class="perf-fill {{ $data['avg'] >= 75 ? 'high' : ($data['avg'] >= 50 ? 'medium' : 'low') }}"
                                     style="width:{{ $data['avg'] }}%"></div>
                            </div>
                            <div style="font-size:11.5px; color:var(--gray-400); margin-top:3px;">
                                {{ $data['count'] }} test(s) taken
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state" style="padding:16px;">
                    <div class="empty-icon" style="font-size:24px;">📭</div>
                    <p style="font-size:13px;">No test results yet.</p>
                </div>
            @endif
        </div>

        {{-- Stats + Password Reset --}}
        <div style="display:flex; flex-direction:column; gap:16px;">

            {{-- Quick stats --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:14px;">Quick Stats</div>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; font-size:13.5px;">
                        <span style="color:var(--gray-500);">Tests Taken</span>
                        <span style="font-weight:700; color:var(--blue-900);">{{ $submissions->count() }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13.5px;">
                        <span style="color:var(--gray-500);">Best Subject</span>
                        <span style="font-weight:700; color:var(--green);">{{ $bestSubject ?? '—' }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:13.5px;">
                        <span style="color:var(--gray-500);">Last Activity</span>
                        <span style="font-weight:600; color:var(--gray-700);">
                            {{ $submissions->first()?->created_at->diffForHumans() ?? '—' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Password Reset --}}
            <div class="card">
                <div class="card-title" style="margin-bottom:14px;">🔐 Reset Password</div>
                <div style="font-size:13px; color:var(--gray-400); margin-bottom:14px;">
                    Use this to help a student who has forgotten their password.
                </div>

                <form method="POST"
                      action="{{ route('teacher.students.password', $student->id) }}"
                      id="passwordForm">
                    @csrf @method('PUT')

                    @if($errors->has('new_password'))
                        <div style="background:#fee2e2; color:#b91c1c; padding:10px; border-radius:8px;
                                    font-size:13px; margin-bottom:12px;">
                            {{ $errors->first('new_password') }}
                        </div>
                    @endif

                    <label class="field-label">New Password</label>
                    <input class="field-input" type="password" name="new_password"
                           placeholder="Min. 4 characters" required>

                    <label class="field-label" style="margin-top:10px;">Confirm Password</label>
                    <input class="field-input" type="password" name="new_password_confirmation"
                           placeholder="Repeat password" required style="margin-top:4px;">

                    <button type="submit"
                            onclick="return confirm('Reset password for {{ $student->name }}?')"
                            style="margin-top:14px; width:100%; padding:10px; background:var(--blue-600);
                                   color:#fff; border:none; border-radius:9px; font-size:13.5px;
                                   font-weight:600; cursor:pointer; font-family:inherit;">
                        🔐 Reset Password
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Test history --}}
    @if($submissions->count() > 0)
        <div class="card">
            <div class="card-title" style="margin-bottom:16px;">Test History</div>
            <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
                <thead>
                    <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                        <th style="padding:11px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Subject</th>
                        <th style="padding:11px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Test</th>
                        <th style="padding:11px 16px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Score</th>
                        <th style="padding:11px 16px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Grade</th>
                        <th style="padding:11px 16px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        @php
                            $pct = $submission->percentage;
                            $grade = match(true) {
                                $pct >= 80 => 'A',
                                $pct >= 70 => 'B',
                                $pct >= 60 => 'C',
                                $pct >= 50 => 'D',
                                default    => 'F',
                            };
                        @endphp
                        <tr style="border-bottom:1px solid var(--gray-100);">
                            <td style="padding:11px 16px; color:var(--gray-700);">{{ $submission->test->subject ?? '—' }}</td>
                            <td style="padding:11px 16px; color:var(--gray-500);">{{ $submission->test->title ?? '—' }}</td>
                            <td style="padding:11px 16px; text-align:center; font-weight:700;
                                color:{{ $pct >= 75 ? 'var(--green)' : ($pct >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                                {{ $submission->score }}/{{ $submission->total }} ({{ $pct }}%)
                            </td>
                            <td style="padding:11px 16px; text-align:center;">
                                <span class="module-badge {{ in_array($grade, ['A']) ? 'badge-green' : (in_array($grade, ['B','C']) ? 'badge-blue' : 'badge-amber') }}">
                                    {{ $grade }}
                                </span>
                            </td>
                            <td style="padding:11px 16px; color:var(--gray-400); font-size:12.5px;">
                                {{ $submission->created_at->format('M d, Y') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>

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
        transition: border-color .2s, box-shadow .2s;
    }
    .field-input:focus {
        outline: none;
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
</style>
@endpush