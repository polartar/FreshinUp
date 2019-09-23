<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

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
