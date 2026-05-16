@extends('admin.layouts.app')

@section('title', 'Add Student')

@section('content')

<div style="margin-bottom:24px;">
    <a href="{{ route('admin.students.index') }}"
       style="font-size:13px; color:var(--blue-600); text-decoration:none;
              display:inline-flex; align-items:center; gap:5px; margin-bottom:10px;">
        ← Back to Students
    </a>
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">➕ Add New Student</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Create a student account and assign them to a class
    </div>
</div>

@if($errors->any())
    <div style="background:#fee2e2; color:#b91c1c; padding:12px 16px;
                border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
        ⚠️ Please fix the errors below.
    </div>
@endif

<div class="card" style="max-width:520px;">
    <form method="POST" action="{{ route('admin.students.store') }}">
        @csrf

        {{-- Name --}}
        <div style="margin-bottom:18px;">
            <label class="field-label">Full Name <span style="color:var(--red);">*</span></label>
            <input class="field-input" type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. Amara Okafor" required>
            @error('name')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Student ID --}}
        <div style="margin-bottom:18px;">
            <label class="field-label">Student ID <span style="color:var(--red);">*</span></label>
            <input class="field-input" type="text" name="student_id" value="{{ old('student_id') }}"
                   placeholder="e.g. STU/2024/001" required>
            @error('student_id')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Class --}}
        <div style="margin-bottom:18px;">
            <label class="field-label">Class <span style="color:var(--red);">*</span></label>
            <select class="field-input" name="class_id" required>
                <option value="">— Select a class —</option>
                @foreach($classes as $class)
                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                        {{ $class->name }}
                    </option>
                @endforeach
            </select>
            @error('class_id')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div style="margin-bottom:18px;">
            <label class="field-label">Password <span style="color:var(--red);">*</span></label>
            <input class="field-input" type="password" name="password"
                   placeholder="Minimum 4 characters" required>
            @error('password')
                <div style="font-size:12px; color:var(--red); margin-top:5px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div style="margin-bottom:28px;">
            <label class="field-label">Confirm Password <span style="color:var(--red);">*</span></label>
            <input class="field-input" type="password" name="password_confirmation"
                   placeholder="Re-enter password" required>
        </div>

        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit" class="btn-primary" style="padding:10px 28px;">
                ✅ Create Student
            </button>
            <a href="{{ route('admin.students.index') }}" class="btn-secondary"
               style="padding:10px 20px;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection