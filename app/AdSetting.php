<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdSetting extends Model
{
    protected $fillable = [
        'impression_per_dollar',
        'reach_per_dollar',

    ];
}
