<?php

use App\Models\Foodfleet\Document\Template\Status;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Status::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
