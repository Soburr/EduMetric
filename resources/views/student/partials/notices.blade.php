@if(isset($notices) && count($notices) > 0)
    <div class="notice-list">
        @foreach($notices as $notice)
            <div class="notice-item">
                <div class="notice-dot"
                     style="background:{{ $notice->class_id ? 'var(--blue-500)' : 'var(--amber)' }}">
                </div>
                <div>
                    <div class="notice-text">
                        <strong>{{ $notice->title }}</strong> — {{ $notice->content }}
                    </div>
                    <div class="notice-meta">
                        By {{ $notice->author->name ?? 'School' }}
                        &nbsp;·&nbsp;
                        {{ $notice->created_at->diffForHumans() }}
                        &nbsp;·&nbsp;
                        {{ $notice->class_id ? ($notice->schoolClass->name ?? '') : '🏫 School-wide' }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="notice-empty">
        <div class="notice-icon">📭</div>
        <div class="notice-message">No new notices at the moment.</div>
    </div>
@endif