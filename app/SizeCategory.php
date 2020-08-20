<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SizeCategory extends Model
{
    protected $fillable = [
        'name',
        'description',

    ];
    public function size_type_categories()
    {
        return $this->hasMany('App\SizeTypeCategory');
    }
}
