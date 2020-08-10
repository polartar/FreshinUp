<?php

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
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Square\Customer::class, function (Faker $faker) {
    return [
        "given_name" => $faker->firstName,
        "family_name" => $faker->lastName,
        "square_id" => $faker->randomNumber(5),
        "reference_id" => $faker->randomNumber(5)
    ];
});
