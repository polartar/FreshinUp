<?php

use App\Models\Foodfleet\DocumentType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(DocumentType::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
