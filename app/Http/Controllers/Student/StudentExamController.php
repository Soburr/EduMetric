<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestSubmission;
use App\Models\TestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentExamController extends Controller
{
    /**
     * List all active tests available for the student's class
     * that the student hasn't submitted yet.
     */
    public function index()
    {
        $user = Auth::user();

        $tests = Test::where('class_id', $user->class_id)
            ->where('is_active', true)
            ->withCount('questions')
            ->whereDoesntHave('submissions', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            })
            ->latest()
            ->get();

        return view('student.take-exam', compact('tests'));
    }

    /**
     * Show the exam page for a specific test.
     */
    public function start(int $id)
    {
        $user = Auth::user();

        $test = Test::where('id', $id)
            ->where('class_id', $user->class_id)
            ->where('is_active', true)
            ->withCount('questions')
            ->firstOrFail();

        // Prevent re-submission
        $alreadySubmitted = TestSubmission::where('student_id', $user->id)
            ->where('test_id', $test->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.results')
                ->with('info', 'You have already submitted this test.');
        }

        // Load questions with shuffled options
        $questions = $test->questions()->with('options')->get()->map(function ($question) {
            $question->options = $question->options->shuffle();
            return $question;
        });

        return view('student.exam-page', compact('test', 'questions'));
    }

    /**
     * Handle exam submission and calculate the score.
     */
    public function submit(Request $request, int $id)
    {
        $user = Auth::user();

        $test = Test::where('id', $id)
            ->where('class_id', $user->class_id)
            ->where('is_active', true)
            ->firstOrFail();

        // Prevent double submission
        $alreadySubmitted = TestSubmission::where('student_id', $user->id)
            ->where('test_id', $test->id)
            ->exists();

        if ($alreadySubmitted) {
            return redirect()->route('student.results')
                ->with('info', 'You have already submitted this test.');
        }

        $request->validate([
            'answers'   => ['required', 'array'],
            'answers.*' => ['required', 'integer'],
        ]);

        DB::transaction(function () use ($request, $user, $test) {
            $questions   = $test->questions()->with('options')->get();
            $totalScore  = 0;
            $totalMarks  = $questions->count(); // 1 mark per question

            $submission = TestSubmission::create([
                'student_id'  => $user->id,
                'test_id'     => $test->id,
                'score'       => 0,   // updated below
                'total'       => $totalMarks,
                'percentage'  => 0,
            ]);

            foreach ($questions as $question) {
                $selectedOptionId = $request->input("answers.{$question->id}");

                $isCorrect = $question->options()
                    ->where('id', $selectedOptionId)
                    ->where('is_correct', true)
                    ->exists();

                if ($isCorrect) {
                    $totalScore++;
                }

                TestAnswer::create([
                    'submission_id'    => $submission->id,
                    'question_id'      => $question->id,
                    'selected_option'  => $selectedOptionId,
                    'is_correct'       => $isCorrect,
                ]);
            }

            $submission->update([
                'score'      => $totalScore,
                'percentage' => $totalMarks > 0
                    ? round(($totalScore / $totalMarks) * 100)
                    : 0,
            ]);
        });

        return redirect()->route('student.results')
            ->with('success', 'Test submitted successfully! Your results are now available.');
    }
}