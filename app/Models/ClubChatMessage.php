<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClubChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'sender_id',
        'message',
    ];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
    