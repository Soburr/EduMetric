<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\SubjectScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BroadsheetController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->role === 'teacher' ? $user->teacherProfile : null;

        // Admin sees all classes
        // Class teacher sees only their assigned class
        if ($user->role === 'admin') {
            $classes = SchoolClass::orderBy('name')->get();
        } else {
            // Must be a class teacher to access broadsheet
            if (!$profile?->is_class_teacher || !$profile->assigned_class_id) {
                abort(403, 'Only class teachers and admins can view the broadsheet.');
            }
            $classes = SchoolClass::where('id', $profile->assigned_class_id)->get();
        }

        $broadsheet      = collect();
        $subjects        = collect();
        $selectedClass   = null;
        $selectedTerm    = $request->input('term');
        $selectedSession = $request->input('session');

        if ($request->filled(['class_id', 'term', 'session'])) {

            // Class teacher can only view their own class
            if ($user->role === 'teacher') {
                if ((int) $request->class_id !== (int) $profile->assigned_class_id) {
                    abort(403, 'You can only view the broadsheet for your assigned class.');
                }
            }

            $selectedClass = SchoolClass::find($request->class_id);

            $students = User::where('class_id', $request->class_id)
                ->where('role', 'student')
                ->orderBy('name')
                ->get();

            // All subjects with scores entered for this class/term/session
            // — collected from ALL subject teachers, not just the logged-in one
            $subjects = SubjectScore::where('class_id', $request->class_id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->pluck('subject')
                ->unique()
                ->sort()
                ->values();

            // Build broadsheet rows
            $broadsheet = $students->map(function ($student) use ($request, $subjects) {
                $row = [
                    'student'       => $student,
                    'scores'        => [],
                    'grand_total'   => 0,
                    'subject_count' => 0,
                ];

                foreach ($subjects as $subject) {
                    $score = SubjectScore::where('student_id', $student->id)
                        ->where('class_id', $request->class_id)
                        ->where('subject', $subject)
                        ->where('term', $request->term)
                        ->where('session', $request->session)
                        ->first();

                    $row['scores'][$subject] = $score;

                    
                        $row['grand_total'] += $score ? $score->total : 0;
                        $row['subject_count']++;  
                    
                }

                $row['average'] = $row['subject_count'] > 0
                    ? round($row['grand_total'] / $row['subject_count'], 2)
                    : 0;

                return $row;
            });

            // Calculate positions
            $broadsheet = $this->assignPositions($broadsheet, 'grand_total');
        }

        $sessions = $this->getSessions();

        return view('broadsheet', compact(
            'classes',
            'broadsheet',
            'subjects',
            'selectedClass',
            'selectedTerm',
            'selectedSession',
            'sessions'
        ));
    }

    /**
     * Annual broadsheet — aggregate of all 3 terms, final positions.
     */
    public function annual(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->role === 'teacher' ? $user->teacherProfile : null;

        if ($user->role === 'admin') {
            $classes = SchoolClass::orderBy('name')->get();
        } else {
            if (!$profile?->is_class_teacher || !$profile->assigned_class_id) {
                abort(403, 'Only class teachers and admins can view the annual broadsheet.');
            }
            $classes = SchoolClass::where('id', $profile->assigned_class_id)->get();
        }

        $annualSheet     = collect();
        $selectedClass   = null;
        $selectedSession = $request->input('session');

        if ($request->filled(['class_id', 'session'])) {

            if ($user->role === 'teacher') {
                if ((int) $request->class_id !== (int) $profile->assigned_class_id) {
                    abort(403, 'You can only view the broadsheet for your assigned class.');
                }
            }

            $selectedClass = SchoolClass::find($request->class_id);

            $students = User::where('class_id', $request->class_id)
                ->where('role', 'student')
                ->orderBy('name')
                ->get();

            $annualSheet = $students->map(function ($student) use ($request) {
                $termTotals = [];
                $aggregate  = 0;

                foreach (['first', 'second', 'third'] as $term) {
                    $termSum = SubjectScore::where('student_id', $student->id)
                        ->where('class_id', $request->class_id)
                        ->where('term', $term)
                        ->where('session', $request->session)
                        ->sum('total');

                    $termTotals[$term] = round($termSum, 2);
                    $aggregate        += $termSum;
                }

                return [
                    'student'     => $student,
                    'term_totals' => $termTotals,
                    'aggregate'   => round($aggregate, 2),
                ];
            });

            // Assign final year positions
            $annualSheet = $this->assignPositions($annualSheet, 'aggregate');
        }

        $sessions = $this->getSessions();

        return view('broadsheet-annual', compact(
            'classes',
            'annualSheet',
            'selectedClass',
            'selectedSession',
            'sessions'
        ));
    }

    // ── Helpers ──────────────────────────────────────────────────

    /**
     * Assign positions to a collection sorted by a key.
     * Handles ties — tied students share the same position.
     */
    private function assignPositions($collection, string $key)
{
    $sorted   = $collection->sortByDesc($key)->values()->toArray();
    $position = 1;
    $prevVal  = null;
    $prevPos  = 1;

    foreach ($sorted as $idx => $row) {
        $val = $row[$key];
        if ($prevVal !== null && $val === $prevVal) {
            $sorted[$idx]['position'] = $prevPos;
        } else {
            $sorted[$idx]['position'] = $position;
            $prevPos = $position;
            $prevVal = $val;
        }
        $position++;
    }

    return collect($sorted)->sortBy('student.name')->values();
}

    private function getSessions(): array
    {
        $currentYear = date('Y');
        $sessions    = [];
        for ($i = 0; $i < 4; $i++) {
            $start      = $currentYear - $i;
            $sessions[] = $start . '/' . ($start + 1);
        }
        return $sessions;
    }
}