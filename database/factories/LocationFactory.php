<?php

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
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
$factory->define(Location::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "name" => $faker->city,
        "venue_uuid" => function () {
            return factory(Venue::class)->create()->uuid;
        },
        "spots" => $faker->randomNumber(2),
        "capacity" => $faker->randomNumber(2),
        "details" => $faker->sentences(2, true)
    ];
});
