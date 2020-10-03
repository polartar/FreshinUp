<?php

use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\Document\Template\Status;
use App\Models\Foodfleet\Document\Template\Type;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Template::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "title" => $faker->word,
        "type_id" => function () {
            return factory(Type::class)->create()->id;
        },
        "status_id" => function () {
            return factory(Status::class)->create()->id;
        }
    ];
});
