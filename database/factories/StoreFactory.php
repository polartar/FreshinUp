<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Store::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "square_id" => $faker->randomNumber(5)
    ];
});
