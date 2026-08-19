<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->role === 'teacher' ? $user->teacherProfile : null;

        if ($user->role === 'admin') {
            $classes = SchoolClass::orderBy('name')->get();
        } else {
            if (!$profile?->is_class_teacher || !$profile->assigned_class_id) {
                abort(403, 'Only class teachers and admins can access attendance.');
            }
            $classes = SchoolClass::where('id', $profile->assigned_class_id)->get();
        }

        $selectedClassId = $request->input('class_id', $profile?->assigned_class_id);
        $selectedDate    = $request->input('date', date('Y-m-d'));
        $selectedTerm    = $request->input('term', $this->currentTerm());
        $selectedSession = $request->input('session', $this->currentSession());

        $selectedClass = null;
        $students      = collect();
        $records       = collect();

        if ($selectedClassId) {

            if ($user->role === 'teacher' && (int) $selectedClassId !== (int) $profile->assigned_class_id) {
                abort(403, 'You can only mark attendance for your assigned class.');
            }

            $selectedClass = SchoolClass::find($selectedClassId);

            $students = User::where('class_id', $selectedClassId)
                ->where('role', 'student')
                ->orderBy('name')
                ->get();

            $records = AttendanceRecord::where('class_id', $selectedClassId)
                ->where('date', $selectedDate)
                ->get()
                ->keyBy('student_id');
        }

        $sessions = $this->getSessions();

        return view('attendance.index', compact(
            'classes',
            'selectedClass',
            'selectedClassId',
            'selectedDate',
            'selectedTerm',
            'selectedSession',
            'students',
            'records',
            'sessions'
        ));
    }

    public function store(Request $request)
    {
        $user    = Auth::user();
        $profile = $user->teacherProfile;

        if (!$profile?->is_class_teacher || (int) $request->class_id !== (int) $profile->assigned_class_id) {
            abort(403, 'You can only mark attendance for your assigned class.');
        }

        $request->validate([
            'class_id' => ['required', 'exists:school_classes,id'],
            'date'     => ['required', 'date'],
            'term'     => ['required', 'in:first,second,third'],
            'session'  => ['required', 'string'],
            'absent'   => ['nullable', 'array'],
        ]);

        $students = User::where('class_id', $request->class_id)
            ->where('role', 'student')
            ->pluck('id');

        $absentIds = collect($request->input('absent', []))->map(fn($id) => (int) $id);

        DB::transaction(function () use ($students, $absentIds, $request, $user) {
            foreach ($students as $studentId) {
                AttendanceRecord::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'date'       => $request->date,
                    ],
                    [
                        'class_id'  => $request->class_id,
                        'status'    => $absentIds->contains($studentId) ? 'absent' : 'present',
                        'term'      => $request->term,
                        'session'   => $request->session,
                        'marked_by' => $user->id,
                    ]
                );
            }
        });

        return back()->with('success', 'Attendance saved for ' . \Carbon\Carbon::parse($request->date)->format('jS M Y') . '.');
    }

    // ── Helpers ──────────────────────────────────────────────

    public static function getAttendanceStats(int $studentId, string $term, string $session): array
    {
        // Days school opened — set by admin
        $calendar   = \App\Models\SchoolCalendar::where('term', $term)
            ->where('session', $session)
            ->first();
        $daysOpened = $calendar?->days_opened ?? 0;

        // Days this student was marked present
        $present = \App\Models\AttendanceRecord::where('student_id', $studentId)
            ->where('term', $term)
            ->where('session', $session)
            ->where('status', 'present')
            ->count();

        // Absent = opened - present (not from records)
        $absent = $daysOpened > 0 ? max(0, $daysOpened - $present) : 0;

        return [
            'times_opened'  => $daysOpened ?: '',
            'times_present' => $present    ?: '',
            'times_absent'  => $absent     ?: '',
        ];
    }

    private function currentTerm(): string
    {
        $month = (int) date('n');
        return match (true) {
            $month >= 9 && $month <= 12 => 'first',
            $month >= 1 && $month <= 3  => 'second',
            default                     => 'third',
        };
    }

    private function currentSession(): string
    {
        $year = (int) date('Y');
        $month = (int) date('n');
        $start = $month >= 9 ? $year : $year - 1;
        return $start . '/' . ($start + 1);
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
