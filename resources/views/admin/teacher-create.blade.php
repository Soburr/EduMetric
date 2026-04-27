@extends('admin.layouts.app')

@section('title', 'Add Teacher')

@section('content')

<div style="margin-bottom:24px;">
    <a href="{{ route('admin.teachers.index') }}"
       style="display:inline-flex; align-items:center; gap:7px; font-size:13.5px;
              font-weight:600; color:var(--gray-500); text-decoration:none; margin-bottom:14px;">
        ← Back to Teachers
    </a>
    <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                font-size:20px; color:var(--blue-900);">➕ Add New Teacher</div>
    <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
        Create a teacher account directly from the admin panel
    </div>
</div>

<div style="max-width:560px;">
    <div class="card">

        @if($errors->any())
            <div style="background:#fee2e2; border:1px solid #fca5a5; padding:14px 18px;
                        border-radius:10px; margin-bottom:20px;">
                <ul style="margin:0; padding-left:18px; color:#b91c1c; font-size:13px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.teachers.store') }}" id="teacherForm">
            @csrf

            <div style="margin-bottom:16px;">
                <label class="field-label">Full Name</label>
                <input class="field-input" type="text" name="name"
                       placeholder="e.g. Mr. Adebayo James"
                       value="{{ old('name') }}" required>
            </div>

            <div style="margin-bottom:16px;">
                <label class="field-label">Email Address</label>
                <input class="field-input" type="email" name="email"
                       placeholder="e.g. adebayo@jgsgs.edu.ng"
                       value="{{ old('email') }}" required>
            </div>

            {{-- Subjects tag input --}}
            <div style="margin-bottom:16px;">
                <label class="field-label">Subject(s)</label>
                <span style="font-size:11.5px; color:var(--gray-400); display:block; margin-bottom:6px;">
                    Press <strong>Enter</strong> or <strong>comma</strong> to add each subject
                </span>
                <div class="tag-input-wrap" id="tagWrap"
                     onclick="document.getElementById('tagInput').focus()">
                    @if(old('subjects'))
                        @foreach(json_decode(old('subjects'), true) ?? [] as $sub)
                            <span class="tag" data-value="{{ $sub }}">
                                {{ $sub }}
                                <span class="tag-remove" onclick="removeTag(this)">×</span>
                            </span>
                        @endforeach
                    @endif
                    <input type="text" id="tagInput" class="tag-text-input"
                           placeholder="e.g. Mathematics…" autocomplete="off">
                </div>
                <input type="hidden" name="subjects" id="subjectsHidden"
                       value="{{ old('subjects', '[]') }}">
            </div>

            {{-- Class teacher toggle --}}
            <div class="toggle-box" id="toggleBox" style="margin-bottom:16px;">
                <div class="toggle-row">
                    <div class="toggle-info">
                        <div class="toggle-title">🏫 Class Teacher</div>
                        <div class="toggle-desc">Assign to manage a specific class</div>
                    </div>
                    <label class="switch">
                        <input type="checkbox" name="is_class_teacher" id="classTeacherToggle"
                               value="1" onchange="toggleClassSelect()"
                               {{ old('is_class_teacher') ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="class-select-wrap {{ old('is_class_teacher') ? 'show' : '' }}"
                     id="classSelectWrap">
                    <label class="field-label" style="margin-top:12px;">Assigned Class</label>
                    <select class="field-input" name="assigned_class_id" id="assignedClassSelect">
                        <option value="">-- Select class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                    {{ old('assigned_class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="margin-bottom:16px;">
                <label class="field-label">Password</label>
                <input class="field-input" type="password" name="password"
                       placeholder="Minimum 6 characters" required>
            </div>

            <div style="margin-bottom:24px;">
                <label class="field-label">Confirm Password</label>
                <input class="field-input" type="password" name="password_confirmation"
                       placeholder="Repeat password" required>
            </div>

            <div style="display:flex; gap:12px;">
                <button type="submit" class="btn-primary">✅ Create Teacher</button>
                <a href="{{ route('admin.teachers.index') }}" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection

@push('styles')
<style>
    .tag-input-wrap {
        border: 1px solid var(--gray-200); border-radius: 8px; padding: 8px 10px;
        display: flex; flex-wrap: wrap; gap: 7px; cursor: text;
        min-height: 46px; align-items: center;
        transition: border-color .2s, box-shadow .2s;
    }
    .tag-input-wrap:focus-within {
        border-color: var(--blue-500);
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }
    .tag {
        background: var(--blue-50); color: var(--blue-700); padding: 4px 10px;
        border-radius: 20px; font-size: 13px; font-weight: 500;
        display: flex; align-items: center; gap: 6px; border: 1px solid var(--blue-100);
    }
    .tag-remove { cursor: pointer; font-size: 15px; line-height: 1; color: var(--blue-400); font-weight: 700; }
    .tag-remove:hover { color: var(--blue-900); }
    .tag-text-input {
        border: none !important; outline: none !important; box-shadow: none !important;
        padding: 4px !important; font-size: 13.5px; flex: 1; min-width: 120px;
        width: auto !important; font-family: inherit; background: transparent;
    }
    .toggle-box { border: 1.5px solid var(--gray-200); border-radius: 10px; padding: 14px 16px; transition: all .2s; }
    .toggle-box.active { border-color: var(--blue-500); background: var(--blue-50); }
    .toggle-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .toggle-info { flex: 1; }
    .toggle-title { font-size: 13.5px; font-weight: 600; color: var(--blue-900); }
    .toggle-desc  { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; flex-shrink: 0; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; inset: 0; background: #cbd5e1; border-radius: 24px; transition: .3s; }
    .slider:before { content: ''; position: absolute; width: 18px; height: 18px; left: 3px; bottom: 3px; background: white; border-radius: 50%; transition: .3s; }
    input:checked + .slider { background: var(--blue-600); }
    input:checked + .slider:before { transform: translateX(20px); }
    .class-select-wrap { display: none; }
    .class-select-wrap.show { display: block; }
</style>
@endpush

@push('scripts')
<script>
    const tagInput    = document.getElementById('tagInput');
    const tagWrap     = document.getElementById('tagWrap');
    const hiddenInput = document.getElementById('subjectsHidden');

    function syncHidden() {
        hiddenInput.value = JSON.stringify([...tagWrap.querySelectorAll('.tag')].map(t => t.dataset.value));
    }

    function addTag(value) {
        const label = value.trim().replace(/,+$/, '').trim();
        if (!label) return;
        const existing = [...tagWrap.querySelectorAll('.tag')].map(t => t.dataset.value.toLowerCase());
        if (existing.includes(label.toLowerCase())) { tagInput.value = ''; return; }
        const tag = document.createElement('span');
        tag.className = 'tag'; tag.dataset.value = label;
        tag.innerHTML = `${label} <span class="tag-remove" onclick="removeTag(this)">×</span>`;
        tagWrap.insertBefore(tag, tagInput);
        syncHidden(); tagInput.value = '';
    }

    function removeTag(el) { el.parentElement.remove(); syncHidden(); }

    tagInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); addTag(this.value); }
        if (e.key === 'Backspace' && this.value === '') {
            const tags = tagWrap.querySelectorAll('.tag');
            if (tags.length > 0) { tags[tags.length - 1].remove(); syncHidden(); }
        }
    });

    tagInput.addEventListener('input', function() { if (this.value.endsWith(',')) addTag(this.value); });

    function toggleClassSelect() {
        const toggle = document.getElementById('classTeacherToggle');
        const wrap   = document.getElementById('classSelectWrap');
        const box    = document.getElementById('toggleBox');
        wrap.classList.toggle('show', toggle.checked);
        box.classList.toggle('active', toggle.checked);
        if (!toggle.checked) document.getElementById('assignedClassSelect').value = '';
    }

    document.getElementById('teacherForm').addEventListener('submit', function(e) {
        const tags = tagWrap.querySelectorAll('.tag');
        if (tags.length === 0) {
            e.preventDefault();
            tagInput.placeholder = '⚠ Add at least one subject';
            tagWrap.style.borderColor = '#ef4444';
            tagInput.focus();
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('classTeacherToggle').checked) toggleClassSelect();
    });
</script>
@endpush