<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
   
    public function showRegister()
    {
        $classes = SchoolClass::all();
        return view('auth.student-register', compact('classes'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'student_id' => 'required|unique:users',
            'password' => 'required|min:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'student_id' => $request->student_id,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'class_id' => $request->class_id
        ]);

        Auth::login($user);

        return redirect('/student/dashboard')->with('success', 'Registration successful');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('student.dashboard');
        }
 
        return view('auth.student-login');
    }
 
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'student_id' => ['required', 'string'],
            'password'   => ['required', 'string'],
        ]);

        $user = User::where('student_id', $credentials['student_id'])
            ->where('role', 'student')
            ->first();
 
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'student_id' => 'Invalid student ID or password.',
            ])->onlyInput('student_id');
        }
 
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();
 
        return redirect()->intended(route('student.dashboard'));
    }
 
    public function logout(Request $request)
    {
        Auth::logout();
 
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect()->route('student.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
