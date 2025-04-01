<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'sender_role',
        'receiver_id',
        'receiver_role',
        'message',
        'timestamp'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }
}
