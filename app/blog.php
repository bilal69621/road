<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blog extends Model
{
    protected  $fillable = ['name','blog','main_image','category_id'];
}
