<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'teacher_id',
        'start_time',
        'end_time'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
