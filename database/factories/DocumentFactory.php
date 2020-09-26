<?php

use App\Models\Foodfleet\DocumentType;
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
    // *** Commented out because this can cause more problem than the actual benefits
    // $assigned_type = $faker->randomElement([User::class, Store::class, Event::class, Location::class]);
    // $assigned_uuid = factory($assigned_type)->create()->uuid;
    return [
        'title' => $faker->word,
        'description' => $faker->realText(),
        'notes' => $faker->realText(),
        'expiration_at' => $faker->dateTimeBetween('+1 days', '+10 days'),
        'status' => '',
        'type' => function () {
            return factory(DocumentType::class)->create()->id;
        },
        'created_by_uuid' => function () {
            return factory(User::class)->create()->uuid;
        },
        // 'assigned_uuid' => $assigned_uuid,
        // 'assigned_type' => $assigned_type,
        // 'event_store_uuid' => '',
    ];
});
