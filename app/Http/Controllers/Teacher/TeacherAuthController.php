<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function showRegister()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('auth.teacher-register', compact('classes'));
    }

    public function register(Request $request)
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
            return back()
                ->withErrors(['subjects' => 'Please add at least one subject.'])
                ->withInput();
        }

        $isClassTeacher  = $request->boolean('is_class_teacher');
        $assignedClassId = ($isClassTeacher && $request->assigned_class_id)
            ? $request->assigned_class_id
            : null;

        // Must select a class if class teacher toggle is on
        if ($isClassTeacher && !$assignedClassId) {
            return back()
                ->withErrors(['assigned_class_id' => 'Please select your assigned class.'])
                ->withInput();
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

        Auth::login($user);

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Registration successful. Welcome, ' . $user->name . '!');
    }

    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }
        return view('auth.teacher-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)
            ->where('role', 'teacher')
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->onlyInput('email');
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('teacher.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher.login')
            ->with('success', 'You have been logged out successfully.');
    }
}