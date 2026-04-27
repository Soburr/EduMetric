<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TestSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $submissions = TestSubmission::where('student_id', $user->id)
            ->with('test')
            ->get();

        // Performance per subject — sorted best first
        $performance = $submissions
            ->groupBy('test.subject')
            ->map(fn($group) => round($group->avg('percentage')))
            ->sortDesc();

        $avgPerformance = $submissions->isNotEmpty()
            ? round($submissions->avg('percentage'))
            : null;

        $bestSubject = $performance->isNotEmpty()
            ? $performance->keys()->first()
            : null;

        $testsTaken = $submissions->count();

        return view('student.profile', compact(
            'user',
            'performance',
            'avgPerformance',
            'bestSubject',
            'testsTaken'
        ));
    }

    /**
     * Update name only — student ID and class are read-only.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->update(['name' => $request->name]);

        return back()->with('success', 'Name updated successfully.');
    }

    /**
     * Change password with current password verification.
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:4', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed successfully.');
    }
}