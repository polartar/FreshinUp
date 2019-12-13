<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(\App\Models\Foodfleet\EventSchedule::class, function (Faker $faker) {
    return [
        'interval_unit' => $faker->randomElement(['Year(s)', 'Month(s)', 'Week(s)']),
        'interval_value' => $faker->randomNumber(5),
        'ends_on' => $faker->randomElement(['after', 'on']),
        "occurrences" => $faker->numberBetween(1, 10),
        'description' => Str::random(50)
    ];
});
