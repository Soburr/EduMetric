<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestSubmission;
use Illuminate\Support\Facades\Auth;

class StudentPerformanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $submissions = TestSubmission::where('student_id', $user->id)
            ->with('test')
            ->latest()
            ->get();

        // Group by subject → average percentage
        $performance = $submissions
            ->groupBy('test.subject')
            ->map(fn($group) => round($group->avg('percentage')));

        $avgPerformance = $performance->isNotEmpty()
            ? round($performance->avg())
            : null;

        $bestSubject = $performance->isNotEmpty()
            ? $performance->sortDesc()->keys()->first()
            : null;

        // Map submissions for the history table
        $results = $submissions->map(function ($submission) {
            return (object) [
                'subject'    => $submission->test->subject ?? '—',
                'test_title' => $submission->test->title ?? '—',
                'score'      => $submission->score,
                'total'      => $submission->total,
                'percentage' => $submission->percentage,
                'created_at' => $submission->created_at,
            ];
        });

        return view('student.performance', [
            'performance'         => $performance,
            'avgPerformance'      => $avgPerformance,
            'bestSubject'         => $bestSubject,
            'completedTestsCount' => $submissions->count(),
            'results'             => $results,
        ]);
    }
}