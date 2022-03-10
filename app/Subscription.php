<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['stripe_id','user_id','name','total_miles','counter','stripe_plan','quantity'];
    public function getUser(){
        return $this->hasOne(User::class,'id','user_id');
    }
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
