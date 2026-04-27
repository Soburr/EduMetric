@extends('admin.layouts.app')

@section('title', 'Classes')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between;
            flex-wrap:wrap; gap:12px; margin-bottom:24px;">
    <div>
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800;
                    font-size:20px; color:var(--blue-900);">🏫 Classes</div>
        <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
            Manage all school classes
        </div>
    </div>
    <button onclick="toggleForm('addForm')" class="btn-primary">＋ Add Class</button>
</div>

{{-- Add class form --}}
<div id="addForm" style="display:none; margin-bottom:24px;">
    <div class="card">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:700;
                    font-size:15px; color:var(--blue-900); margin-bottom:16px;">
            Add New Class
        </div>
        <form method="POST" action="{{ route('admin.classes.store') }}"
              style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">
            @csrf
            <div style="flex:1; min-width:200px;">
                <label class="field-label">Class Name</label>
                <input class="field-input" type="text" name="name"
                       placeholder="e.g. JSS1A, SS2B"
                       value="{{ old('name') }}" required>
            </div>
            <button type="submit" class="btn-primary">✅ Create Class</button>
            <button type="button" onclick="toggleForm('addForm')" class="btn-secondary">Cancel</button>
        </form>
        @error('name')
            <div style="color:var(--red); font-size:13px; margin-top:8px;">{{ $message }}</div>
        @enderror
    </div>
</div>

{{-- Classes table --}}
@if($classes->count() > 0)
    <div class="card" style="padding:0; overflow:hidden;">
        <table style="width:100%; border-collapse:collapse; font-size:13.5px;">
            <thead>
                <tr style="background:var(--blue-50); border-bottom:1px solid var(--gray-200);">
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">#</th>
                    <th style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Class</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Students</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Tests</th>
                    <th class="hide-mobile" style="padding:13px 20px; text-align:left; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Class Teacher(s)</th>
                    <th style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900); font-family:'Plus Jakarta Sans',sans-serif;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classes as $i => $class)
                    <tr style="border-bottom:1px solid var(--gray-100); transition:background .15s;"
                        onmouseover="this.style.background='var(--blue-50)'"
                        onmouseout="this.style.background=''">
                        <td style="padding:13px 20px; color:var(--gray-400); font-size:12px;">{{ $i + 1 }}</td>
                        <td style="padding:13px 20px;">
                            {{-- Inline edit form --}}
                            <form method="POST" action="{{ route('admin.classes.update', $class->id) }}"
                                  id="editForm_{{ $class->id }}" style="display:none;">
                                @csrf @method('PUT')
                                <div style="display:flex; gap:8px; align-items:center;">
                                    <input type="text" name="name" value="{{ $class->name }}"
                                           class="field-input" style="max-width:140px; padding:7px 10px;">
                                    <button type="submit" class="btn-primary" style="padding:7px 14px; font-size:12.5px;">Save</button>
                                    <button type="button" onclick="toggleEdit({{ $class->id }})"
                                            class="btn-secondary" style="padding:7px 14px; font-size:12.5px;">Cancel</button>
                                </div>
                            </form>
                            <span id="className_{{ $class->id }}"
                                  style="font-weight:700; color:var(--blue-900); font-size:15px;">
                                {{ $class->name }}
                            </span>
                        </td>
                        <td class="hide-mobile" style="padding:13px 20px; text-align:center; font-weight:700; color:var(--blue-900);">
                            {{ $class->students_count }}
                        </td>
                        <td class="hide-mobile" style="padding:13px 20px; text-align:center; color:var(--gray-500);">
                            {{ $class->tests_count }}
                        </td>
                        <td class="hide-mobile" style="padding:13px 20px; color:var(--gray-500); font-size:12.5px;">
                            {{ $class->classTeachers->isNotEmpty() ? $class->classTeachers->join(', ') : '—' }}
                        </td>
                        <td style="padding:13px 20px; text-align:center;">
                            <div style="display:flex; gap:8px; justify-content:center; flex-wrap:wrap;">
                                <button onclick="toggleEdit({{ $class->id }})"
                                        class="btn-secondary" style="padding:6px 12px; font-size:12px;">
                                    ✏️ Edit
                                </button>
                                <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}"
                                      onsubmit="return confirm('Delete {{ $class->name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger" style="padding:6px 12px; font-size:12px;">
                                        🗑 Delete
                                    </button>
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
            <p>No classes yet. Add your first class above.</p>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
    function toggleForm(id) {
        const el = document.getElementById(id);
        el.style.display = el.style.display === 'none' ? 'block' : 'none';
    }

    function toggleEdit(id) {
        const form = document.getElementById(`editForm_${id}`);
        const name = document.getElementById(`className_${id}`);
        const showing = form.style.display === 'none';
        form.style.display = showing ? 'block' : 'none';
        name.style.display = showing ? 'none' : 'inline';
    }

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', () => toggleForm('addForm'));
    @endif
</script>
@endpush