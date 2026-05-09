<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'subject',
        'term',
        'session',
        'cbt_score',
        'ca_score',
        'exam_score',
        'total',
        'grade',
        'remark',
    ];

    protected function casts(): array
    {
        return [
            'cbt_score' => 'decimal:2',
            'ca_score' => 'decimal:2',
            'exam_score' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }


    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Auto-calculate total and grade whenever ca or exam is updated.
     */
public function recalculate(): void
{
    $total = ($this->cbt_score  ?? 0)
           + ($this->exam_score ?? 0);

    $this->total = round($total, 2);
    $this->grade = self::computeGrade($total);
    $this->save();
}

    /**
     * Scale a raw CBT percentage to /20.
     */
    public static function scaleCbt(int $score, int $total): float
    {
        if ($total === 0)
            return 0;
        return round(($score / $total) * 20, 2);
    }

    /**
     * Convert total score to letter grade.
     */
    public static function computeGrade(float $total): string
    {
    return match(true) {
        $total >= 75 => 'A1',
        $total >= 70 => 'B2',
        $total >= 65 => 'B3',
        $total >= 60 => 'C4',
        $total >= 55 => 'C5',
        $total >= 50 => 'C6',
        $total >= 45 => 'D7',
        $total >= 40 => 'E8',
        default      => 'F9',
    };
    }

    /**
     * Human-readable term label.
     */
    public static function termLabel(string $term): string
    {
        return match ($term) {
            'first' => 'First Term',
            'second' => 'Second Term',
            'third' => 'Third Term',
            default => ucfirst($term) . ' Term',
        };
    }
}