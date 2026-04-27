<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;

class AdminClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['students', 'tests', 'materials'])
            ->with('students')
            ->orderBy('name')
            ->get()
            ->map(function ($class) {
                // Find class teachers
                $class->classTeachers = \App\Models\TeacherProfile::where('assigned_class_id', $class->id)
                    ->with('user')
                    ->get()
                    ->map(fn($p) => $p->user->name ?? '—');
                return $class;
            });

        return view('admin.classes', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:school_classes,name'],
        ]);

        SchoolClass::create(['name' => strtoupper(trim($request->name))]);

        return back()->with('success', "Class '{$request->name}' created successfully.");
    }

    public function update(Request $request, int $id)
    {
        $class = SchoolClass::findOrFail($id);

        $request->validate([
            'name' => ['required', 'string', 'max:50', 'unique:school_classes,name,' . $id],
        ]);

        $class->update(['name' => strtoupper(trim($request->name))]);

        return back()->with('success', "Class renamed to '{$request->name}' successfully.");
    }

    public function destroy(int $id)
    {
        $class = SchoolClass::withCount('students')->findOrFail($id);

        if ($class->students_count > 0) {
            return back()->with('error', "Cannot delete '{$class->name}' — it still has {$class->students_count} student(s). Reassign them first.");
        }

        $class->delete();

        return back()->with('success', "Class '{$class->name}' deleted successfully.");
    }
}