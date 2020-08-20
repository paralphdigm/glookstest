<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeTypeCategoryUser extends Model
{
    protected $fillable = [
        'user_id',
        'size_type_category_id',

    ];
}
