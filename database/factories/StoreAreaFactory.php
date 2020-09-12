<?php

use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(StoreArea::class, function (Faker $faker) {
    return [
        'name' => $faker->streetAddress,
        'radius' => $faker->randomNumber(2),
        'state' => $faker->state,
        'store_uuid' => function () {
            return factory(Store::class)->create()->uuid;
        }
    ];
});
