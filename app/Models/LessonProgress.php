<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    /** @use HasFactory<\Database\Factories\LessonProgressFactory> */
    use HasFactory;
    protected $fillable = [
        'student_id',
        'lesson_id',
        'progress_percentage',
        'status',
        'completed_at',
        'last_accessed',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
