<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_id',
        'score',       // number of correct answers
        'total',       // total number of questions
        'percentage',  // calculated: (score/total) * 100
    ];

    protected function casts(): array
    {
        return [
            'score'      => 'integer',
            'total'      => 'integer',
            'percentage' => 'integer',
        ];
    }

    /** The student who submitted */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /** The test that was submitted */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /** All individual answers in this submission */
    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'submission_id');
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function getGradeAttribute(): string
    {
        return match (true) {
            $this->percentage >= 70 => 'A',
            $this->percentage >= 60 => 'B',
            $this->percentage >= 50 => 'C',
            $this->percentage >= 44 => 'D',
            $this->percentage >= 40 => 'E',
            default                 => 'F',
        };
    }
}