<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommunicationPreference extends Model
{
    protected $fillable = [
        'preference_category_id',
        'name',

    ];
    public function preference_categories()
    {
        return $this->belongsTo('App\PreferenceCategory');
    }
}
