<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'parent_id',
        'grade_level',
    ];

    public function student ()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function parent ()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }
}
