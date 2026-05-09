<?php

namespace App\Http\Controllers;

use App\Models\ReportCardMeta;
use App\Models\User;
use Illuminate\Http\Request;

class ReportCardMetaController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'class_id'   => ['required', 'exists:school_classes,id'],
            'term'       => ['required', 'in:first,second,third'],
            'session'    => ['required', 'string'],
        ]);

        ReportCardMeta::updateOrCreate(
            [
                'student_id' => $request->student_id,
                'term'       => $request->term,
                'session'    => $request->session,
            ],
            $request->except(['_token', 'student_id', 'term', 'session']) + [
                'student_id' => $request->student_id,
                'class_id'   => $request->class_id,
                'term'       => $request->term,
                'session'    => $request->session,
            ]
        );

        return back()->with('success', 'Report card details saved successfully.');
    }
}