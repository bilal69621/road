<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatMember extends Model
{
    public function user(){
        return $this->belongsTo('App\User', 'member_id', 'id');
    }

    public function chat(){
        return $this->belongsTo('App\Chat', 'chat_id', 'id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
