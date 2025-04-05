<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherAssignmentReview extends Model
{
    use HasFactory;

    protected $table = 'teacher_assignment_review'; // تحديد اسم الجدول

    protected $fillable = [
        'submission_id',
        'teacher_id',
        'feedback',
        'score',
    ];

    // العلاقات
    public function submission()
    {
        return $this->belongsTo(StudentSubmission::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
