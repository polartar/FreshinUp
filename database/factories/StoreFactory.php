<?php

use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreStatus;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Store::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "square_id" => $faker->randomNumber(5),
        "status_id" => function () {
            return factory(StoreStatus::class)->create();
        }
    ];
});
