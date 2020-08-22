<?php


use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(EventHistory::class, function (Faker $faker) {
    return [
        "status_id" => function () {
            return factory(EventStatus::class)->create();
        },
        "description" => $faker->word,
        "completed" => $faker->boolean,
        "date" => $faker->dateTime,
        "event_uuid" => function () {
            return factory(Event::class)->create();
        }
    ];
});
