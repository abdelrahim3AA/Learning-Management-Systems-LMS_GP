<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'due_date',
    ];

    // Relationship
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
