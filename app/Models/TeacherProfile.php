<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subjects',   
        'is_class_teacher',
        'assigned_class_id',
    ];

    protected function casts(): array
{
    return [
        'subjects' => 'array',
        'is_class_teacher' => 'boolean',
    ];
}

    /** The teacher (user) this profile belongs to */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** All tests this teacher has created */
    public function tests()
    {
        return $this->hasMany(Test::class, 'teacher_id', 'user_id');
    }

    /** All materials this teacher has uploaded */
    public function materials()
    {
        return $this->hasMany(StudyMaterial::class, 'teacher_id', 'user_id');
    }

    public function assignedClass()
{
    return $this->belongsTo(SchoolClass::class, 'assigned_class_id');
}
}