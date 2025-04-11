<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIChatLog extends Model
{
    use HasFactory;


    protected $table = 'ai_chat_logs'; // Specify the correct table name
    protected $fillable = [
        'user_id',
        'message',
        'is_ai'
    ];



    // Relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
