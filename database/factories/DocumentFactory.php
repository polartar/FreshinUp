<?php

use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\DocumentStatus;
use App\Models\Foodfleet\DocumentType;
use App\User;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Document::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'description' => $faker->realText(),
        'notes' => $faker->realText(),
        'expiration_at' => $faker->dateTimeBetween('+1 days', '+10 days'),
        'status_id' => function () {
            return factory(DocumentStatus::class)->create()->id;
        },
        'type_id' => function () {
            return factory(DocumentType::class)->create()->id;
        },
        'created_by_uuid' => function () {
            return factory(User::class)->create()->uuid;
        },
        'template_uuid' => function () {
            return factory(Template::class)->create()->uuid;
        }
    ];
});
