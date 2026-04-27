<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminTeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')
            ->with(['teacherProfile.assignedClass'])
            ->withCount('createdTests')
            ->latest()
            ->get();

        $classes = SchoolClass::orderBy('name')->get();

        return view('admin.teachers', compact('teachers', 'classes'));
    }

    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('admin.teacher-create', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', 'unique:users,email'],
            'subjects'          => ['required', 'string'],
            'password'          => ['required', 'min:6', 'confirmed'],
            'assigned_class_id' => ['nullable', 'exists:school_classes,id'],
        ]);

        $subjects = json_decode($request->subjects, true);

        if (empty($subjects) || !is_array($subjects)) {
            return back()->withErrors(['subjects' => 'Please add at least one subject.'])->withInput();
        }

        $isClassTeacher  = $request->boolean('is_class_teacher');
        $assignedClassId = ($isClassTeacher && $request->assigned_class_id)
            ? $request->assigned_class_id : null;

        if ($isClassTeacher && !$assignedClassId) {
            return back()->withErrors(['assigned_class_id' => 'Please select an assigned class.'])->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        TeacherProfile::create([
            'user_id'           => $user->id,
            'subjects'          => $subjects,
            'is_class_teacher'  => $isClassTeacher,
            'assigned_class_id' => $assignedClassId,
        ]);

        return redirect()->route('admin.teachers.index')
            ->with('success', "Teacher '{$user->name}' added successfully.");
    }

    public function toggle(int $id)
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
        // Using email_verified_at as active flag — null = inactive
        if ($teacher->email_verified_at) {
            $teacher->update(['email_verified_at' => null]);
            $status = 'deactivated';
        } else {
            $teacher->update(['email_verified_at' => now()]);
            $status = 'activated';
        }

        return back()->with('success', "Teacher '{$teacher->name}' {$status} successfully.");
    }

    public function resetPassword(Request $request, int $id)
    {
        $request->validate([
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
        $teacher->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', "Password for '{$teacher->name}' reset successfully.");
    }

    public function destroy(int $id)
    {
        $teacher = User::where('id', $id)->where('role', 'teacher')->firstOrFail();
        $name    = $teacher->name;
        $teacher->delete();

        return back()->with('success', "Teacher '{$name}' deleted successfully.");
    }
}