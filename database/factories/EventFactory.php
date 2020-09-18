<?php

use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventStatus;
use App\Models\Foodfleet\EventType;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
use App\User;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Event::class, function (Faker $faker) {
    return [
        "uuid" => $faker->uuid,
        "name" => $faker->text(50),
        "type_id" => function () {
            return factory(EventType::class)->create();
        },
        "status_id" => function () {
            return factory(EventStatus::class)->create();
        },
        "location_uuid" =>  function () {
            return factory(Location::class)->create()->uuid;
        },
        "start_at" => $faker->dateTime('now +1 hour'),
        "end_at" => $faker->dateTimeBetween('+1 days', '+2 days'),
        "staff_notes" => $faker->text,
        "member_notes" => $faker->text,
        "customer_notes" => $faker->text,
        "host_uuid" => factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create()->uuid,
        "host_status" => $faker->randomNumber(),
        "manager_uuid" => factory(User::class)->create()->uuid,
        "budget" => $faker->numberBetween(1000, 100000),
        "attendees" => $faker->numberBetween(),
        "commission_rate" => $faker->randomFloat(0.1, .5),
        "commission_type" => $faker->numberBetween(),
        "created_at" => $faker->dateTimeBetween('-30days', '+30days'),
        "updated_at" => $faker->dateTimeBetween('-30days', '+30days'),
        "venue_uuid" => function () {
            return factory(Venue::class)->create()->uuid;
        }
    ];
});
