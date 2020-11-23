<?php

use App\Models\Foodfleet\Venue;
use App\Models\Foodfleet\VenueStatus;
use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Venue::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "name" => $faker->word,
        "address_line_1" => $faker->streetAddress,
        "address_line_2" => $faker->streetAddress,
        "status_id" => function () {
            return factory(VenueStatus::class)->create()->id;
        },
        'owner_uuid' => function () {
            return factory(User::class)->create()->uuid;
        },
        'created_at' => $faker->dateTimeBetween('-30days', '+30days'),
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
    ];
});
