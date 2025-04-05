<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'exams';

    // Define fillable fields to protect from mass-assignment vulnerability
    protected $fillable = [
        'course_id',
        'title',
        'exam_date'
    ];

    // Define the relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
