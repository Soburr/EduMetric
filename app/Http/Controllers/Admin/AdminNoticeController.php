<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNoticeController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::orderBy('name')->get();

        // Admin sees ALL notices from everyone
        $notices = Notice::with(['author', 'schoolClass'])
            ->latest()
            ->get();

        return view('admin.notices', compact('notices', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'audience'    => ['required', 'in:class,schoolwide'],
            'content'     => ['required', 'string'],
            'class_ids'   => ['required_if:audience,class', 'array', 'min:1'],
            'class_ids.*' => ['exists:school_classes,id'],
        ], [
            'class_ids.required_if' => 'Please select at least one class.',
        ]);

        if ($request->audience === 'schoolwide') {
            Notice::create([
                'teacher_id' => Auth::id(),
                'class_id'   => null,
                'title'      => $request->title,
                'content'    => $request->content,
            ]);
        } else {
            foreach ($request->class_ids as $classId) {
                Notice::create([
                    'teacher_id' => Auth::id(),
                    'class_id'   => $classId,
                    'title'      => $request->title,
                    'content'    => $request->content,
                ]);
            }
        }

        $target = $request->audience === 'schoolwide'
            ? 'the entire school'
            : count($request->class_ids) . ' class(es)';

        return back()->with('success', "Notice posted to {$target} successfully.");
    }

    public function destroy(int $id)
    {
        // Admin can delete ANY notice
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return back()->with('success', 'Notice deleted successfully.');
    }
}