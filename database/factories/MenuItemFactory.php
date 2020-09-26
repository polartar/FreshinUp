<?php

use App\Models\Foodfleet\MenuItem;
use App\Models\Foodfleet\Store;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(MenuItem::class, function (Faker $faker) {
    return [
        "title" => $faker->word,
        "servings" => $faker->numberBetween(1, 50),
        "cost" => $faker->numberBetween(700, 10000),
        "description" => $faker->realText(),
        "store_uuid" => function () {
            return factory(Store::class)->create()->uuid;
        },
    ];
});
