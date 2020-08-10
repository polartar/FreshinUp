<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Square\Transaction::class, function (Faker $faker) {
    return [
        "square_id" => $faker->randomNumber(5),
        "total_money" => $faker->numberBetween(700, 10000),
        "total_tax_money" => $faker->numberBetween(700, 10000),
        "total_discount_money" => $faker->numberBetween(700, 10000),
        "total_service_charge_money" => $faker->numberBetween(700, 10000),
        "square_created_at" => $faker->dateTime('now')
    ];
});
