<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Foodfleet\Message::class, function (Faker $faker) {
    return [
        "content" => $faker->word,
        "created_at" => $faker->dateTime('now')
    ];
});
