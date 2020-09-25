<?php

use App\Models\Foodfleet\DocumentStatus;
use App\Models\Foodfleet\DocumentType;
use App\Models\Foodfleet\Store;
use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use App\Models\Foodfleet\Document;

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

    $assigned_type = $faker->randomElement([Document::class, ]);
    $assigned_uuid = '';
    return [
        'title' => $faker->word,
        'description' => Str::random(50),
        'notes' => Str::random(20),
        'expiration_at' => $faker->dateTimeBetween('+1 days', '+10 days'),
        'status' => function () {
            return factory(DocumentStatus::class)->create()->id;
        },
        'type' => function () {
            return factory(DocumentType::class)->create()->id;
        },
        'created_by_uuid' => function () {
            return factory(User::class)->create()->uuid;
        },
        'event_store_uuid' => function () {
            return factory(Store::class)->create()->uuid;
        },
        'assigned_uuid' => $assigned_uuid,
        'assigned_type' => $assigned_type
    ];
});
