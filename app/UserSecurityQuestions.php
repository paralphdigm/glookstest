<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSecurityQuestions extends Model
{
    protected $fillable = [
        'question_id',
        'user_id',
        'answer',

    ];
}
