<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question_text',
        'order',         // optional ordering of questions
    ];

    /** The test this question belongs to */
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }

    /** All answer options for this question */
    public function options()
    {
        return $this->hasMany(TestOption::class, 'question_id');
    }

    /** The correct option (convenience) */
    public function correctOption()
    {
        return $this->hasOne(TestOption::class, 'question_id')->where('is_correct', true);
    }

    /** All student answers submitted for this question */
    public function answers()
    {
        return $this->hasMany(TestAnswer::class, 'question_id');
    }
}