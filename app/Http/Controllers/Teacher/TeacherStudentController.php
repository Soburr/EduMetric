<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Test;
use App\Models\TestSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherStudentController extends Controller
{
    
    public function index()
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;

        // ── Section 1: Class Teacher View ────────────────────────
        $classStudents     = collect();
        $assignedClass     = null;

        if ($profile?->is_class_teacher && $profile->assigned_class_id) {
            $assignedClass = $profile->assignedClass;

            $classStudents = User::where('class_id', $profile->assigned_class_id)
                ->where('role', 'student')
                ->get()
                ->map(function ($student) {
                    // Overall performance across ALL subjects
                    $submissions = TestSubmission::where('student_id', $student->id)
                        ->with('test')
                        ->get();

                    $student->tests_taken     = $submissions->count();
                    $student->avg_performance = $submissions->isNotEmpty()
                        ? round($submissions->avg('percentage'))
                        : null;
                    $student->last_submission = $submissions->sortByDesc('created_at')->first()?->created_at;

                    return $student;
                });
        }

        // ── Section 2: Subject Teacher View ──────────────────────
        // Classes that have taken at least one of this teacher's tests
        $subjectClasses = Test::where('teacher_id', $teacher->id)
            ->with('schoolClass')
            ->withCount('submissions')
            ->get()
            ->groupBy('class_id')
            ->map(function ($tests, $classId) use ($teacher) {
                $class       = $tests->first()->schoolClass;
                $testIds     = $tests->pluck('id');

                $students = User::where('class_id', $classId)
                    ->where('role', 'student')
                    ->get()
                    ->map(function ($student) use ($testIds, $teacher) {
                        $submissions = TestSubmission::where('student_id', $student->id)
                            ->whereIn('test_id', $testIds)
                            ->with('test')
                            ->get();

                        $student->tests_taken      = $submissions->count();
                        $student->tests_available  = $testIds->count();
                        $student->avg_performance  = $submissions->isNotEmpty()
                            ? round($submissions->avg('percentage'))
                            : null;
                        $student->last_submission  = $submissions->sortByDesc('created_at')->first()?->created_at;

                        return $student;
                    })
                    ->filter(fn($s) => $s->tests_taken > 0); // only students who have submitted

                return [
                    'class'    => $class,
                    'tests'    => $tests,
                    'students' => $students,
                ];
            })
            ->values();

        return view('teacher.students', compact(
            'classStudents',
            'assignedClass',
            'subjectClasses',
            'profile'
        ));
    }

    /**
     * Student mini profile — for class teacher only.
     */
    public function show(int $id)
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;

        // Only class teachers can view full student profiles
        if (!$profile?->is_class_teacher) {
            abort(403, 'Only class teachers can view student profiles.');
        }

        $student = User::where('id', $id)
            ->where('class_id', $profile->assigned_class_id)
            ->where('role', 'student')
            ->firstOrFail();

        // All submissions with subject breakdown
        $submissions = TestSubmission::where('student_id', $student->id)
            ->with('test')
            ->latest()
            ->get();

        // Performance per subject
        $performance = $submissions
            ->groupBy('test.subject')
            ->map(fn($group) => [
                'avg'   => round($group->avg('percentage')),
                'count' => $group->count(),
            ]);

        $avgPerformance = $submissions->isNotEmpty()
            ? round($submissions->avg('percentage'))
            : null;

        $bestSubject = $performance->isNotEmpty()
            ? $performance->sortByDesc('avg')->keys()->first()
            : null;

        return view('teacher.student-profile', compact(
            'student',
            'submissions',
            'performance',
            'avgPerformance',
            'bestSubject'
        ));
    }

    /**
     * Reset a student's password — class teacher only.
     */
    public function resetPassword(Request $request, int $id)
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;

        if (!$profile?->is_class_teacher) {
            abort(403, 'Only class teachers can reset student passwords.');
        }

        $request->validate([
            'new_password'              => ['required', 'min:4', 'confirmed'],
            'new_password_confirmation' => ['required'],
        ]);

        $student = User::where('id', $id)
            ->where('class_id', $profile->assigned_class_id)
            ->where('role', 'student')
            ->firstOrFail();

        $student->update([
            'password' => Hash::make($request->new_password),
        ]);

        return back()->with('success', "Password for {$student->name} has been reset successfully.");
    }
}