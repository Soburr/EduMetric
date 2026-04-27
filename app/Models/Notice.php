<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',  
        'class_id',    // null = school-wide notice
        'title',
        'content',
    ];

    /** The teacher who posted this notice */
    public function author()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** The class this notice targets (null = all classes) */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // ── Scopes ───────────────────────────────────────────────────

    /**
     * Notices visible to a specific class:
     * includes class-specific AND school-wide (class_id = null)
     */
    public function scopeVisibleToClass($query, int $classId)
    {
        return $query->where(function ($q) use ($classId) {
            $q->where('class_id', $classId)
              ->orWhereNull('class_id');
        });
    }
}