<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreferenceCategory extends Model
{
    protected $fillable = [
        'name',
        'description',

    ];
}
