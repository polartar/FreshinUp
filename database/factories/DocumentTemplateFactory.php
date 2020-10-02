<?php

use App\Models\Foodfleet\DocumentTemplate;
use App\Models\Foodfleet\DocumentTemplateStatus;
use App\Models\Foodfleet\DocumentTemplateType;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(DocumentTemplate::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "title" => $faker->word,
        "type_id" => function () {
            return factory(DocumentTemplateType::class)->create()->id;
        },
        "status_id" => function () {
            return factory(DocumentTemplateStatus::class)->create()->id;
        }
    ];
});
