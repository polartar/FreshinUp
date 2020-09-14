<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Venue::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "name" => $faker->word,
        "address" => $faker->address
    ];
});
