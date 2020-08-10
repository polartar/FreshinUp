<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Square\Payment::class, function (Faker $faker) {
    return [
        "square_id" => $faker->randomNumber(5),
        "amount_money" => $faker->numberBetween(700, 10000),
        "tip_money" => $faker->numberBetween(700, 10000),
        "processing_fee_money" => $faker->numberBetween(700, 10000),
        "square_created_at" => $faker->dateTime('now')
    ];
});
