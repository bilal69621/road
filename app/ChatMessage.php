<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ChatMessage extends Model
{
    public function sender(){
        return $this->belongsTo('App\User', 'sender_id', 'id');
    }

    public function receiver(){
        return $this->hasOne('App\ChatMember', 'chat_id','chat_id');
    }

    public function chat(){
        return $this->belongsTo('App\Chat', 'chat_id', 'id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
