@extends('admin.layouts.app')

@section('title', 'Teachers')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between;
            flex-wrap:wrap; gap:12px; margin-bottom:24px;">
    <div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:20px; color:var(--blue-900);">👨‍🏫 Teachers</div>
        <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
            Manage all teacher accounts
        </div>
    </div>
    <a href="{{ route('admin.teachers.create') }}" class="btn-primary">＋ Add Teacher</a>
</div>

@if($teachers->count() > 0)
    <div class="card" style="padding:0; overflow:hidden;">

        {{-- Search --}}
        <div style="padding:14px 20px; border-bottom:1px solid var(--gray-100);
                    display:flex; align-items:center; gap:8px; background:var(--gray-50);">
            <span style="color:var(--gray-400);">🔍</span>
            <input type="text" placeholder="Search teacher by name or email…"
                   oninput="filterTable(this.value)"
                   style="border:none; background:none; outline:none; font-size:13.5px;
                          font-family:inherit; color:var(--gray-700); width:100%;">
        </div>

        <table style="width:100%; border-collapse:collapse; font-size:13.5px;" id="teachersTable">
            <thead>
                <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">#</th>
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Teacher</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Subjects</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Tests</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Status</th>
                    <th style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Actions</th>
                </tr>
            </thead>
            <tbody id="teacherRows">
                @foreach($teachers as $i => $teacher)
                    <tr class="teacher-row" style="border-bottom:1px solid var(--gray-100);">
                        <td style="padding:12px 20px; color:var(--gray-400); font-size:12px;">{{ $i + 1 }}</td>
                        <td style="padding:12px 20px;">
                            <div style="display:flex; align-items:center; gap:10px;">
                                <div style="width:34px; height:34px; border-radius:9px; background:var(--blue-800);
                                            display:flex; align-items:center; justify-content:center;
                                            font-weight:700; font-size:12px; color:#fff; flex-shrink:0;
                                            font-family:'Plus Jakarta Sans',sans-serif;">
                                    {{ strtoupper(substr($teacher->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="teacher-name" style="font-weight:600; color:var(--blue-900);">
                                        {{ $teacher->name }}
                                    </div>
                                    <div style="font-size:11.5px; color:var(--gray-400); margin-top:1px;">
                                        {{ $teacher->email }}
                                        @if($teacher->teacherProfile?->is_class_teacher)
                                            &nbsp;·&nbsp;
                                            <span style="color:var(--blue-600); font-weight:500;">
                                                Class Teacher — {{ $teacher->teacherProfile->assignedClass->name ?? '—' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="hide-mobile" style="padding:12px 20px;">
                            <div style="display:flex; flex-wrap:wrap; gap:5px;">
                                @foreach($teacher->teacherProfile?->subjects ?? [] as $subject)
                                    <span class="module-badge badge-blue">{{ $subject }}</span>
                                @endforeach
                                @if(empty($teacher->teacherProfile?->subjects))
                                    <span style="color:var(--gray-400); font-size:12.5px;">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="hide-mobile" style="padding:12px 20px; text-align:center;
                                   font-weight:700; color:var(--blue-900);">
                            {{ $teacher->created_tests_count }}
                        </td>
                        <td class="hide-mobile" style="padding:12px 20px; text-align:center;">
                            <span class="module-badge {{ $teacher->email_verified_at ? 'badge-green' : 'badge-gray' }}">
                                {{ $teacher->email_verified_at ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="padding:12px 20px;">
                            <div style="display:flex; gap:6px; justify-content:center; flex-wrap:wrap;">

                                {{-- Toggle active --}}
                                <form method="POST" action="{{ route('admin.teachers.toggle', $teacher->id) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-secondary"
                                            style="padding:6px 10px; font-size:11.5px; white-space:nowrap;">
                                        {{ $teacher->email_verified_at ? '⏸ Deactivate' : '▶ Activate' }}
                                    </button>
                                </form>

                                {{-- Reset password --}}
                                <button onclick="togglePasswordForm({{ $teacher->id }})"
                                        class="btn-secondary"
                                        style="padding:6px 10px; font-size:11.5px; white-space:nowrap;">
                                    🔐 Password
                                </button>

                                {{-- Delete --}}
                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}"
                                      onsubmit="return confirm('Delete {{ $teacher->name }}? All their tests and materials will be deleted too.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger"
                                            style="padding:6px 10px; font-size:11.5px;">
                                        🗑
                                    </button>
                                </form>

                            </div>

                            {{-- Password reset form (hidden) --}}
                            <div id="pwForm_{{ $teacher->id }}"
                                 style="display:none; margin-top:10px; padding:12px;
                                        background:var(--gray-50); border-radius:10px;
                                        border:1px solid var(--gray-200);">
                                <form method="POST" action="{{ route('admin.teachers.password', $teacher->id) }}">
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
                                                onclick="togglePasswordForm({{ $teacher->id }})"
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
            <p>No teachers yet.</p>
            <a href="{{ route('admin.teachers.create') }}"
               class="btn-primary" style="margin-top:14px; display:inline-flex;">
                ＋ Add First Teacher
            </a>
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

    function filterTable(query) {
        const q = query.toLowerCase();
        document.querySelectorAll('.teacher-row').forEach(row => {
            const name = row.querySelector('.teacher-name')?.textContent.toLowerCase() ?? '';
            row.style.display = name.includes(q) ? '' : 'none';
        });
    }
</script>
@endpush