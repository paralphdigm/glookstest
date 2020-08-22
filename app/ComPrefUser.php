<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComPrefUser extends Model
{
    protected $fillable = [
        'user_id',
        'communication_preference_id',

    ];
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
