<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    protected $fillable = [
        'app_name',
        'app_id',
        'api_token',
        'api_secret',

    ];
}
