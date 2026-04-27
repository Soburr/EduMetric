<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudyMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',  
        'class_id',    
        'subject',    
        'title',       // display title e.g. "Chapter 3 Notes"
        'file_name',   // original file name e.g. "chapter3.pdf"
        'file_path',   // storage path e.g. "materials/abc123.pdf"
        'file_size',   
    ];

    /** The teacher who uploaded this material */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /** The class this material is shared with */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // ── Helpers ──────────────────────────────────────────────────

    /** Human readable file size e.g. "2.4 MB" */
    public function getFileSizeFormattedAttribute(): string
    {
        if (!$this->file_size) return '—';

        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->file_size;
        $i     = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 1) . ' ' . $units[$i];
    }

    /** File extension e.g. "pdf", "docx" */
    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->file_name, PATHINFO_EXTENSION));
    }
}