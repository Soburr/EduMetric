@extends('layouts.teacher.app')

@section('title', 'Notices')

@section('content')

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between;
                flex-wrap:wrap; gap:12px; margin-bottom:24px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:var(--blue-900);">
                📢 Notices
            </div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                Post announcements to your classes or the entire school
            </div>
        </div>
        <button onclick="toggleForm()" id="toggleBtn"
                style="padding:10px 20px; background:var(--blue-600); color:#fff;
                       border:none; border-radius:10px; font-size:13.5px; font-weight:600;
                       cursor:pointer; font-family:inherit;">
            ＋ Post Notice
        </button>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#15803d; padding:12px 16px;
                    border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
            ✅ {{ session('success') }}
        </div>
    @endif

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

    {{-- Post Notice Form --}}
    <div id="noticeForm" style="display:none; margin-bottom:24px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">New Notice</div>

            <form method="POST" action="{{ route('teacher.notices.store') }}">
                @csrf

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                    <div>
                        <label class="field-label">Title</label>
                        <input class="field-input" type="text" name="title"
                               placeholder="e.g. Upcoming Test Reminder"
                               value="{{ old('title') }}" required>
                    </div>

                    <div>
                        <label class="field-label">Audience</label>
                        <select class="field-input" name="audience" id="audienceSelect"
                                onchange="toggleClassSelect()" required>
                            <option value="">-- Select audience --</option>
                            <option value="class"      {{ old('audience') == 'class'      ? 'selected' : '' }}>Specific Class(es)</option>
                            <option value="schoolwide" {{ old('audience') == 'schoolwide' ? 'selected' : '' }}>Entire School</option>
                        </select>
                    </div>

                </div>

                {{-- Class selector (shown only when audience = class) --}}
                <div id="classSelector" style="display:none; margin-top:16px;">
                    <label class="field-label">Select Class(es)</label>
                    <span style="font-size:11.5px; color:var(--gray-400);
                                 display:block; margin-bottom:8px;">
                        Choose one or more classes to receive this notice
                    </span>
                    <div style="display:flex; flex-wrap:wrap; gap:10px;">
                        @foreach($classes as $class)
                            <label class="class-check-label" id="noticeClassLabel_{{ $class->id }}"
                                   onclick="toggleNoticeClass({{ $class->id }})">
                                <input type="checkbox" name="class_ids[]"
                                       value="{{ $class->id }}"
                                       id="noticeClassCheck_{{ $class->id }}"
                                       style="accent-color:var(--blue-600); width:15px; height:15px;"
                                       {{ is_array(old('class_ids')) && in_array($class->id, old('class_ids')) ? 'checked' : '' }}>
                                {{ $class->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Content --}}
                <div style="margin-top:16px;">
                    <label class="field-label">Message</label>
                    <textarea class="field-input" name="content" rows="4"
                              placeholder="Write your notice here…"
                              style="resize:vertical; line-height:1.6;"
                              required>{{ old('content') }}</textarea>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:20px;">
                    <button type="button" onclick="toggleForm()"
                            style="padding:10px 22px; border-radius:9px;
                                   border:1.5px solid var(--gray-200); color:var(--gray-500);
                                   font-size:13.5px; font-weight:600; background:#fff;
                                   cursor:pointer; font-family:inherit;">
                        Cancel
                    </button>
                    <button type="submit"
                            style="padding:10px 26px; background:var(--blue-600); color:#fff;
                                   border:none; border-radius:9px; font-size:13.5px;
                                   font-weight:600; cursor:pointer; font-family:inherit;">
                        📢 Post Notice
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Notices List --}}
    @if($notices->count() > 0)

        {{-- Filter --}}
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
            <span style="font-size:13px; font-weight:600; color:var(--gray-500);">Filter:</span>
            <button class="filter-btn active" onclick="filterNotices('all', this)">All</button>
            <button class="filter-btn" onclick="filterNotices('schoolwide', this)">🏫 School-wide</button>
            @foreach($classes as $class)
                @if($notices->where('class_id', $class->id)->count() > 0)
                    <button class="filter-btn"
                            onclick="filterNotices('{{ $class->id }}', this)">
                        {{ $class->name }}
                    </button>
                @endif
            @endforeach
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;">
            @foreach($notices as $notice)
                <div class="card notice-item"
                     data-type="{{ $notice->class_id ? 'class' : 'schoolwide' }}"
                     data-class="{{ $notice->class_id ?? 'schoolwide' }}"
                     style="padding:20px 22px;">

                    <div style="display:flex; align-items:flex-start;
                                justify-content:space-between; gap:14px; flex-wrap:wrap;">

                        <div style="flex:1;">
                            {{-- Badge + title --}}
                            <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:8px;">
                                <span class="module-badge {{ $notice->class_id ? 'badge-blue' : 'badge-amber' }}">
                                    {{ $notice->class_id ? ($notice->schoolClass->name ?? 'Class') : '🏫 School-wide' }}
                                </span>
                                <span style="font-family:'Plus Jakarta Sans',sans-serif;
                                             font-weight:700; font-size:15px; color:var(--blue-900);">
                                    {{ $notice->title }}
                                </span>
                            </div>

                            {{-- Content --}}
                            <div style="font-size:13.5px; color:var(--gray-700);
                                        line-height:1.6; margin-bottom:10px;">
                                {{ $notice->content }}
                            </div>

                            {{-- Meta --}}
                            <div style="font-size:12px; color:var(--gray-400);">
                                Posted by <strong>{{ $notice->author->name ?? 'Unknown' }}</strong>
                                &nbsp;·&nbsp;
                                {{ $notice->created_at->format('M d, Y h:i A') }}
                                &nbsp;·&nbsp;
                                {{ $notice->created_at->diffForHumans() }}
                            </div>
                        </div>

                        {{-- Delete (only own notices) --}}
                        @if($notice->teacher_id === auth()->id())
                            <form method="POST"
                                  action="{{ route('teacher.notices.destroy', $notice->id) }}"
                                  onsubmit="return confirm('Delete this notice?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="padding:7px 14px; border-radius:8px; font-size:12.5px;
                                               font-weight:600; cursor:pointer; font-family:inherit;
                                               border:none; background:#fee2e2; color:#dc2626;
                                               white-space:nowrap;">
                                    🗑 Delete
                                </button>
                            </form>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No notices posted yet.</p>
                <p style="margin-top:6px; font-size:12.5px; color:var(--gray-400);">
                    Click "Post Notice" above to send an announcement to your students.
                </p>
            </div>
        </div>
    @endif

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

    .class-check-label {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border: 1.5px solid var(--gray-200);
        border-radius: 8px;
        cursor: pointer;
        font-size: 13.5px;
        color: var(--gray-700);
        transition: all .2s;
        user-select: none;
        background: #fff;
    }

    .class-check-label:hover {
        border-color: var(--blue-400);
        background: var(--blue-50);
    }

    .filter-btn {
        padding: 6px 14px;
        border-radius: 20px;
        border: 1.5px solid var(--gray-200);
        background: #fff;
        color: var(--gray-500);
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: all .2s;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: #fff;
    }

    @media (max-width: 640px) {
        div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleForm() {
        const form = document.getElementById('noticeForm');
        const btn  = document.getElementById('toggleBtn');
        const open = form.style.display === 'none';
        form.style.display = open ? 'block' : 'none';
        btn.textContent    = open ? '✕ Close' : '＋ Post Notice';
        if (open) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function toggleClassSelect() {
        const val      = document.getElementById('audienceSelect').value;
        const selector = document.getElementById('classSelector');
        selector.style.display = val === 'class' ? 'block' : 'none';
    }

    function toggleNoticeClass(id) {
        setTimeout(() => {
            const check = document.getElementById(`noticeClassCheck_${id}`);
            const label = document.getElementById(`noticeClassLabel_${id}`);
            label.style.borderColor = check.checked ? 'var(--blue-600)' : 'var(--gray-200)';
            label.style.background  = check.checked ? 'var(--blue-50)'  : '#fff';
            label.style.color       = check.checked ? 'var(--blue-700)' : 'var(--gray-700)';
        }, 10);
    }

    function filterNotices(type, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        document.querySelectorAll('.notice-item').forEach(item => {
            if (type === 'all') {
                item.style.display = 'block';
            } else if (type === 'schoolwide') {
                item.style.display = item.dataset.type === 'schoolwide' ? 'block' : 'none';
            } else {
                item.style.display = item.dataset.class == type ? 'block' : 'none';
            }
        });
    }

    // Auto-open form on validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => {
            toggleForm();
            // Re-apply audience toggle if old input exists
            const audience = '{{ old("audience") }}';
            if (audience) {
                document.getElementById('audienceSelect').value = audience;
                toggleClassSelect();
                // Re-apply class highlights
                document.querySelectorAll('input[name="class_ids[]"]').forEach(cb => {
                    if (cb.checked) toggleNoticeClass(cb.value);
                });
            }
        });
    @endif
</script>
@endpush