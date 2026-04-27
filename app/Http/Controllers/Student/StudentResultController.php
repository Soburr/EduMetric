<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestSubmission;
use Illuminate\Support\Facades\Auth;

class StudentResultController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $results = TestSubmission::where('student_id', $user->id)
            ->with(['test', 'answers.question', 'answers.selectedOption'])
            ->latest()
            ->get()
            ->map(function ($submission) {
                return (object) [
                    'id'          => $submission->id,
                    'subject'     => $submission->test->subject ?? '—',
                    'test_title'  => $submission->test->title ?? '—',
                    'score'       => $submission->score,
                    'total'       => $submission->total,
                    'percentage'  => $submission->percentage,
                    'grade'       => $this->getGrade($submission->percentage),
                    'created_at'  => $submission->created_at,
                ];
            });

        return view('student.results', compact('results'));
    }

    /**
     * Convert percentage to letter grade.
     */
    private function getGrade(int $percentage): string
    {
        return match (true) {
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default           => 'F',
        };
    }
}