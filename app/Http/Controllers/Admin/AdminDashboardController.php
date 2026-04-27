<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\SchoolClass;
use App\Models\StudyMaterial;
use App\Models\Test;
use App\Models\TestSubmission;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ── Counts ───────────────────────────────────────────────
        $totalStudents   = User::where('role', 'student')->count();
        $totalTeachers   = User::where('role', 'teacher')->count();
        $totalClasses    = SchoolClass::count();
        $totalTests      = Test::count();
        $totalSubmissions= TestSubmission::count();
        $totalMaterials  = StudyMaterial::count();
        $totalNotices    = Notice::count();

        // ── Overall average score across all submissions ──────────
        $overallAvg = TestSubmission::avg('percentage');
        $overallAvg = $overallAvg ? round($overallAvg) : null;

        // ── Recent students (last 5) ──────────────────────────────
        $recentStudents = User::where('role', 'student')
            ->with('studentClass')
            ->latest()
            ->take(5)
            ->get();

        // ── Recent teachers (last 5) ──────────────────────────────
        $recentTeachers = User::where('role', 'teacher')
            ->with('teacherProfile')
            ->latest()
            ->take(5)
            ->get();

        // ── Class performance: avg score per class ────────────────
        $classPerformance = SchoolClass::withCount('students')
            ->get()
            ->map(function ($class) {
                $avg = TestSubmission::whereHas('student', function ($q) use ($class) {
                        $q->where('class_id', $class->id);
                    })
                    ->avg('percentage');

                return [
                    'name' => $class->name,
                    'avg'  => $avg ? round($avg) : 0,
                ];
            })
            ->filter(fn($c) => $c['avg'] > 0)
            ->values();

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalTests',
            'totalSubmissions',
            'totalMaterials',
            'totalNotices',
            'overallAvg',
            'recentStudents',
            'recentTeachers',
            'classPerformance'
        ));
    }
}