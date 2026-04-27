@extends('layouts.teacher.app')

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

    {{-- LEFT COLUMN --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Account overview card --}}
        <div class="card" style="text-align:center; padding:28px 20px;">
            <div style="width:64px; height:64px; border-radius:16px; background:var(--blue-700);
                        display:flex; align-items:center; justify-content:center;
                        font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:24px; color:#fff; margin:0 auto 14px;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
            </div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:16px; color:var(--blue-900);">
                {{ auth()->user()->name }}
            </div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                {{ auth()->user()->email }}
            </div>
            <div style="margin-top:10px;">
                <span class="module-badge badge-blue">Teacher</span>
                @if($profile?->is_class_teacher)
                    <span class="module-badge badge-green" style="margin-left:6px;">
                        🏫 Class Teacher
                    </span>
                @endif
            </div>

            {{-- Quick stats --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-top:20px;">
                <div style="background:var(--gray-50); border-radius:10px; padding:12px 8px;">
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                font-size:20px; color:var(--blue-900);">{{ $totalTests }}</div>
                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:2px;">Tests Created</div>
                </div>
                <div style="background:var(--gray-50); border-radius:10px; padding:12px 8px;">
                    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                                font-size:20px; color:var(--blue-900);">{{ $totalMaterials }}</div>
                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:2px;">Materials</div>
                </div>
            </div>

            <div style="font-size:12px; color:var(--gray-400); margin-top:16px;">
                Member since {{ auth()->user()->created_at->format('M Y') }}
            </div>
        </div>

        {{-- Subjects display --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:14px; color:var(--blue-900); margin-bottom:12px;">
                📚 Subjects
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                @forelse($profile?->subjects ?? [] as $subject)
                    <span style="background:var(--blue-50); color:var(--blue-700);
                                 padding:5px 12px; border-radius:20px; font-size:13px;
                                 font-weight:500; border:1px solid var(--blue-100);">
                        {{ $subject }}
                    </span>
                @empty
                    <span style="font-size:13px; color:var(--gray-400);">No subjects set.</span>
                @endforelse
            </div>
        </div>

        {{-- Class teacher info --}}
        @if($profile?->is_class_teacher && $profile->assignedClass)
            <div class="card">
                <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                            font-size:14px; color:var(--blue-900); margin-bottom:12px;">
                    🏫 Class Assignment
                </div>
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:40px; height:40px; border-radius:10px; background:var(--blue-50);
                                display:flex; align-items:center; justify-content:center; font-size:20px;">
                        🏫
                    </div>
                    <div>
                        <div style="font-weight:700; color:var(--blue-900); font-size:15px;">
                            {{ $profile->assignedClass->name }}
                        </div>
                        <div style="font-size:12px; color:var(--gray-400); margin-top:2px;">
                            Assigned class
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- RIGHT COLUMN --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- Personal Info Form --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:18px;">
                ✏️ Personal Information
            </div>

            @if($errors->hasAny(['name', 'email']))
                <div style="background:#fee2e2; padding:12px 14px; border-radius:8px;
                            margin-bottom:16px; font-size:13px; color:#b91c1c;">
                    {{ $errors->first('name') ?? $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.profile.update') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px;">
                    <label class="field-label">Full Name</label>
                    <input class="field-input" type="text" name="name"
                           value="{{ old('name', auth()->user()->name) }}" required>
                </div>

                <div style="margin-bottom:20px;">
                    <label class="field-label">Email Address</label>
                    <input class="field-input" type="email" name="email"
                           value="{{ old('email', auth()->user()->email) }}" required>
                </div>

                <button type="submit" class="btn-primary">
                    💾 Save Changes
                </button>
            </form>
        </div>

        {{-- Teaching Info Form --}}
        <div class="card">
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                        font-size:15px; color:var(--blue-900); margin-bottom:18px;">
                📚 Teaching Information
            </div>

            @if($errors->hasAny(['subjects', 'assigned_class_id']))
                <div style="background:#fee2e2; padding:12px 14px; border-radius:8px;
                            margin-bottom:16px; font-size:13px; color:#b91c1c;">
                    {{ $errors->first('subjects') ?? $errors->first('assigned_class_id') }}
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.profile.teaching') }}">
                @csrf @method('PUT')

                {{-- Subjects tag input --}}
                <div style="margin-bottom:16px;">
                    <label class="field-label">Subject(s) You Teach</label>
                    <span style="font-size:11.5px; color:var(--gray-400); display:block; margin-bottom:6px;">
                        Press <strong>Enter</strong> or <strong>comma</strong> to add. Backspace to remove last.
                    </span>
                    <div class="tag-input-wrap" id="tagWrap"
                         onclick="document.getElementById('tagInput').focus()">
                        @foreach($profile?->subjects ?? [] as $subject)
                            <span class="tag" data-value="{{ $subject }}">
                                {{ $subject }}
                                <span class="tag-remove" onclick="removeTag(this)">×</span>
                            </span>
                        @endforeach
                        <input type="text" id="tagInput" class="tag-text-input"
                               placeholder="Add subject…" autocomplete="off">
                    </div>
                    <input type="hidden" name="subjects" id="subjectsHidden"
                           value="{{ json_encode($profile?->subjects ?? []) }}">
                </div>

                {{-- Class teacher toggle --}}
                <div class="toggle-box {{ $profile?->is_class_teacher ? 'active' : '' }}"
                     id="toggleBox" style="margin-bottom:16px;">
                    <div class="toggle-row">
                        <div class="toggle-info">
                            <div class="toggle-title">🏫 I am a Class Teacher</div>
                            <div class="toggle-desc">Manage a specific class and view all their students</div>
                        </div>
                        <label class="switch">
                            <input type="checkbox" name="is_class_teacher" id="classTeacherToggle"
                                   value="1" onchange="toggleClassSelect()"
                                   {{ $profile?->is_class_teacher ? 'checked' : '' }}>
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="class-select-wrap {{ $profile?->is_class_teacher ? 'show' : '' }}"
                         id="classSelectWrap">
                        <label class="field-label" style="margin-top:12px;">Assigned Class</label>
                        <select class="field-input" name="assigned_class_id" id="assignedClassSelect">
                            <option value="">-- Select class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}"
                                        {{ $profile?->assigned_class_id == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    💾 Update Teaching Info
                </button>
            </form>
        </div>

        {{-- Change Password Form --}}
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

            <form method="POST" action="{{ route('teacher.profile.password') }}">
                @csrf @method('PUT')

                <div style="margin-bottom:14px;">
                    <label class="field-label">Current Password</label>
                    <input class="field-input" type="password" name="current_password"
                           placeholder="Enter current password" required>
                </div>

                <div style="margin-bottom:14px;">
                    <label class="field-label">New Password</label>
                    <input class="field-input" type="password" name="password"
                           placeholder="Minimum 6 characters" required>
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

    </div>
</div>

@endsection

@push('styles')
<style>
    .profile-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 768px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }

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

    /* Tag input */
    .tag-input-wrap {
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        cursor: text;
        min-height: 46px;
        align-items: center;
        transition: border-color .2s, box-shadow .2s;
    }

    .tag-input-wrap:focus-within {
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }

    .tag {
        background: var(--blue-50);
        color: var(--blue-700);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--blue-100);
    }

    .tag-remove {
        cursor: pointer;
        font-size: 15px;
        line-height: 1;
        color: var(--blue-400);
        font-weight: 700;
    }

    .tag-remove:hover { color: var(--blue-900); }

    .tag-text-input {
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        padding: 4px !important;
        font-size: 13.5px;
        flex: 1;
        min-width: 120px;
        width: auto !important;
        font-family: inherit;
        background: transparent;
    }

    /* Toggle */
    .toggle-box {
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 14px 16px;
        transition: border-color .2s, background .2s;
    }

    .toggle-box.active {
        border-color: var(--blue-500);
        background: var(--blue-50);
    }

    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .toggle-info { flex: 1; }
    .toggle-title { font-size: 13.5px; font-weight: 600; color: var(--blue-900); }
    .toggle-desc  { font-size: 12px; color: var(--gray-400); margin-top: 2px; }

    .switch {
        position: relative;
        display: inline-block;
        width: 44px; height: 24px;
        flex-shrink: 0;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #cbd5e1;
        border-radius: 24px;
        transition: .3s;
    }

    .slider:before {
        content: '';
        position: absolute;
        width: 18px; height: 18px;
        left: 3px; bottom: 3px;
        background: white;
        border-radius: 50%;
        transition: .3s;
    }

    input:checked + .slider { background: var(--blue-600); }
    input:checked + .slider:before { transform: translateX(20px); }

    .class-select-wrap { display: none; margin-top: 10px; }
    .class-select-wrap.show { display: block; }
</style>
@endpush

@push('scripts')
<script>
    // ── Tag input ─────────────────────────────────────────────────
    const tagInput    = document.getElementById('tagInput');
    const tagWrap     = document.getElementById('tagWrap');
    const hiddenInput = document.getElementById('subjectsHidden');

    function syncHidden() {
        const tags = [...tagWrap.querySelectorAll('.tag')].map(t => t.dataset.value);
        hiddenInput.value = JSON.stringify(tags);
    }

    function addTag(value) {
        const label = value.trim().replace(/,+$/, '').trim();
        if (!label) return;
        const existing = [...tagWrap.querySelectorAll('.tag')]
            .map(t => t.dataset.value.toLowerCase());
        if (existing.includes(label.toLowerCase())) { tagInput.value = ''; return; }
        const tag = document.createElement('span');
        tag.className     = 'tag';
        tag.dataset.value = label;
        tag.innerHTML     = `${label} <span class="tag-remove" onclick="removeTag(this)">×</span>`;
        tagWrap.insertBefore(tag, tagInput);
        syncHidden();
        tagInput.value = '';
    }

    function removeTag(el) { el.parentElement.remove(); syncHidden(); }

    tagInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addTag(this.value); }
        if (e.key === 'Backspace' && this.value === '') {
            const tags = tagWrap.querySelectorAll('.tag');
            if (tags.length > 0) tags[tags.length - 1].remove();
            syncHidden();
        }
    });

    tagInput.addEventListener('input', function () {
        if (this.value.endsWith(',')) addTag(this.value);
    });

    // ── Class teacher toggle ──────────────────────────────────────
    function toggleClassSelect() {
        const toggle = document.getElementById('classTeacherToggle');
        const wrap   = document.getElementById('classSelectWrap');
        const box    = document.getElementById('toggleBox');
        wrap.classList.toggle('show', toggle.checked);
        box.classList.toggle('active', toggle.checked);
        if (!toggle.checked) {
            document.getElementById('assignedClassSelect').value = '';
        }
    }
</script>
@endpush