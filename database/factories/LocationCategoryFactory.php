<?php

use App\Models\Foodfleet\LocationCategory;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(LocationCategory::class, function (Faker $faker) {
    return [
        "name" => $faker->city
    ];
});
