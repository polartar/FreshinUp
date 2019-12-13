<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\Foodfleet\EventOccurrence::class, function (Faker $faker) {
    return [
        "start_at" => $faker->dateTime('now'),
        "end_at" => $faker->dateTimeBetween('+1 days', '+2 days')
    ];
});
