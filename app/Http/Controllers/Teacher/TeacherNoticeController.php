<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherNoticeController extends Controller
{

    public function index()
    {
        $classes = SchoolClass::orderBy('name')->get();

        $notices = Notice::with(['author', 'schoolClass'])
            ->latest()
            ->get();

        return view('teacher.notices', compact('notices', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => ['required', 'string', 'max:255'],
            'audience' => ['required', 'in:class,schoolwide'],
            'content'  => ['required', 'string'],
            'class_ids'=> ['required_if:audience,class', 'array', 'min:1'],
            'class_ids.*' => ['exists:school_classes,id'],
        ], [
            'class_ids.required_if' => 'Please select at least one class.',
        ]);

        $teacher = Auth::user();

        if ($request->audience === 'schoolwide') {
            // One notice for the whole school (class_id = null)
            Notice::create([
                'teacher_id' => $teacher->id,
                'class_id'   => null,
                'title'      => $request->title,
                'content'    => $request->content,
            ]);
        } else {
            // One notice record per selected class
            foreach ($request->class_ids as $classId) {
                Notice::create([
                    'teacher_id' => $teacher->id,
                    'class_id'   => $classId,
                    'title'      => $request->title,
                    'content'    => $request->content,
                ]);
            }
        }

        $target = $request->audience === 'schoolwide'
            ? 'the entire school'
            : count($request->class_ids) . ' class(es)';

        return redirect()->route('teacher.notices.index')
            ->with('success', "Notice posted to {$target} successfully.");
    }


    public function destroy(int $id)
    {
        $teacher = Auth::user();

        Notice::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Notice deleted successfully.');
    }
}