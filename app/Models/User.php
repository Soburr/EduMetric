<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'student_id', 'password', 'role', 'class_id'])]
#[Hidden(['password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /** Student belongs to a class */
    public function studentClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
 
    /** Teacher profile (subjects they teach) */
    public function teacherProfile()
    {
        return $this->hasOne(TeacherProfile::class, 'user_id');
    }
 
    /** Tests submitted by this student */
    public function submissions()
    {
        return $this->hasMany(TestSubmission::class, 'student_id');
    }
 
    /** Tests created by this teacher */
    public function createdTests()
    {
        return $this->hasMany(Test::class, 'teacher_id');
    }
 
    /** Materials uploaded by this teacher */
    public function uploadedMaterials()
    {
        return $this->hasMany(StudyMaterial::class, 'teacher_id');
    }
 
    // ── Helpers ──────────────────────────────────────────────────
 
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
 
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }
 
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    
}
