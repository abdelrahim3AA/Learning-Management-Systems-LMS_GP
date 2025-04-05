<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'question_text',
        'question_type',
    ];

    // Relationship to the Lesson model
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
