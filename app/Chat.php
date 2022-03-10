<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    public $timestamps = true;

    public function members(){
        return $this->hasMany('App\ChatMember', 'chat_id', 'id');
    }

    public function messages(){
        return $this->hasMany('App\ChatMessage', 'chat_id', 'id');
    }

    public function lastMessage(){
        return $this->hasOne('App\ChatMessage')->orderBy('created_at', 'DESC');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
