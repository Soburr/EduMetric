<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCardMeta extends Model
{
    protected $fillable = [
        'student_id', 'class_id', 'term', 'session',
        'times_opened', 'times_present', 'times_absent',
        'term_begins', 'term_ends', 'next_term_begins', 'terminal_duration',
        'obedience', 'honesty', 'self_control', 'self_reliance', 'use_of_initiative',
        'punctuality', 'neatness', 'perseverance', 'attendance_rating', 'attentiveness',
        'courtesy', 'consideration', 'sociability', 'consistency', 'accept_responsibility',
        'reading_writing', 'verbal_communication', 'sports_games', 'inquisitiveness', 'dexterity',
        'class_teacher_comment', 'principal_comment', 'promoted_repeated', 'next_term_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}