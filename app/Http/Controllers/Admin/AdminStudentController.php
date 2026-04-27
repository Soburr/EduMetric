<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\TestSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStudentController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::orderBy('name')->get();

        $query = User::where('role', 'student')->with('studentClass');

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        // Search by name or student ID
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('student_id', 'like', '%' . $request->search . '%');
            });
        }

        $students = $query->latest()->get()->map(function ($student) {
            $submissions = TestSubmission::where('student_id', $student->id)->get();
            $student->tests_taken     = $submissions->count();
            $student->avg_performance = $submissions->isNotEmpty()
                ? round($submissions->avg('percentage'))
                : null;
            return $student;
        });

        return view('admin.students', compact('students', 'classes'));
    }

    public function resetPassword(Request $request, int $id)
    {
        $request->validate([
            'new_password' => ['required', 'min:6', 'confirmed'],
        ]);

        $student = User::where('id', $id)->where('role', 'student')->firstOrFail();
        $student->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', "Password for '{$student->name}' reset successfully.");
    }

    public function destroy(int $id)
    {
        $student = User::where('id', $id)->where('role', 'student')->firstOrFail();
        $name    = $student->name;
        $student->delete();

        return back()->with('success', "Student '{$name}' deleted successfully.");
    }
}