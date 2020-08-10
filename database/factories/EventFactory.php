<?php

use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\EventStatus;
use App\Models\Foodfleet\Location;
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
$factory->define(\App\Models\Foodfleet\Event::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "name" => $faker->word,
        "type" => $faker->randomNumber(),
        "location_uuid" => factory(Location::class)->create()->uuid,
        "start_at" => $faker->dateTime('now +1 hour'),
        "end_at" => $faker->dateTimeBetween('+1 days', '+2 days'),
        "host_uuid" => factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create()->uuid,
        "host_status" => $faker->randomNumber(),
        "manager_uuid" => factory(User::class)->create()->uuid,
        "status_id" => factory(EventStatus::class)->create()->id,
        "budget" => $faker->numberBetween(1000, 100000),
        "attendees" => $faker->numberBetween(),
        "commission_rate" => $faker->randomFloat(0.1, .5),
        "commission_type" => $faker->numberBetween(),
        "created_at" => $faker->dateTimeBetween('-30days', '+30days'),
        "updated_at" => $faker->dateTimeBetween('-30days', '+30days')
    ];
});
