<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'user_id', 'job_id', 'status', 'lat', 'lng', 'type','swoop_token'
    ];
    
    
        public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
        }
}
