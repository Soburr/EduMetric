@extends('layouts.app')

@section('title', $test->title)

@push('styles')
<style>
    /* ── Layout ── */
    .exam-wrap {
        max-width: 700px;
        margin: 0 auto;
    }

    /* ── Top bar ── */
    .exam-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 14px;
        padding: 14px 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .exam-meta { line-height: 1.3; }

    .exam-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 16px;
        color: var(--blue-900);
    }

    .exam-sub {
        font-size: 12.5px;
        color: var(--gray-400);
        margin-top: 3px;
    }

    /* ── Timer ── */
    .timer-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--blue-50);
        border: 1.5px solid var(--blue-200, #bfdbfe);
        border-radius: 10px;
        padding: 10px 18px;
        min-width: 130px;
        justify-content: center;
    }

    .timer-box.warning {
        background: #fff7ed;
        border-color: #fed7aa;
        animation: pulse .8s infinite;
    }

    .timer-box.danger {
        background: #fee2e2;
        border-color: #fca5a5;
        animation: pulse .4s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: .7; }
    }

    .timer-icon { font-size: 18px; }

    .timer-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 800;
        font-size: 20px;
        color: var(--blue-700);
        letter-spacing: .03em;
        font-variant-numeric: tabular-nums;
    }

    .timer-box.warning .timer-text { color: #c2410c; }
    .timer-box.danger  .timer-text { color: #dc2626; }

    /* ── Progress ── */
    .progress-wrap {
        margin-bottom: 20px;
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        font-size: 12.5px;
        color: var(--gray-400);
        margin-bottom: 7px;
    }

    .progress-track {
        background: var(--gray-100);
        border-radius: 99px;
        height: 7px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 99px;
        background: linear-gradient(90deg, var(--blue-600), var(--blue-400));
        transition: width .4s cubic-bezier(.4,0,.2,1);
    }

    /* ── Question card ── */
    .question-card {
        background: var(--white);
        border: 1px solid var(--gray-200);
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 18px;
        animation: fadeIn .3s ease;
    }

    @keyframes fadeIn {
        from { opacity:0; transform:translateY(6px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .q-label {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--blue-500);
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .q-text {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 17px;
        color: var(--blue-900);
        line-height: 1.5;
        margin-bottom: 22px;
    }

    /* ── Options ── */
    .options-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 18px;
        border: 1.5px solid var(--gray-200);
        border-radius: 11px;
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }

    .option-item:hover {
        border-color: var(--blue-400);
        background: var(--blue-50);
    }

    .option-item.selected {
        border-color: var(--blue-600);
        background: var(--blue-50);
        box-shadow: 0 0 0 3px rgba(37,99,235,.1);
    }

    .option-item input[type="radio"] {
        display: none;
    }

    .option-bubble {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--gray-300);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-400);
        flex-shrink: 0;
        transition: all .2s;
    }

    .option-item.selected .option-bubble {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: white;
    }

    .option-text {
        font-size: 14.5px;
        color: var(--gray-700);
        line-height: 1.4;
    }

    /* ── Nav buttons ── */
    .exam-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .btn-prev {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 11px 22px;
        background: var(--white);
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--gray-500);
        cursor: pointer;
        font-family: inherit;
        transition: all .2s;
    }

    .btn-prev:hover:not(:disabled) {
        border-color: var(--blue-400);
        color: var(--blue-600);
    }

    .btn-prev:disabled { opacity: .4; cursor: not-allowed; }

    .btn-next {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 11px 26px;
        background: var(--blue-600);
        border: none;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        font-family: inherit;
        transition: background .2s;
    }

    .btn-next:hover { background: var(--blue-700); }

    .btn-submit {
        background: var(--green);
    }

    .btn-submit:hover { background: #059669; }

    /* ── Question dots ── */
    .q-dots {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        justify-content: center;
        margin-bottom: 20px;
    }

    .q-dot {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        border: 1.5px solid var(--gray-200);
        color: var(--gray-400);
        background: var(--white);
        transition: all .15s;
    }

    .q-dot.answered {
        background: var(--blue-600);
        border-color: var(--blue-600);
        color: white;
    }

    .q-dot.current {
        border-color: var(--blue-600);
        color: var(--blue-600);
        font-weight: 800;
    }

    @media (max-width: 600px) {
        .question-card { padding: 20px 16px; }
        .q-text { font-size: 15px; }
        .exam-topbar { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')

<div class="exam-wrap">

    {{-- Top bar: title + timer --}}
    <div class="exam-topbar">
        <div class="exam-meta">
            <div class="exam-title">{{ $test->title }}</div>
            <div class="exam-sub">
                {{ $test->subject }} &nbsp;·&nbsp; {{ $questions->count() }} Questions
            </div>
        </div>
        <div class="timer-box" id="timerBox">
            <span class="timer-icon">⏱</span>
            <span class="timer-text" id="timerDisplay">--:--</span>
        </div>
    </div>

    {{-- Question navigation dots --}}
    <div class="q-dots" id="qDots">
        @foreach($questions as $i => $q)
            <div class="q-dot {{ $i === 0 ? 'current' : '' }}"
                 id="dot_{{ $i }}"
                 onclick="goToQuestion({{ $i }})">
                {{ $i + 1 }}
            </div>
        @endforeach
    </div>

    {{-- Progress bar --}}
    <div class="progress-wrap">
        <div class="progress-info">
            <span id="progressLabel">Question 1 of {{ $questions->count() }}</span>
            <span id="answeredLabel">0 answered</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progressFill" style="width:0%"></div>
        </div>
    </div>

    {{-- Exam form --}}
    <form method="POST" action="{{ route('student.exam.submit', $test->id) }}" id="examForm">
        @csrf

        @foreach($questions as $i => $question)
            <div class="question-card" id="qCard_{{ $i }}"
                 style="{{ $i !== 0 ? 'display:none;' : '' }}">

                <div class="q-label">Question {{ $i + 1 }} of {{ $questions->count() }}</div>
                <div class="q-text">{{ $question->question_text }}</div>

                <div class="options-list">
                    @foreach($question->options as $j => $option)
                        <label class="option-item" id="optLabel_{{ $i }}_{{ $j }}"
                               onclick="selectOption({{ $i }}, {{ $j }}, this)">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $option->id }}"
                                   id="opt_{{ $i }}_{{ $j }}">
                            <div class="option-bubble">{{ chr(65 + $j) }}</div>
                            <span class="option-text">{{ $option->option_text }}</span>
                        </label>
                    @endforeach
                </div>

            </div>
        @endforeach

        {{-- Navigation --}}
        <div class="exam-nav">
            <button type="button" class="btn-prev" id="btnPrev"
                    onclick="prevQuestion()" disabled>
                ← Previous
            </button>

            <button type="button" class="btn-next" id="btnNext"
                    onclick="nextQuestion()">
                Next →
            </button>

            <button type="submit" class="btn-next btn-submit" id="btnSubmit"
                    style="display:none;"
                    onclick="return confirmSubmit()">
                ✅ Submit Exam
            </button>
        </div>

    </form>

</div>

@endsection

@push('scripts')
<script>
    const TOTAL       = {{ $questions->count() }};
    const DURATION    = {{ $test->duration_minutes * 60 }}; // seconds
    let currentIndex  = 0;
    let timeLeft      = DURATION;
    let answered      = new Array(TOTAL).fill(false);
    let timerInterval = null;

    // ── Timer ────────────────────────────────────────────────────
    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            timeLeft--;
            updateTimerDisplay();

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                autoSubmit();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const mins = String(Math.floor(timeLeft / 60)).padStart(2, '0');
        const secs = String(timeLeft % 60).padStart(2, '0');
        document.getElementById('timerDisplay').textContent = `${mins}:${secs}`;

        const box = document.getElementById('timerBox');
        box.classList.remove('warning', 'danger');

        if (timeLeft <= 60)       box.classList.add('danger');
        else if (timeLeft <= 180) box.classList.add('warning');
    }

    function autoSubmit() {
        alert('⏰ Time is up! Your exam is being submitted automatically.');
        document.getElementById('examForm').submit();
    }

    // ── Navigation ───────────────────────────────────────────────
    function showQuestion(index) {
        // Hide all cards
        for (let i = 0; i < TOTAL; i++) {
            document.getElementById(`qCard_${i}`).style.display = 'none';
            document.getElementById(`dot_${i}`).classList.remove('current');
        }

        // Show current
        document.getElementById(`qCard_${index}`).style.display = 'block';
        document.getElementById(`dot_${index}`).classList.add('current');

        currentIndex = index;

        // Prev button
        document.getElementById('btnPrev').disabled = (index === 0);

        // Next vs Submit
        if (index === TOTAL - 1) {
            document.getElementById('btnNext').style.display   = 'none';
            document.getElementById('btnSubmit').style.display = 'flex';
        } else {
            document.getElementById('btnNext').style.display   = 'flex';
            document.getElementById('btnSubmit').style.display = 'none';
        }

        updateProgress();
    }

    function nextQuestion() {
        if (currentIndex < TOTAL - 1) showQuestion(currentIndex + 1);
    }

    function prevQuestion() {
        if (currentIndex > 0) showQuestion(currentIndex - 1);
    }

    function goToQuestion(index) {
        showQuestion(index);
    }

    // ── Option selection ─────────────────────────────────────────
    function selectOption(qIndex, optIndex, labelEl) {
        // Deselect all options for this question
        const card = document.getElementById(`qCard_${qIndex}`);
        card.querySelectorAll('.option-item').forEach(el => el.classList.remove('selected'));

        // Select clicked option
        labelEl.classList.add('selected');

        // Mark question as answered
        answered[qIndex] = true;
        document.getElementById(`dot_${qIndex}`).classList.add('answered');

        updateProgress();
    }

    // ── Progress ─────────────────────────────────────────────────
    function updateProgress() {
        const answeredCount = answered.filter(Boolean).length;
        const pct = Math.round((answeredCount / TOTAL) * 100);

        document.getElementById('progressFill').style.width    = `${pct}%`;
        document.getElementById('progressLabel').textContent   = `Question ${currentIndex + 1} of ${TOTAL}`;
        document.getElementById('answeredLabel').textContent   = `${answeredCount} answered`;
    }

    // ── Submit confirmation ───────────────────────────────────────
    function confirmSubmit() {
        const answeredCount = answered.filter(Boolean).length;
        const unanswered    = TOTAL - answeredCount;

        if (unanswered > 0) {
            return confirm(
                `⚠ You have ${unanswered} unanswered question(s).\n\nAre you sure you want to submit?`
            );
        }
        return confirm('Submit your exam now?');
    }

    // ── Init ─────────────────────────────────────────────────────
    showQuestion(0);
    startTimer();
</script>
@endpush