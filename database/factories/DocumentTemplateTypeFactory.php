<?php

use App\Models\Foodfleet\Document\Template\Type;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Type::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
