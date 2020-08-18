<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'house_number',
        'address_line_1',
        'address_line_2',
        'city',
        'post_code',
        'country',

    ];
}
