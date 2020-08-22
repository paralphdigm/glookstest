<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeTypeCategoryUser extends Model
{
    protected $fillable = [
        'user_id',
        'size_type_category_id',

    ];
    public function size_type_categories()
    {
        return $this->belongsTo('App\SizeTypeCategory');
    }
    public function users()
    {
        return $this->belongsTo('App\User');
    }
}
