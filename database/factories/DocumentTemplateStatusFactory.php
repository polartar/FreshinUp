<?php

use App\Models\Foodfleet\DocumentTemplateStatus;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(DocumentTemplateStatus::class, function (Faker $faker) {
    return [
        "name" => $faker->word
    ];
});
