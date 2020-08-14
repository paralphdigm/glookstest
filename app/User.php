<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'membership_number',
        'first_name',
        'last_name',
        'email',
        'gender',
        'mobile_number',
        'landline_number',
        'birthdate',
        'verification_code',
        'verification_code_status',
        'marital_status',
        'account_status',
        'email_verified_at',
        'password',
        'created_by'.
        'updated_by',
        'deleted_by',

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
