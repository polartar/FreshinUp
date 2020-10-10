<?php

use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\Document\Template\Status;
use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Template::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "title" => $faker->word,
        "description" => $faker->realText(500),
        "content" => $faker->randomHtml(),
        "status_id" => function () {
            return factory(Status::class)->create()->id;
        },
        "updated_by_uuid" => function () {
            return factory(User::class)->create()->uuid;
        }
    ];
});
