@extends('layouts.teacher.app')

@section('title', 'Study Materials')

@section('content')

    {{-- Header --}}
    <div style="display:flex; align-items:center; justify-content:space-between;
                flex-wrap:wrap; gap:12px; margin-bottom:24px;">
        <div>
            <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                        font-size:20px; color:var(--blue-900);">
                📁 Study Materials
            </div>
            <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
                Upload and manage learning resources for your classes
            </div>
        </div>
        <button onclick="toggleUploadForm()" id="toggleBtn"
                style="padding:10px 20px; background:var(--blue-600); color:#fff;
                       border:none; border-radius:10px; font-size:13.5px; font-weight:600;
                       cursor:pointer; font-family:inherit;">
            ＋ Upload Material
        </button>
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

    {{-- Upload Form --}}
    <div id="uploadForm" style="display:none; margin-bottom:24px;">
        <div class="card">
            <div class="card-title" style="margin-bottom:20px;">Upload New Material</div>

            @if ($errors->any())
                <div style="background:#fee2e2; border:1px solid #fca5a5; padding:14px 18px;
                            border-radius:10px; margin-bottom:20px;">
                    <ul style="margin:0; padding-left:18px; color:#b91c1c; font-size:13px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('teacher.materials.store') }}"
                  enctype="multipart/form-data">
                @csrf

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                    <div>
                        <label class="field-label">Material Title</label>
                        <input class="field-input" type="text" name="title"
                               placeholder="e.g. Chapter 3 Notes"
                               value="{{ old('title') }}" required>
                    </div>

                    <div>
                        <label class="field-label">Subject</label>
                        @if(count($subjects) > 1)
                            <select class="field-input" name="subject" required>
                                <option value="">-- Select subject --</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}"
                                            {{ old('subject') == $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input class="field-input" type="text" name="subject"
                                   value="{{ $subjects[0] ?? '' }}" readonly
                                   style="background:var(--gray-50); color:var(--gray-500);">
                        @endif
                    </div>

                </div>

                {{-- Multi-class selection --}}
                <div style="margin-top:16px;">
                    <label class="field-label">Assign to Class(es)</label>
                    <span style="font-size:11.5px; color:var(--gray-400);
                                 display:block; margin-bottom:8px;">
                        Select one or more classes to share this material with
                    </span>
                    <div style="display:flex; flex-wrap:wrap; gap:10px;">
                        @foreach($classes as $class)
                            <label class="class-check-label" id="classLabel_{{ $class->id }}"
                                   onclick="toggleClassLabel({{ $class->id }})">
                                <input type="checkbox" name="class_ids[]"
                                       value="{{ $class->id }}"
                                       id="classCheck_{{ $class->id }}"
                                       style="accent-color:var(--blue-600); width:15px; height:15px;"
                                       {{ is_array(old('class_ids')) && in_array($class->id, old('class_ids')) ? 'checked' : '' }}>
                                {{ $class->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- File upload --}}
                <div style="margin-top:16px;">
                    <label class="field-label">File</label>
                    <span style="font-size:11.5px; color:var(--gray-400);
                                 display:block; margin-bottom:8px;">
                        Allowed: PDF, DOCX, PPTX, MP4, MOV, JPG, PNG &nbsp;·&nbsp; Max: 100MB
                    </span>
                    <div id="dropZone" onclick="document.getElementById('fileInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">
                        <div style="font-size:36px; margin-bottom:10px;">📂</div>
                        <div style="font-size:14px; font-weight:600; color:var(--blue-700);">
                            Click to browse or drag & drop
                        </div>
                        <div style="font-size:12.5px; color:var(--gray-400); margin-top:4px;"
                             id="fileLabel">No file selected</div>
                    </div>
                    <input type="file" id="fileInput" name="file"
                           accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.avi,.jpg,.jpeg,.png,.gif"
                           style="display:none;" onchange="updateFileLabel(this)" required>
                </div>

                <div style="display:flex; gap:12px; justify-content:flex-end; margin-top:22px;">
                    <button type="button" onclick="toggleUploadForm()"
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
                        📤 Upload Material
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Materials List --}}
    @if($materials->count() > 0)

        {{-- Filter buttons --}}
        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:16px;">
            <span style="font-size:13px; font-weight:600; color:var(--gray-500);">Filter:</span>
            <button class="filter-btn active" onclick="filterMaterials('all', this)">All</button>
            @foreach($classes as $class)
                @if($materials->where('class_id', $class->id)->count() > 0)
                    <button class="filter-btn"
                            onclick="filterMaterials('{{ $class->id }}', this)">
                        {{ $class->name }}
                    </button>
                @endif
            @endforeach
        </div>

        <div style="display:flex; flex-direction:column; gap:12px;" id="materialsList">
            @foreach($materials as $material)
                <div class="card material-item" data-class="{{ $material->class_id }}"
                     style="display:flex; align-items:center; justify-content:space-between;
                            flex-wrap:wrap; gap:14px; padding:16px 20px;">

                    <div style="display:flex; align-items:center; gap:14px;">
                        {{-- File type icon --}}
                        <div style="width:46px; height:46px; border-radius:11px;
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:22px; flex-shrink:0;
                                    background:{{ match($material->extension) {
                                        'pdf'             => '#fee2e2',
                                        'doc','docx'      => '#dbeafe',
                                        'ppt','pptx'      => '#ffedd5',
                                        'mp4','mov','avi'  => '#f3e8ff',
                                        default           => '#f1f5f9'
                                    } }};">
                            {{ match($material->extension) {
                                'pdf'                    => '📄',
                                'doc','docx'             => '📝',
                                'ppt','pptx'             => '📊',
                                'mp4','mov','avi'         => '🎬',
                                'jpg','jpeg','png','gif'  => '🖼️',
                                default                  => '📁'
                            } }}
                        </div>

                        <div>
                            <div style="font-family:'Plus Jakarta Sans',sans-serif;
                                        font-weight:700; font-size:14.5px; color:var(--blue-900);">
                                {{ $material->title }}
                            </div>
                            <div style="font-size:12px; color:var(--gray-400); margin-top:3px;">
                                {{ $material->subject }} &nbsp;·&nbsp;
                                {{ $material->schoolClass->name ?? '—' }} &nbsp;·&nbsp;
                                {{ strtoupper($material->extension) }} &nbsp;·&nbsp;
                                {{ $material->file_size_formatted }} &nbsp;·&nbsp;
                                Uploaded {{ $material->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('teacher.materials.destroy', $material->id) }}"
                          onsubmit="return confirm('Delete this material? Students will no longer be able to access it.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                                style="padding:7px 14px; border-radius:8px; font-size:12.5px;
                                       font-weight:600; cursor:pointer; font-family:inherit;
                                       border:none; background:#fee2e2; color:#dc2626;">
                            🗑 Delete
                        </button>
                    </form>

                </div>
            @endforeach
        </div>

    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No materials uploaded yet.</p>
                <p style="margin-top:6px; font-size:12.5px; color:var(--gray-400);">
                    Click "Upload Material" above to share resources with your students.
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

    #dropZone {
        border: 2px dashed var(--gray-200);
        border-radius: 12px;
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        background: var(--gray-50);
    }

    #dropZone:hover, #dropZone.drag-over {
        border-color: var(--blue-500);
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
    function toggleUploadForm() {
        const form = document.getElementById('uploadForm');
        const btn  = document.getElementById('toggleBtn');
        const open = form.style.display === 'none';
        form.style.display = open ? 'block' : 'none';
        btn.textContent    = open ? '✕ Close' : '＋ Upload Material';
        if (open) form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // Auto-open form on validation errors
    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => toggleUploadForm());
    @endif

    function toggleClassLabel(id) {
        setTimeout(() => {
            const check = document.getElementById(`classCheck_${id}`);
            const label = document.getElementById(`classLabel_${id}`);
            label.style.borderColor = check.checked ? 'var(--blue-600)' : 'var(--gray-200)';
            label.style.background  = check.checked ? 'var(--blue-50)'  : '#fff';
            label.style.color       = check.checked ? 'var(--blue-700)' : 'var(--gray-700)';
        }, 10);
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[name="class_ids[]"]').forEach(cb => {
            if (cb.checked) toggleClassLabel(cb.value);
        });
    });

    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('drag-over');
    }

    function handleDragLeave() {
        document.getElementById('dropZone').classList.remove('drag-over');
    }

    function handleDrop(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('fileInput').files = dt.files;
            updateFileLabel(document.getElementById('fileInput'));
        }
    }

    function updateFileLabel(input) {
        const file = input.files[0];
        if (file) {
            const size = file.size > 1024 * 1024
                ? (file.size / (1024 * 1024)).toFixed(1) + ' MB'
                : (file.size / 1024).toFixed(0) + ' KB';
            document.getElementById('fileLabel').textContent = `${file.name} (${size})`;
            document.getElementById('fileLabel').style.color = 'var(--blue-600)';
            document.getElementById('dropZone').style.borderColor = 'var(--blue-500)';
        }
    }

    function filterMaterials(classId, btn) {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.querySelectorAll('.material-item').forEach(item => {
            item.style.display = (classId === 'all' || item.dataset.class == classId)
                ? 'flex' : 'none';
        });
    }
</script>
@endpush