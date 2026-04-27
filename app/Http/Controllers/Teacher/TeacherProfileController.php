<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherProfileController extends Controller
{
    /**
     * Show the profile page.
     */
    public function index()
    {
        $teacher  = Auth::user();
        $profile  = $teacher->teacherProfile;
        $classes  = SchoolClass::orderBy('name')->get();

        $totalTests     = \App\Models\Test::where('teacher_id', $teacher->id)->count();
        $totalMaterials = \App\Models\StudyMaterial::where('teacher_id', $teacher->id)->count();

        return view('teacher.profile', compact(
            'profile',
            'classes',
            'totalTests',
            'totalMaterials'
        ));
    }

    /**
     * Update name and email.
     */
    public function update(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $teacher->id],
        ]);

        $teacher->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update subjects and class teacher info.
     */
    public function updateTeaching(Request $request)
    {
        $teacher = Auth::user();
        $profile = $teacher->teacherProfile;

        $request->validate([
            'subjects'          => ['required', 'string'],
            'assigned_class_id' => ['nullable', 'exists:school_classes,id'],
        ]);

        $subjects = json_decode($request->subjects, true);

        if (empty($subjects) || !is_array($subjects)) {
            return back()->withErrors(['subjects' => 'Please add at least one subject.']);
        }

        $isClassTeacher  = $request->boolean('is_class_teacher');
        $assignedClassId = ($isClassTeacher && $request->assigned_class_id)
            ? $request->assigned_class_id
            : null;

        if ($isClassTeacher && !$assignedClassId) {
            return back()->withErrors(['assigned_class_id' => 'Please select your assigned class.']);
        }

        $profile->update([
            'subjects'          => $subjects,
            'is_class_teacher'  => $isClassTeacher,
            'assigned_class_id' => $assignedClassId,
        ]);

        return back()->with('success', 'Teaching information updated successfully.');
    }

    /**
     * Change password.
     */
    public function changePassword(Request $request)
    {
        $teacher = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $teacher->password)) {
            return back()->withErrors([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $teacher->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}