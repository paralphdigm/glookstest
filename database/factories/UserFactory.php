<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'membership_number' => $faker->unique()->safeEmail,
        'first_name' => $faker->text(20),
        'last_name' => $faker->text(20),
        'email' => $faker->unique()->safeEmail,
        'gender' => $faker->randomElement(['male', 'female']),
        'mobile_number' => $faker->text(20),
        'landline_number' => $faker->text(20),
        'birthdate' => now(),
        'verification_code' => $faker->text(6),
        'verification_code_status' => $faker->randomElement(['active', 'inactive']),
        'marital_status' => $faker->randomElement(['married', 'single', 'divorced', 'widowed','others']),
        'account_status' => $faker->randomElement(['active','inactive','suspended','deleted']),
        'email_verified_at' => now(),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => $faker->text(20),
        'created_by' => $faker->text(20),
        'updated_by' => $faker->text(20),
        'deleted_by' => $faker->text(20),
    ];
});


