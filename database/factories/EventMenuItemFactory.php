<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\EventMenuItem::class, function (Faker $faker) {
    return [
        'item' => $faker->word,
        "servings" => $faker->numberBetween(1, 50),
        "cost" => $faker->numberBetween(700, 10000)
    ];
});
