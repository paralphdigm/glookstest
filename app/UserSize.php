<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSize extends Model
{
    protected $fillable = [
        'user_id',
        'user_size',

    ];
}
