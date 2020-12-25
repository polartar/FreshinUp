<?php

use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreStatus;
use App\Models\Foodfleet\StoreType;
use App\User;
use Faker\Generator as Faker;
use FreshinUp\FreshBusForms\Models\Address\Address;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Store::class, function (Faker $faker) {
    return [
        'owner_uuid' => function () {
            return factory(User::class)->create()->uuid;
        },
        'type_id' => function () {
            return factory(StoreType::class)->create();
        },
        'name' => $faker->word,
        'size' => $faker->randomNumber(1),
        'contact_phone' => $faker->phoneNumber,
        'state_of_incorporation' => $faker->state,
        'website' => $faker->url,
        'twitter' => $faker->url,
        'facebook' => $faker->url,
        'instagram' => $faker->url,
        'staff_notes' => $faker->text,
        // "square_id" => $faker->uuid,
        "status_id" => function () {
            return factory(StoreStatus::class)->create()->id;
        },
        "address_uuid" => function () {
            return factory(Address::class)->create()->uuid;
        },
        "supplier_uuid" => function () {
            return factory(FreshinUp\FreshBusForms\Models\Company\Company::class)->create()->uuid;
        }
    ];
});
