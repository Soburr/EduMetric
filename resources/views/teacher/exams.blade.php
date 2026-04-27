@extends('layouts.teacher.app')

@section('title', 'My Exams')

@section('content')

    <div class="section-head" style="margin-bottom:20px;">
        <div class="section-title">📝 My Exams</div>
        <a href="{{ route('teacher.exams.create') }}" class="module-btn" style="text-decoration:none;">
            ＋ Create New Exam
        </a>
    </div>

    @if(session('success'))
        <div style="background:#dcfce7; color:#15803d; padding:12px 16px;
                    border-radius:10px; margin-bottom:20px; font-size:13.5px; font-weight:500;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($exams->count() > 0)
        <div style="display:flex; flex-direction:column; gap:14px;">
            @foreach($exams as $exam)
                <div class="card" style="display:flex; align-items:center;
                            justify-content:space-between; flex-wrap:wrap; gap:14px;">
<a href="{{ route('teacher.exams.submissions', $exam->id) }}"
   style="text-decoration:none; flex:1;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div class="module-icon-wrap blue" style="flex-shrink:0;">📝</div>
                        <div>
                            <div style="font-family:'Plus Jakarta Sans',sans-serif;
                                        font-weight:700; font-size:15px; color:var(--blue-900);">
                                {{ $exam->title }}
                            </div>
                            <div style="font-size:12.5px; color:var(--gray-400); margin-top:3px;">
                                {{ $exam->subject }} &nbsp;·&nbsp;
                                {{ $exam->schoolClass->name ?? '—' }} &nbsp;·&nbsp;
                                {{ $exam->questions_count }} questions &nbsp;·&nbsp;
                                {{ $exam->duration_minutes }} mins &nbsp;·&nbsp;
                                {{ $exam->submissions_count }} submission(s)
                            </div>
                        </div>
                    </div>
</a>

                    <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">

                        {{-- Active badge --}}
                        <span class="module-badge {{ $exam->is_active ? 'badge-green' : 'badge-gray' }}">
                            {{ $exam->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        {{-- Toggle active --}}
                        <form method="POST" action="{{ route('teacher.exams.toggle', $exam->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    style="padding:7px 14px; border-radius:8px; font-size:12.5px;
                                           font-weight:600; cursor:pointer; font-family:inherit;
                                           border:1.5px solid var(--gray-200); background:#fff;
                                           color:var(--gray-600);">
                                {{ $exam->is_active ? '⏸ Deactivate' : '▶ Activate' }}
                            </button>
                        </form>

                        {{-- Delete --}}
                        <form method="POST" action="{{ route('teacher.exams.destroy', $exam->id) }}"
                              onsubmit="return confirm('Delete this exam? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    style="padding:7px 14px; border-radius:8px; font-size:12.5px;
                                           font-weight:600; cursor:pointer; font-family:inherit;
                                           border:none; background:#fee2e2; color:#dc2626;">
                                🗑 Delete
                            </button>
                        </form>

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>You haven't created any exams yet.</p>
                <a href="{{ route('teacher.exams.create') }}"
                   style="display:inline-block; margin-top:12px;" class="module-btn">
                    ＋ Create your first exam
                </a>
            </div>
        </div>
    @endif

@endsection