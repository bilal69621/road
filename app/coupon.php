<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class coupon extends Model
{
    public $timestamps = false;
    protected $table = 'coupons';
    protected $guarded = ['coupon','discount','valid_till','valid','name'];
}
