<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\StudyMaterial;
use App\Models\Test;
use App\Models\TestSubmission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;

        // ── Core counts ──────────────────────────────────────────
        $totalTests = Test::where('teacher_id', $teacher->id)->count();

        $totalSubmissions = TestSubmission::whereHas('test', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->count();

        $totalMaterials = StudyMaterial::where('teacher_id', $teacher->id)->count();

        $totalNotices = Notice::where('teacher_id', $teacher->id)->count();

        $activeTests = Test::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->count();

        // ── Class teacher student count ───────────────────────────
        $classStudentCount = 0;
        if ($profile?->is_class_teacher && $profile->assigned_class_id) {
            $classStudentCount = User::where('class_id', $profile->assigned_class_id)
                ->where('role', 'student')
                ->count();
        }

        // ── Recent submissions (last 5) ───────────────────────────
        $recentSubmissions = TestSubmission::whereHas('test', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->with(['student', 'test'])
            ->latest()
            ->take(5)
            ->get();

        // ── Recent tests (last 5) ─────────────────────────────────
        $recentTests = Test::where('teacher_id', $teacher->id)
            ->with('schoolClass')
            ->withCount(['questions', 'submissions'])
            ->latest()
            ->take(5)
            ->get();

        // ── Performance: avg score per class ─────────────────────
        $classComparison = Test::where('teacher_id', $teacher->id)
            ->with(['schoolClass', 'submissions'])
            ->get()
            ->groupBy('class_id')
            ->map(function ($tests) {
                $allSubmissions = $tests->flatMap->submissions;
                return [
                    'name' => $tests->first()->schoolClass->name ?? '—',
                    'avg'  => $allSubmissions->isNotEmpty()
                        ? round($allSubmissions->avg('percentage'))
                        : 0,
                ];
            })
            ->values();

        // ── Recent notices (last 3) ───────────────────────────────
        $recentNotices = Notice::where('teacher_id', $teacher->id)
            ->with('schoolClass')
            ->latest()
            ->take(3)
            ->get();

        return view('teacher.dashboard', compact(
            'profile',
            'totalTests',
            'totalSubmissions',
            'totalMaterials',
            'totalNotices',
            'activeTests',
            'classStudentCount',
            'recentSubmissions',
            'recentTests',
            'classComparison',
            'recentNotices'
        ));
    }
}