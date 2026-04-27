<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Available tests for this student's class
        $availableTests = \App\Models\Test::where('class_id', $user->class_id)
            ->where('is_active', true)
            ->whereDoesntHave('submissions', function ($q) use ($user) {
                $q->where('student_id', $user->id);
            })
            ->get();

        // Completed test submissions
        $completedTests = \App\Models\TestSubmission::where('student_id', $user->id)->get();

        // Study materials for this student's class
        $materials = \App\Models\StudyMaterial::where('class_id', $user->class_id)->get();

        // Performance: average score per subject
        $performance = \App\Models\TestSubmission::where('student_id', $user->id)
            ->with('test')
            ->get()
            ->groupBy('test.subject')
            ->map(fn($group) => round($group->avg('percentage')));

        $avgPerformance = $performance->isNotEmpty()
            ? round($performance->avg())
            : null;

       $notices = \App\Models\Notice::with(['author', 'schoolClass'])
            ->where(function ($q) use ($user) {
            $q->where('class_id', $user->class_id)
            ->orWhereNull('class_id');
       })
       ->latest()
       ->take(5)
       ->get();

        return view('student.dashboard', [
            'user'                => $user,
            'availableTests'      => $availableTests,
            'availableTestsCount' => $availableTests->count(),
            'completedTestsCount' => $completedTests->count(),
            'materialsCount'      => $materials->count(),
            'performance'         => $performance,
            'avgPerformance'      => $avgPerformance,
            'activeCoursesCount'  => $materials->pluck('subject')->unique()->count(),
            'notices'             => $notices,
        ]);
    }
}