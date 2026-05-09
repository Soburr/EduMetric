<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\SubjectScore;
use App\Models\TestSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherScoreController extends Controller
{
    /**
     * Score entry page.
     * Teacher can enter scores for ANY class they teach.
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;
        $subjects = $profile?->subjects ?? [];

        // ALL classes available — teacher decides which class they're recording for
        $classes = SchoolClass::orderBy('name')->get();

        $students = collect();
        $scores = collect();
        $selectedClass = null;
        $selectedTerm = $request->input('term');
        $selectedSession = $request->input('session');
        $selectedSubject = $request->input('subject');

        if ($request->filled(['class_id', 'term', 'session', 'subject'])) {

            // Validate subject belongs to this teacher
            if (!in_array($request->subject, $subjects)) {
                return back()->withErrors([
                    'subject' => 'You are not assigned to teach this subject.',
                ]);
            }

            $selectedClass = SchoolClass::find($request->class_id);

            $students = User::where('class_id', $request->class_id)
                ->where('role', 'student')
                ->orderBy('name')
                ->get();

            $scores = SubjectScore::where('class_id', $request->class_id)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->where('subject', $request->subject)
                ->get()
                ->keyBy('student_id');


            foreach ($students as $student) {
                $cbtScore = $this->getCbtScore($student->id, $request->class_id, $request->subject);

                $score = SubjectScore::firstOrCreate([
                    'student_id' => $student->id,
                    'class_id' => $request->class_id,
                    'subject' => $request->subject,
                    'term' => $request->term,
                    'session' => $request->session,
                ], [
                    'cbt_score' => $cbtScore,
                    'total' => $cbtScore,
                    'grade' => SubjectScore::computeGrade($cbtScore),
                ]);

                // Always update CBT in case student took the test after record was created
                if ((float) $score->cbt_score !== (float) $cbtScore) {
                    $score->cbt_score = $cbtScore;
                    $score->recalculate();
                }

                $scores[$student->id] = $score;
            }
        }

        $currentYear = date('Y');
        $sessions = [];
        for ($i = 0; $i < 4; $i++) {
            $start = $currentYear - $i;
            $sessions[] = $start . '/' . ($start + 1);
        }

        return view('teacher.scores', compact(
            'classes',
            'subjects',
            'students',
            'scores',
            'selectedClass',
            'selectedTerm',
            'selectedSession',
            'selectedSubject',
            'sessions'
        ));
    }

    /**
     * Save CA and Exam scores.
     */
    public function store(Request $request)
    {
        $teacher = Auth::user();
        $subjects = $teacher->teacherProfile?->subjects ?? [];

        // Ensure teacher teaches this subject
        if (!in_array($request->subject, $subjects)) {
            return back()->withErrors([
                'subject' => 'You are not assigned to teach this subject.',
            ]);
        }

        $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'term' => ['required', 'in:first,second,third'],
            'session' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'scores' => ['required', 'array'],
            'scores.*.ca_score' => ['nullable', 'numeric', 'min:0', 'max:30'],
            'scores.*.exam_score' => ['nullable', 'numeric', 'min:0', 'max:70'],
            'scores.*.remark' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->scores as $studentId => $data) {
                $score = SubjectScore::firstOrCreate([
                    'student_id' => $studentId,
                    'class_id' => $request->class_id,
                    'subject' => $request->subject,
                    'term' => $request->term,
                    'session' => $request->session,
                ], ['cbt_score' => 0]);

                $score->ca_score = isset($data['ca_score']) ? (float) $data['ca_score'] : null;
                $score->exam_score = isset($data['exam_score']) ? (float) $data['exam_score'] : null;
                $score->cbt_score = isset($data['cbt_score']) ? (float) $data['cbt_score'] : $score->cbt_score; // add this line
                $score->remark = $data['remark'] ?? null;
                $score->save();

                $score->recalculate();
            }
        });

        $class = SchoolClass::find($request->class_id);

        return back()->with(
            'success',
            "Scores saved for {$class->name} — {$request->subject} — " .
            SubjectScore::termLabel($request->term) . "."
        );
    }

    /**
     * Scale CBT score to /20 from test submissions.
     */
    private function getCbtScore(int $studentId, int $classId, string $subject): float
    {
        $submissions = TestSubmission::where('student_id', $studentId)
            ->whereHas('test', function ($q) use ($classId, $subject) {
                $q->where('class_id', $classId)->where('subject', $subject);
            })
            ->get();

        if ($submissions->isEmpty())
            return 0;

        $avgPct = $submissions->avg('percentage');
        return round(($avgPct / 100) * 20, 2);
    }
}