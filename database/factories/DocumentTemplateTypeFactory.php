<?php

use App\Models\Foodfleet\DocumentTemplateType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(DocumentTemplateType::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
