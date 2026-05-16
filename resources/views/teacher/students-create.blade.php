@extends('layouts.teacher.app')

@section('title', 'Add Student')

@section('content')

<div style="margin-bottom:24px;">
    <a href="{{ route('teacher.students.index') }}"
       style="font-size:13px; color:var(--blue-600); text-decoration:none;
              display:inline-flex; align-items:center; gap:5px; margin-bottom:10px;">
        ← Back to My Students
    </a>
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">
        ➕ Add New Student
    </div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Creating account for <strong>{{ $assignedClass->name }}</strong>
    </div>
</div>

@if($errors->any())
    <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px;
                border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
        ⚠️ Please fix the errors below.
    </div>
@endif

<div class="card" style="max-width:520px;">

    <form method="POST" action="{{ route('teacher.students.store') }}">
        @csrf

        {{-- Name --}}
        <div style="margin-bottom:18px;">
            <label style="display:block; font-size:13px; font-weight:600;
                          color:var(--blue-900); margin-bottom:6px;">
                Full Name <span style="color:var(--red);">*</span>
            </label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. Amara Okafor"
                   required
                   style="width:100%; padding:10px 14px; border-radius:9px;
                          border:1.5px solid {{ $errors->has('name') ? 'var(--red)' : 'var(--gray-200)' }};
                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                          outline:none; box-sizing:border-box; background:#fff;">
            @error('name')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Student ID --}}
        <div style="margin-bottom:18px;">
            <label style="display:block; font-size:13px; font-weight:600;
                          color:var(--blue-900); margin-bottom:6px;">
                Student ID <span style="color:var(--red);">*</span>
            </label>
            <input type="text" name="student_id" value="{{ old('student_id') }}"
                   placeholder="e.g. STU/2024/001"
                   required
                   style="width:100%; padding:10px 14px; border-radius:9px;
                          border:1.5px solid {{ $errors->has('student_id') ? 'var(--red)' : 'var(--gray-200)' }};
                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                          outline:none; box-sizing:border-box; background:#fff;">
            @error('student_id')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div style="margin-bottom:18px;">
            <label style="display:block; font-size:13px; font-weight:600;
                          color:var(--blue-900); margin-bottom:6px;">
                Password <span style="color:var(--red);">*</span>
            </label>
            <input type="password" name="password"
                   placeholder="Minimum 4 characters"
                   required
                   style="width:100%; padding:10px 14px; border-radius:9px;
                          border:1.5px solid {{ $errors->has('password') ? 'var(--red)' : 'var(--gray-200)' }};
                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                          outline:none; box-sizing:border-box; background:#fff;">
            @error('password')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; font-weight:600;
                          color:var(--blue-900); margin-bottom:6px;">
                Confirm Password <span style="color:var(--red);">*</span>
            </label>
            <input type="password" name="password_confirmation"
                   placeholder="Re-enter password"
                   required
                   style="width:100%; padding:10px 14px; border-radius:9px;
                          border:1.5px solid var(--gray-200);
                          font-size:13.5px; font-family:inherit; color:var(--gray-700);
                          outline:none; box-sizing:border-box; background:#fff;">
        </div>

        {{-- Class (read-only indicator) --}}
        <div style="background:var(--blue-50); border:1.5px solid var(--blue-100);
                    border-radius:9px; padding:11px 14px; margin-bottom:24px;
                    font-size:13px; color:var(--blue-700);">
            🏫 This student will be enrolled in <strong>{{ $assignedClass->name }}</strong>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit"
                    style="padding:10px 28px; background:var(--blue-700); color:#fff;
                           border:none; border-radius:9px; font-size:13.5px; font-weight:700;
                           font-family:inherit; cursor:pointer;">
                ✅ Create Student
            </button>
            <a href="{{ route('teacher.students.index') }}"
               style="padding:10px 20px; background:#fff; color:var(--gray-500);
                      border:1.5px solid var(--gray-200); border-radius:9px;
                      font-size:13.5px; font-weight:600; text-decoration:none;
                      display:inline-flex; align-items:center;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection