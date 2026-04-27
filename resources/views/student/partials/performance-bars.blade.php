@if(isset($performance) && count($performance) > 0)
    <div class="perf-row">
        @foreach($performance as $subject => $pct)
            <div class="perf-item">
                <div class="perf-top">
                    <span class="perf-subj">{{ $subject }}</span>
                    <span class="perf-pct">{{ $pct }}%</span>
                </div>
                <div class="perf-track">
                    <div class="perf-fill {{ $pct >= 75 ? 'high' : ($pct >= 50 ? 'medium' : 'low') }}"
                         style="width: {{ $pct }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
@else
 You do not have any performance data yet. Take some tests to see your progress here!
@endif