<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\StudyMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherMaterialController extends Controller
{
    /**
     * Show all materials uploaded by this teacher.
     */
    public function index()
    {
        $teacher  = Auth::user();
        $subjects = $teacher->teacherProfile?->subjects ?? [];
        $classes  = SchoolClass::orderBy('name')->get();

        $materials = StudyMaterial::where('teacher_id', $teacher->id)
            ->with('schoolClass')
            ->latest()
            ->get();

        return view('teacher.study-materials', compact('materials', 'subjects', 'classes'));
    }

    /**
     * Handle file upload — creates one StudyMaterial record per selected class.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'subject'     => ['required', 'string'],
            'class_ids'   => ['required', 'array', 'min:1'],
            'class_ids.*' => ['exists:school_classes,id'],
            'file'        => [
                'required',
                'file',
                'max:102400', // 100MB in kilobytes
                'mimes:pdf,doc,docx,ppt,pptx,mp4,mov,avi,jpg,jpeg,png,gif',
            ],
        ], [
            'class_ids.required' => 'Please select at least one class.',
            'file.max'           => 'File size must not exceed 100MB.',
            'file.mimes'         => 'File type not allowed. Allowed: PDF, DOCX, PPTX, MP4, MOV, JPG, PNG.',
        ]);

        $teacher = Auth::user();
        $file    = $request->file('file');

        // Store file once in private storage
        $path = $file->store('materials', 'private');

        // Create one record per selected class
        foreach ($request->class_ids as $classId) {
            StudyMaterial::create([
                'teacher_id' => $teacher->id,
                'class_id'   => $classId,
                'subject'    => $request->subject,
                'title'      => $request->title,
                'file_name'  => $file->getClientOriginalName(),
                'file_path'  => $path,
                'file_size'  => $file->getSize(),
            ]);
        }

        $classCount = count($request->class_ids);

        return redirect()->route('teacher.materials.index')
            ->with('success', "Material uploaded successfully to {$classCount} class(es).");
    }

    /**
     * Delete a material (only if it belongs to this teacher).
     * Smart file deletion — only removes from disk if no other records share the same file.
     */
    public function destroy(int $id)
    {
        $teacher  = Auth::user();
        $material = StudyMaterial::where('id', $id)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        // Only delete the actual file if no other class records reference it
        $sameFileCount = StudyMaterial::where('file_path', $material->file_path)
            ->where('id', '!=', $material->id)
            ->count();

        if ($sameFileCount === 0) {
            Storage::disk('private')->delete($material->file_path);
        }

        $material->delete();

        return back()->with('success', 'Material deleted successfully.');
    }
}