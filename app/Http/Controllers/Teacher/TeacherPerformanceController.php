<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestSubmission;
use Illuminate\Support\Facades\Auth;

class TeacherPerformanceController extends Controller
{
    public function index()
    {
        $teacherId = Auth::id();

        $tests = Test::with(['schoolClass', 'submissions'])
            ->byTeacher($teacherId)
            ->get();

        $totalTests       = $tests->count();
        $totalSubmissions = $tests->sum(fn($t) => $t->submissions->count());
        $overallAvg       = $tests->flatMap->submissions->avg('percentage');
        $atRiskCount      = $tests->flatMap->submissions
            ->groupBy('student_id')
            ->filter(fn($subs) => $subs->avg('percentage') < 30)
            ->count();

        $classComparison = $tests
            ->filter(fn($t) => $t->schoolClass)
            ->groupBy('class_id')
            ->map(function ($classTests) {
                $allSubs = $classTests->flatMap->submissions;
                return [
                    'name' => $classTests->first()->schoolClass->name,
                    'avg'  => round($allSubs->avg('percentage') ?? 0),
                    'count'=> $allSubs->count(),
                ];
            })
            ->values();

        $bestClass = $classComparison->sortByDesc('avg')->first();

        return view('teacher.performance', compact(
            'tests',
            'totalTests',
            'totalSubmissions',
            'overallAvg',
            'atRiskCount',
            'classComparison',
            'bestClass',
        ));
    }

    public function show(Test $test)
    {
        abort_if($test->teacher_id !== Auth::id(), 403);

        $test->load(['schoolClass', 'submissions.student']);

        $submissions  = $test->submissions;
        $totalStudents= $test->schoolClass?->students()->count() ?? 0;
        $submitted    = $submissions->count();
        $notSubmitted = max(0, $totalStudents - $submitted);
        $avgScore     = round($submissions->avg('percentage') ?? 0);
        $highest      = $submissions->max('percentage') ?? 0;
        $lowest       = $submissions->min('percentage') ?? 0;
        $passRate      = $submitted > 0
            ? round($submissions->where('percentage', '>=', 30)->count() / $submitted * 100)
            : 0;

        $distribution = [
            'A' => $submissions->filter(fn($s) => $s->percentage >= 70)->count(),
            'B' => $submissions->filter(fn($s) => $s->percentage >= 60 && $s->percentage < 70)->count(),
            'C' => $submissions->filter(fn($s) => $s->percentage >= 50 && $s->percentage < 60)->count(),
            'D' => $submissions->filter(fn($s) => $s->percentage >= 44 && $s->percentage < 50)->count(),
            'E' => $submissions->filter(fn($s) => $s->percentage >= 40 && $s->percentage < 44)->count(),
            'F' => $submissions->filter(fn($s) => $s->percentage < 40)->count(),
        ];

        return view('teacher.show-performance', compact(
            'test',
            'submissions',
            'totalStudents',
            'submitted',
            'notSubmitted',
            'avgScore',
            'highest',
            'lowest',
            'passRate',
            'distribution',
        ));
    }
}