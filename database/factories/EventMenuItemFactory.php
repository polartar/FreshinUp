<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EventMenuItem;
use Faker\Generator as Faker;

$factory->define(\App\Models\Foodfleet\EventMenuItem::class, function (Faker $faker) {
    return [
        'item' => $faker->word,
        "servings" => $faker->numberBetween(1, 50),
        "cost" => $faker->numberBetween(700, 10000)
    ];
});
