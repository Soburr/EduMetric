@extends('layouts.app')

@section('title', 'My Profile')

@section('content')

<div style="margin-bottom:24px;">
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">
        👤 My Profile
    </div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Manage your personal information and account settings
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7; color:#15803d; padding:12px 16px;
                border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
        ✅ {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#fee2e2; color:#dc2626; padding:12px 16px;
                border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
        ❌ {{ session('error') }}
    </div>
@endif

<div class="profile-layout">

    {{-- ── LEFT COLUMN ── --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Avatar + overview --}}
        <div class="card" style="text-align:center; padding:28px 20px;">
            <div style="width:64px; height:64px; border-radius:16px;
                        background:var(--blue-700); display:flex; align-items:center;
                        justify-content:center; font-family:'Plus Jakarta Sans',sans-serif;
                        font-weight:800; font-size:24px; color:#fff; margin:0 auto 14px;">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:16px; color:var(--blue-900);">
                {{ $user->name }}
            </div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                {{ $user->student_id }}
            </div>
            <div style="margin-top:10px; display:flex; gap:6px;
                        justify-content:center; flex-wrap:wrap;">
                <span class="module-badge badge-blue">Student</span>
                <span class="module-badge badge-green">
                    {{ $user->studentClass->name ?? '—' }}
                </span>
            </div>

            {{-- Quick stats --}}
            <div style="display:grid; grid-template-columns:1fr 1fr;
                        gap:10px; margin-top:20px;">
                <div style="background:var(--gray-50); border-radius:10px; padding:12px 8px;">
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                font-size:20px; color:var(--blue-900);">
                        {{ $testsTaken }}
                    </div>
                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:2px;">
                        Tests Taken
                    </div>
                </div>
                <div style="background:var(--gray-50); border-radius:10px; padding:12px 8px;">
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                font-size:20px;
                                color:{{ ($avgPerformance ?? 0) >= 50 ? 'var(--green)' : 'var(--red)' }}">
                        {{ $avgPerformance !== null ? $avgPerformance . '%' : '—' }}
                    </div>
                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:2px;">
                        Avg. Score
                    </div>
                </div>
            </div>

            <div style="font-size:12px; color:var(--gray-400); margin-top:16px;">
                Member since {{ $user->created_at->format('M Y') }}
            </div>
        </div>

        {{-- Account info --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:14px; color:var(--blue-900); margin-bottom:14px;">
                📊 Account Info
            </div>
            <div style="display:flex; flex-direction:column; gap:12px;">

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Student ID</span>
                    <span style="font-weight:600; color:var(--blue-900);">
                        {{ $user->student_id }}
                    </span>
                </div>

                <div style="border-top:1px solid var(--gray-100);"></div>

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Class</span>
                    <span style="font-weight:600; color:var(--blue-900);">
                        {{ $user->studentClass->name ?? '—' }}
                    </span>
                </div>

                <div style="border-top:1px solid var(--gray-100);"></div>

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Tests Taken</span>
                    <span style="font-weight:600; color:var(--blue-900);">
                        {{ $testsTaken }}
                    </span>
                </div>

                <div style="border-top:1px solid var(--gray-100);"></div>

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Avg. Performance</span>
                    <span style="font-weight:700;
                        color:{{ ($avgPerformance ?? 0) >= 75 ? 'var(--green)' : (($avgPerformance ?? 0) >= 50 ? 'var(--blue-600)' : 'var(--red)') }}">
                        {{ $avgPerformance !== null ? $avgPerformance . '%' : '—' }}
                    </span>
                </div>

                <div style="border-top:1px solid var(--gray-100);"></div>

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Best Subject</span>
                    <span style="font-weight:600; color:var(--green);">
                        {{ $bestSubject ?? '—' }}
                    </span>
                </div>

                <div style="border-top:1px solid var(--gray-100);"></div>

                <div style="display:flex; justify-content:space-between;
                            align-items:center; font-size:13.5px;">
                    <span style="color:var(--gray-500);">Joined</span>
                    <span style="font-weight:600; color:var(--gray-700);">
                        {{ $user->created_at->format('M d, Y') }}
                    </span>
                </div>

            </div>
        </div>

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Update name --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:18px;">
                ✏️ Personal Information
            </div>

            @if($errors->has('name'))
                <div style="background:#fee2e2; padding:12px 14px; border-radius:8px;
                            margin-bottom:16px; font-size:13px; color:#b91c1c;">
                    {{ $errors->first('name') }}
                </div>
            @endif

            <form method="POST" action="{{ route('student.profile.update') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px;">
                    <label class="field-label">Full Name</label>
                    <input class="field-input" type="text" name="name"
                           value="{{ old('name', $user->name) }}" required>
                </div>

                <div style="margin-bottom:14px;">
                    <label class="field-label">Student ID</label>
                    <input class="field-input" type="text"
                           value="{{ $user->student_id }}" disabled
                           style="background:var(--gray-50); color:var(--gray-400);
                                  cursor:not-allowed;">
                    <span style="font-size:11.5px; color:var(--gray-400); margin-top:4px; display:block;">
                        Student ID cannot be changed. Contact your class teacher if there's an issue.
                    </span>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="field-label">Class</label>
                    <input class="field-input" type="text"
                           value="{{ $user->studentClass->name ?? '—' }}" disabled
                           style="background:var(--gray-50); color:var(--gray-400);
                                  cursor:not-allowed;">
                    <span style="font-size:11.5px; color:var(--gray-400); margin-top:4px; display:block;">
                        Class assignment is managed by your school.
                    </span>
                </div>

                <button type="submit" class="btn-primary">
                    💾 Save Changes
                </button>
            </form>
        </div>

        {{-- Change password --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:18px;">
                🔐 Change Password
            </div>

            @if($errors->hasAny(['current_password', 'password']))
                <div style="background:#fee2e2; padding:12px 14px; border-radius:8px;
                            margin-bottom:16px; font-size:13px; color:#b91c1c;">
                    {{ $errors->first('current_password') ?? $errors->first('password') }}
                </div>
            @endif

            <form method="POST" action="{{ route('student.profile.password') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px;">
                    <label class="field-label">Current Password</label>
                    <input class="field-input" type="password" name="current_password"
                           placeholder="Enter your current password" required>
                </div>

                <div style="margin-bottom:14px;">
                    <label class="field-label">New Password</label>
                    <input class="field-input" type="password" name="password"
                           placeholder="Minimum 4 characters" required>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="field-label">Confirm New Password</label>
                    <input class="field-input" type="password" name="password_confirmation"
                           placeholder="Repeat new password" required>
                </div>

                <button type="submit" class="btn-primary">
                    🔐 Update Password
                </button>
            </form>
        </div>

        {{-- Performance summary --}}
        @if($performance->count() > 0)
            <div class="card">
                <div class="card-title">
                    📈 Subject Performance
                    <a href="{{ route('student.performance') }}" class="see-all">Full view →</a>
                </div>
                <div class="perf-row">
                    @foreach($performance as $subject => $pct)
                        <div class="perf-item">
                            <div class="perf-top">
                                <span class="perf-subj">{{ $subject }}</span>
                                <span class="perf-pct">{{ $pct }}%</span>
                            </div>
                            <div class="perf-track">
                                <div class="perf-fill {{ $pct >= 75 ? 'high' : ($pct >= 50 ? 'medium' : 'low') }}"
                                     style="width:{{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

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
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
    }

    .field-input:focus {
        outline: none;
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }

    .btn-primary {
        padding: 10px 22px;
        background: var(--blue-600);
        color: #fff;
        border: none;
        border-radius: 9px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background .2s;
    }

    .btn-primary:hover { background: var(--blue-700); }
</style>
@endpush