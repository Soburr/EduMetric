<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolCalendar;
use App\Models\SubjectScore;
use Illuminate\Http\Request;

class AdminCalendarController extends Controller
{
    public function index()
    {
        $currentYear = date('Y');
        $sessions    = [];
        for ($i = 0; $i < 4; $i++) {
            $start      = $currentYear - $i;
            $sessions[] = $start . '/' . ($start + 1);
        }

        $calendars = SchoolCalendar::all()
            ->keyBy(fn($c) => $c->term . '_' . $c->session);

        return view('admin.calendar', compact('sessions', 'calendars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entries'              => ['required', 'array'],
            'entries.*.term'       => ['required', 'in:first,second,third'],
            'entries.*.session'    => ['required', 'string'],
            'entries.*.days_opened' => ['nullable', 'integer', 'min:1', 'max:365'],
        ]);

        foreach ($request->entries as $entry) {

            if (empty($entry['days_opened'])) continue;

            SchoolCalendar::updateOrCreate(
                ['term' => $entry['term'], 'session' => $entry['session']],
                ['days_opened' => $entry['days_opened']]
            );
        }

        return back()->with('success', 'School calendar updated successfully.');
    }
}
