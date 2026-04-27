<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'question_id',
        'selected_option',  // foreign key to TestOption
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }


    /** The submission this answer belongs to */
    public function submission()
    {
        return $this->belongsTo(TestSubmission::class, 'submission_id');
    }

    /** The question being answered */
    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'question_id');
    }

    /** The option the student selected */
    public function selectedOption()
    {
        return $this->belongsTo(TestOption::class, 'selected_option');
    }
}