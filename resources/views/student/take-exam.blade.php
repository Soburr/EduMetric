@extends('layouts.app')

@section('title', 'Take a Test')

@section('content')

    <div class="section-head" style="margin-bottom:20px;">
        <div class="section-title">📝 Available Tests</div>
    </div>

    @if(isset($tests) && count($tests) > 0)
        <div class="grid-2">
            @foreach($tests as $test)
                <div class="module-card">
                    <div class="module-card-head">
                        <div class="module-icon-wrap blue">📝</div>
                        <span class="module-badge badge-green">Active</span>
                    </div>
                    <div>
                        <div class="module-title">{{ $test->title }}</div>
                        <div class="module-desc">
                            Subject: <strong>{{ $test->subject }}</strong> &nbsp;·&nbsp;
                            Questions: <strong>{{ $test->questions_count }}</strong> &nbsp;·&nbsp;
                            Duration: <strong>{{ $test->duration_minutes }} mins</strong>
                        </div>
                    </div>
                    <a href="{{ route('student.exam.start', $test->id) }}" class="module-btn">▶ Start Test</a>
                </div>
            @endforeach
        </div>
    @else
        <div class="card">
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No tests have been uploaded by your teacher yet.</p>
                <p style="margin-top:6px; font-size:12.5px;">Check back later — you'll see active tests here when they're available.</p>
            </div>
        </div>
    @endif

@endsection