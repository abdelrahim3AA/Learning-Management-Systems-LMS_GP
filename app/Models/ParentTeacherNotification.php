<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentTeacherNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_id',
        'conversation_id',
        'message_preview',
        'is_read',
    ];

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function conversation()
    {
        return $this->belongsTo(ParentTeacherConversation::class, 'conversation_id');
    }
}
