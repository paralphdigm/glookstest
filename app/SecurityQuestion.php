<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class SecurityQuestion extends Model
{
    protected $fillable = [
        'question',
        'description',

    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
