<?php
namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Model;

class card extends Model
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'user_id', 'stripe_id', 'card_holder_name', 'brand', 'last4', 'exp_month','exp_year','country','is_default','created_at'
    ];
    
     public function cards()
    {
        return $this->hasMany(card::class, 'user_id');
    }
    public function defaultCard(){
        return $this->hasOne(card::class, 'user_id', 'id')->where('is_default','=', true);
    }
}

