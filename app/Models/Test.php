<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',        
        'class_id',          
        'subject',           
        'title',             
        'duration_minutes',  
        'is_active',        
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }


    /** The teacher who created this test */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** The class this test is assigned to */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /** All questions in this test */
    public function questions()
    {
        return $this->hasMany(TestQuestion::class, 'test_id');
    }

    /** All student submissions for this test */
    public function submissions()
    {
        return $this->hasMany(TestSubmission::class, 'test_id');
    }


    /** Only tests that are currently active */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Tests for a specific class */
    public function scopeForClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    /** Tests created by a specific teacher */
    public function scopeByTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}