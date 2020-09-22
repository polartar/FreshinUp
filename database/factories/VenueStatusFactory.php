<?php

use App\Models\Foodfleet\VenueStatus;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(VenueStatus::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
