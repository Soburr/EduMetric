<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudyMaterial;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentMaterialController extends Controller
{
    /**
     * List all study materials for the student's class.
     */
    public function index()
    {
        $user = Auth::user();

        $materials = StudyMaterial::where('class_id', $user->class_id)
            ->latest()
            ->get()
            ->map(function ($material) {
                $material->uploader_name = $material->uploader->name ?? 'Teacher';
                return $material;
            });

        return view('student.study-materials', compact('materials'));
    }

    /**
     * Stream/download a specific material file.
     */
    public function download(int $id): StreamedResponse
    {
        $user     = Auth::user();
        $material = StudyMaterial::where('id', $id)
            ->where('class_id', $user->class_id)
            ->firstOrFail();

        // Make sure the file exists in storage
        if (!Storage::disk('private')->exists($material->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::disk('private')->download(
            $material->file_path,
            $material->file_name
        );
    }
}