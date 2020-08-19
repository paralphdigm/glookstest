<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SecurityQuestionUser extends Model
{
    protected $fillable = [
        'user_id',
        'security_question_id',
        'answer',

    ];
    
    public function security_questions()
    {
        return $this->belongsTo('App\SecurityQuestion');
    }
    public function users()
    {
        return $this->belongsTo('App\User');
    }
    
}
