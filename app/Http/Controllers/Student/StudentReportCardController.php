<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\SubjectScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentReportCardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Generate session options
        $currentYear = date('Y');
        $sessions = [];
        for ($i = 0; $i < 4; $i++) {
            $start = $currentYear - $i;
            $sessions[] = $start . '/' . ($start + 1);
        }

        $scores        = collect();
        $selectedTerm    = $request->input('term');
        $selectedSession = $request->input('session');
        $position        = null;
        $totalStudents   = null;
        $grandTotal      = 0;
        $average         = null;

        if ($request->filled(['term', 'session'])) {

            // This student's scores for the selected term/session
            $scores = SubjectScore::where('student_id', $user->id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->orderBy('subject')
                ->get();

            if ($scores->isNotEmpty()) {
                $grandTotal = $scores->sum('total');
                $average    = round($grandTotal / $scores->count(), 2);

                // Calculate position within the class for this term
                $classStudents = User::where('class_id', $user->class_id)
                    ->where('role', 'student')
                    ->get();

                $totalStudents = $classStudents->count();

                $classTotals = $classStudents->map(function ($student) use ($request) {
                    $total = SubjectScore::where('student_id', $student->id)
                        ->where('term', $request->term)
                        ->where('session', $request->session)
                        ->sum('total');
                    return ['student_id' => $student->id, 'total' => $total];
                })->sortByDesc('total')->values();

                $pos = 1;
                $prevTotal = null;
                $prevPos   = 1;

                foreach ($classTotals as $idx => $entry) {
                    if ($entry['total'] === $prevTotal) {
                        if ($entry['student_id'] === $user->id) { $position = $prevPos; break; }
                    } else {
                        if ($entry['student_id'] === $user->id) { $position = $pos; break; }
                        $prevPos   = $pos;
                        $prevTotal = $entry['total'];
                    }
                    $pos++;
                }
            }
        }

        return view('student.report-card', compact(
            'user',
            'scores',
            'sessions',
            'selectedTerm',
            'selectedSession',
            'grandTotal',
            'average',
            'position',
            'totalStudents'
        ));
    }
}