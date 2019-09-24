<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
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

$factory->define(Document::class, function (Faker $faker) {
    return [
        'title' => $faker->word,
        'type' => $faker->numberBetween(1, 2),
        'status' => $faker->numberBetween(1, 2),
        'description' => Str::random(50),
        'notes' => Str::random(20),
        'expiration_at' => $faker->dateTimeBetween('+1 days', '+10 days')
    ];
});
