<?php

use App\Models\Foodfleet\Square\PaymentStatus;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(PaymentStatus::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
