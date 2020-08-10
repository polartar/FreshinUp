<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\Message::class, function (Faker $faker) {
    return [
        "content" => $faker->word,
        "created_at" => $faker->dateTime('now')
    ];
});
