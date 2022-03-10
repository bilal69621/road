<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cancelationReasons extends Model
{
    protected $fillable = ['user_id', 'reason', 'detail_reason', 'cancel_type'];
    
        public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}


