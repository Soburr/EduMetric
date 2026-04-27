@extends('layouts.app')

@section('title', 'Study Materials')

@section('content')

    <div class="section-head" style="margin-bottom:20px;">
        <div class="section-title">📁 Study Materials</div>
    </div>

    @if(isset($materials) && count($materials) > 0)
        <div class="grid-2">
            @foreach($materials as $material)
                <div class="module-card">
                    <div class="module-card-head">
                        <div class="module-icon-wrap amber">
                            @switch(pathinfo($material->file_name, PATHINFO_EXTENSION))
                                @case('pdf')  📄 @break
                                @case('pptx') 📊 @break
                                @case('docx') 📝 @break
                                @case('mp4')  🎬 @break
                                @default      📁
                            @endswitch
                        </div>
                        <span class="module-badge badge-amber">
                            {{ strtoupper(pathinfo($material->file_name, PATHINFO_EXTENSION)) }}
                        </span>
                    </div>
                    <div>
                        <div class="module-title">{{ $material->title }}</div>
                        <div class="module-desc">
                            Subject: <strong>{{ $material->subject }}</strong><br>
                            Uploaded by {{ $material->uploader_name ?? 'Teacher' }} &nbsp;·&nbsp;
                            {{ \Carbon\Carbon::parse($material->created_at)->format('M d, Y') }}
                        </div>
                    </div>
                    <a href="{{ route('student.materials.download', $material->id) }}" class="module-btn">
                        📥 Download
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No study materials uploaded yet.</p>
                <p style="margin-top:6px; font-size:12.5px;">Your teachers will post notes, slides, and resources here.</p>
            </div>
        </div>
    @endif

@endsection