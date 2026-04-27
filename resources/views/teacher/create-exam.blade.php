@extends('layouts.teacher.app')

@section('title', 'Create Exam')

@section('content')

<div style="max-width:860px; margin:0 auto;">

    {{-- Page Header --}}
    <div style="margin-bottom:24px;">
        <div style="font-family:'Plus Jakarta Sans',sans-serif; font-weight:800; font-size:20px; color:var(--blue-900);">
            📝 Create New Exam
        </div>
        <div style="font-size:13px; color:var(--gray-400); margin-top:4px;">
            Fill in the exam details, add your questions, then save or activate immediately.
        </div>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div style="background:#fee2e2; border:1px solid #fca5a5; padding:14px 18px; border-radius:10px; margin-bottom:20px;">
            <div style="font-weight:600; color:#b91c1c; font-size:13.5px; margin-bottom:6px;">Please fix the following:</div>
            <ul style="margin:0; padding-left:18px; color:#b91c1c; font-size:13px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teacher.exam.store') }}" id="examForm">
        @csrf

        {{-- ── SECTION 1: Exam Details ── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-title">Exam Details</div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">

                <div>
                    <label class="field-label">Exam Title</label>
                    <input class="field-input" type="text" name="title"
                           placeholder="e.g. Chapter 3 Quiz" value="{{ old('title') }}" required>
                </div>

                <div>
                    <label class="field-label">Subject</label>
                    {{-- Auto-filled from teacher profile, read-only --}}
                    @if(count($subjects) > 1)
                        <select class="field-input" name="subject" required>
                            <option value="">-- Select subject --</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject }}" {{ old('subject') == $subject ? 'selected' : '' }}>
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

                <div>
                    <label class="field-label">Assign to Class</label>
                    <select class="field-input" name="class_id" required>
                        <option value="">-- Select class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="field-label">Duration (minutes)</label>
                    <input class="field-input" type="number" name="duration_minutes"
                           placeholder="e.g. 30" min="5" max="180"
                           value="{{ old('duration_minutes', 30) }}" required>
                </div>

            </div>

            {{-- Activate immediately toggle --}}
            <div style="margin-top:18px; display:flex; align-items:center; gap:10px;">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active') ? 'checked' : '' }}
                       style="width:16px; height:16px; accent-color:var(--blue-600); cursor:pointer;">
                <label for="is_active" style="font-size:13.5px; color:var(--gray-700); cursor:pointer;">
                    <strong>Activate immediately</strong> — students will see this exam as soon as it's saved
                </label>
            </div>
        </div>

        {{-- ── SECTION 2: Questions ── --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-title" style="margin-bottom:20px;">
                Questions
                <span id="questionCount"
                      style="background:var(--blue-50); color:var(--blue-600);
                             font-size:12px; font-weight:600; padding:3px 10px;
                             border-radius:20px; margin-left:8px;">
                    1 question
                </span>
            </div>

            {{-- Questions container --}}
            <div id="questionsContainer"></div>

            {{-- Add question button --}}
            <button type="button" onclick="addQuestion()"
                    style="display:flex; align-items:center; gap:8px;
                           background:var(--blue-50); color:var(--blue-600);
                           border:1.5px dashed var(--blue-400); border-radius:10px;
                           padding:11px 20px; font-size:13.5px; font-weight:600;
                           cursor:pointer; width:100%; justify-content:center;
                           margin-top:4px; font-family:inherit; transition:all .2s;">
                ＋ Add Another Question
            </button>
        </div>

        {{-- ── SUBMIT ── --}}
        <div style="display:flex; gap:12px; justify-content:flex-end; margin-bottom:40px;">
            <a href="{{ route('teacher.dashboard') }}"
               style="padding:11px 24px; border-radius:9px; border:1.5px solid var(--gray-200);
                      color:var(--gray-500); font-size:14px; font-weight:600;
                      text-decoration:none; font-family:'Plus Jakarta Sans',sans-serif;">
                Cancel
            </a>
            <button type="submit"
                    style="padding:11px 28px; background:var(--blue-600); color:#fff;
                           border:none; border-radius:9px; font-size:14px; font-weight:600;
                           cursor:pointer; font-family:'Plus Jakarta Sans',sans-serif;">
                💾 Save Exam
            </button>
        </div>

    </form>
</div>

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

    /* Question block */
    .question-block {
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 16px;
        background: var(--gray-50);
        position: relative;
        animation: fadeSlideIn .25s ease;
    }

    @keyframes fadeSlideIn {
        from { opacity:0; transform:translateY(-8px); }
        to   { opacity:1; transform:translateY(0); }
    }

    .question-block:focus-within {
        border-color: var(--blue-400);
        background: #fff;
    }

    .q-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .q-number {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        font-size: 13px;
        color: var(--blue-700);
        background: var(--blue-50);
        padding: 3px 10px;
        border-radius: 20px;
    }

    .q-remove {
        background: #fee2e2;
        color: #dc2626;
        border: none;
        border-radius: 7px;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background .2s;
    }

    .q-remove:hover { background: #fecaca; }

    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 12px;
    }

    .option-row {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid var(--gray-200);
        border-radius: 8px;
        padding: 8px 12px;
        transition: border-color .2s;
    }

    .option-row:focus-within {
        border-color: var(--blue-400);
    }

    .option-row.correct-selected {
        border-color: var(--green);
        background: rgba(16,185,129,.04);
    }

    .option-radio {
        accent-color: var(--green);
        width: 15px;
        height: 15px;
        flex-shrink: 0;
        cursor: pointer;
    }

    .option-letter {
        font-size: 12px;
        font-weight: 700;
        color: var(--blue-600);
        width: 16px;
        flex-shrink: 0;
    }

    .option-input {
        border: none;
        outline: none;
        font-size: 13px;
        font-family: 'DM Sans', sans-serif;
        color: var(--gray-700);
        flex: 1;
        background: transparent;
        min-width: 0;
    }

    .correct-hint {
        font-size: 11.5px;
        color: var(--gray-400);
        margin-top: 8px;
    }

    @media (max-width: 640px) {
        .options-grid { grid-template-columns: 1fr; }
        div[style*="grid-template-columns:1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    let questionIndex = 0;
    const letters = ['A', 'B', 'C', 'D'];

    function addQuestion() {
        const i = questionIndex++;
        const container = document.getElementById('questionsContainer');

        const block = document.createElement('div');
        block.className = 'question-block';
        block.id = `question_${i}`;

        block.innerHTML = `
            <div class="q-header">
                <span class="q-number">Question ${container.children.length + 1}</span>
                <button type="button" class="q-remove" onclick="removeQuestion('question_${i}')">
                    ✕ Remove
                </button>
            </div>

            <label class="field-label">Question Text</label>
            <textarea class="field-input" name="questions[${i}][question_text]"
                      placeholder="Type your question here…"
                      rows="2" required
                      style="resize:vertical; line-height:1.5;"></textarea>

            <div class="options-grid" id="optionsGrid_${i}">
                ${letters.map((letter, j) => `
                    <div class="option-row" id="optionRow_${i}_${j}">
                        <input type="radio" class="option-radio"
                               name="questions[${i}][correct_option]"
                               value="${j}"
                               onchange="highlightCorrect(${i})"
                               required>
                        <span class="option-letter">${letter}</span>
                        <input type="text" class="option-input"
                               name="questions[${i}][options][${j}]"
                               placeholder="Option ${letter}"
                               required>
                    </div>
                `).join('')}
            </div>
            <div class="correct-hint">🟢 Select the radio button next to the correct answer</div>
        `;

        container.appendChild(block);
        updateQuestionNumbers();
        updateQuestionCount();
    }

    function removeQuestion(id) {
        const total = document.getElementById('questionsContainer').children.length;
        if (total <= 1) {
            alert('An exam must have at least one question.');
            return;
        }
        document.getElementById(id)?.remove();
        updateQuestionNumbers();
        updateQuestionCount();
    }

    function highlightCorrect(i) {
        const radios = document.querySelectorAll(`input[name="questions[${i}][correct_option]"]`);
        radios.forEach((radio, j) => {
            const row = document.getElementById(`optionRow_${i}_${j}`);
            if (row) row.classList.toggle('correct-selected', radio.checked);
        });
    }

    function updateQuestionNumbers() {
        const blocks = document.querySelectorAll('.question-block');
        blocks.forEach((block, idx) => {
            const badge = block.querySelector('.q-number');
            if (badge) badge.textContent = `Question ${idx + 1}`;
        });
    }

    function updateQuestionCount() {
        const count = document.querySelectorAll('.question-block').length;
        const el = document.getElementById('questionCount');
        el.textContent = `${count} question${count !== 1 ? 's' : ''}`;
    }

    // Validate all questions have a correct option selected before submit
    document.getElementById('examForm').addEventListener('submit', function (e) {
        const blocks = document.querySelectorAll('.question-block');
        let valid = true;

        blocks.forEach((block, idx) => {
            const radios = block.querySelectorAll('input[type="radio"]');
            const checked = [...radios].some(r => r.checked);
            if (!checked) {
                valid = false;
                block.style.borderColor = '#ef4444';
                block.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                block.style.borderColor = '';
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Please select the correct answer for every question.');
        }
    });

    // Add the first question automatically on load
    addQuestion();
</script>
@endpush