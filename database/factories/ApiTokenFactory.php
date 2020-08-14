<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ApiToken;
use Faker\Generator as Faker;

$factory->define(ApiToken::class, function (Faker $faker) {
    $token = Str::random(60);
    $secret = 'SC' . Str::random(60);
    
    return [
        'app_name' => 'GLOOKS-FRONTEND',
        'app_id' => 'APP'. Str::random(60),
        'api_token' => hash('sha256', $token),
        'api_secret' => hash('sha256', $secret),
    ];
});
