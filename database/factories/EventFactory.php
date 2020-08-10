<?php

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

$factory->define(\App\Models\Foodfleet\Event::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "start_at" => $faker->dateTime('now +1 hour'),
        "end_at" => $faker->dateTimeBetween('+1 days', '+2 days'),
        "staff_notes" => $faker->text,
        "member_notes" => $faker->text,
        "customer_notes" => $faker->text
    ];
});
