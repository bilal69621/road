<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeletedMessage extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
