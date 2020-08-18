<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSecurityQuestion extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'answer',

    ];
}
