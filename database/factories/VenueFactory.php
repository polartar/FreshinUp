<?php

use App\Models\Foodfleet\Venue;
use App\Models\Foodfleet\VenueStatus;
use Faker\Generator as Faker;
use App\User;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Venue::class, function (Faker $faker) {
    return [
        "name" => $faker->word,
        "address" => $faker->address,
        "status_id" => function () {
            return factory(VenueStatus::class)->create()->id;
        },
        'owner_uuid' => function () {
            return factory(User::class)->create()->uuid;
        }
    ];
});
