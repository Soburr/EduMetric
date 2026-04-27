<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'school_classes';

    protected $fillable = ['name']; // e.g. "JSS1", "JSS2", "SS3A"


    /** All students in this class */
    public function students()
    {
        return $this->hasMany(User::class, 'class_id')->where('role', 'student');
    }

    /** All tests assigned to this class */
    public function tests()
    {
        return $this->hasMany(Test::class, 'class_id');
    }

    /** All study materials shared with this class */
    public function materials()
    {
        return $this->hasMany(StudyMaterial::class, 'class_id');
    }

    /** All notices targeting this class */
    public function notices()
    {
        return $this->hasMany(Notice::class, 'class_id');
    }
}