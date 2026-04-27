<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #1e3a8a, #2563eb);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px 0;
        }
        .card {
            background: #fff;
            width: 100%;
            max-width: 440px;
            padding: 32px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .title { text-align:center; color:#1e3a8a; margin-bottom:4px; font-size:22px; }
        .subtitle { text-align:center; color:#64748b; font-size:13px; margin-bottom:22px; }
        .error-box { background:#fee2e2; padding:10px 14px; border-radius:6px; margin-bottom:14px; }
        .error-box ul { margin:0; padding-left:16px; color:#b91c1c; font-size:13px; }
        .field-label { display:block; font-size:12.5px; font-weight:600; color:#374151; margin-top:16px; margin-bottom:5px; }
        .field-hint { font-size:11.5px; color:#94a3b8; margin-bottom:6px; display:block; }
        input, select {
            width: 100%; padding: 11px 12px; border-radius: 6px;
            border: 1px solid #ddd; font-size: 14px; box-sizing: border-box;
            color: #1f2937; font-family: inherit;
        }
        input:focus, select:focus {
            outline: none; border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
        }
        .tag-input-wrap {
            border: 1px solid #ddd; border-radius: 6px; padding: 8px 10px;
            display: flex; flex-wrap: wrap; gap: 7px; cursor: text;
            min-height: 46px; align-items: center;
            transition: border-color .2s, box-shadow .2s;
        }
        .tag-input-wrap:focus-within { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
        .tag {
            background: #dbeafe; color: #1e40af; padding: 4px 10px;
            border-radius: 20px; font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 6px;
        }
        .tag-remove { cursor:pointer; font-size:15px; line-height:1; color:#3b82f6; font-weight:700; }
        .tag-remove:hover { color:#1e3a8a; }
        .tag-text-input {
            border:none !important; outline:none !important; box-shadow:none !important;
            padding:4px !important; font-size:13.5px; flex:1; min-width:140px;
            width:auto !important; font-family:inherit; background:transparent;
        }
        /* Toggle box */
        .toggle-box {
            margin-top: 18px; border: 1.5px solid #e2e8f0;
            border-radius: 10px; padding: 14px 16px;
            transition: border-color .2s, background .2s;
        }
        .toggle-box.active { border-color: #2563eb; background: #eff6ff; }
        .toggle-row { display:flex; align-items:center; justify-content:space-between; gap:12px; }
        .toggle-info { flex:1; }
        .toggle-title { font-size:13.5px; font-weight:600; color:#1e3a8a; }
        .toggle-desc { font-size:12px; color:#94a3b8; margin-top:2px; }
        .switch { position:relative; display:inline-block; width:44px; height:24px; flex-shrink:0; }
        .switch input { opacity:0; width:0; height:0; }
        .slider { position:absolute; cursor:pointer; inset:0; background:#cbd5e1; border-radius:24px; transition:.3s; }
        .slider:before {
            content:''; position:absolute; width:18px; height:18px;
            left:3px; bottom:3px; background:white; border-radius:50%; transition:.3s;
        }
        input:checked + .slider { background: #2563eb; }
        input:checked + .slider:before { transform: translateX(20px); }
        .class-select-wrap { margin-top:12px; display:none; }
        .class-select-wrap.show { display:block; }
        button[type="submit"] {
            width:100%; margin-top:24px; padding:12px; background:#2563eb;
            color:#fff; border:none; border-radius:6px; font-size:15px;
            font-weight:600; cursor:pointer; font-family:inherit;
        }
        button[type="submit"]:hover { background:#1e40af; }
        .footer { text-align:center; margin-top:16px; font-size:13px; color:#64748b; }
        .footer a { color:#2563eb; text-decoration:none; font-weight:500; }
        @media (max-width:500px) { .card { margin:10px; padding:22px; } }
    </style>
</head>
<body>

<div class="card">
    <h2 class="title">🎓 Teacher Registration</h2>
    <p class="subtitle">Create your JGSGS staff account</p>

    @if ($errors->any())
        <div class="error-box">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/teacher/sign-up" id="registerForm">
        @csrf

        <label class="field-label">Full Name</label>
        <input type="text" name="name" placeholder="e.g. Mr. Adebayo James"
               value="{{ old('name') }}" required>

        <label class="field-label">Email Address</label>
        <input type="email" name="email" placeholder="e.g. adebayo@jgsgs.edu.ng"
               value="{{ old('email') }}" required>

        <label class="field-label">Subject(s) You Teach</label>
        <span class="field-hint">Press <strong>Enter</strong> or <strong>comma</strong> to add each subject.</span>
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

        {{-- Class Teacher Toggle --}}
        <div class="toggle-box" id="toggleBox">
            <div class="toggle-row">
                <div class="toggle-info">
                    <div class="toggle-title">🏫 I am a Class Teacher</div>
                    <div class="toggle-desc">
                        Enable if you are assigned to manage a specific class.
                        More than one teacher can be assigned to the same class.
                    </div>
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
                <label class="field-label" style="margin-top:10px;">Select Your Class</label>
                <select name="assigned_class_id" id="assignedClassSelect">
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

        <label class="field-label">Password</label>
        <input type="password" name="password" placeholder="Minimum 6 characters" required>

        <label class="field-label">Confirm Password</label>
        <input type="password" name="password_confirmation" placeholder="Repeat password" required>

        <button type="submit">Create Account</button>
    </form>

    <div class="footer">
        Already have an account? <a href="/teacher/sign-in">Login here</a>
    </div>
</div>

<script>
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

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const tags = tagWrap.querySelectorAll('.tag');
        if (tags.length === 0) {
            e.preventDefault();
            tagInput.placeholder      = '⚠ Add at least one subject';
            tagInput.style.color      = '#b91c1c';
            tagWrap.style.borderColor = '#ef4444';
            tagInput.focus();
            return;
        }
        const isClassTeacher = document.getElementById('classTeacherToggle').checked;
        const classSelect    = document.getElementById('assignedClassSelect');
        if (isClassTeacher && !classSelect.value) {
            e.preventDefault();
            classSelect.style.borderColor = '#ef4444';
            classSelect.focus();
            alert('Please select your assigned class.');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('classTeacherToggle').checked) toggleClassSelect();
    });
</script>

</body>
</html>