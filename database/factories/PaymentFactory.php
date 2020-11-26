<?php

use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentStatus;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Transaction;
use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Payment::class, function (Faker $faker) {
    return [
        "amount_money" => $faker->numberBetween(700, 10000),
        "tip_money" => $faker->numberBetween(700, 10000),
        "processing_fee_money" => $faker->numberBetween(700, 10000),
        "square_created_at" => $faker->dateTime('now'),
        "square_id" => $faker->randomNumber(5),
        'due_date' => $faker->date(),
        'name' => $faker->word(),
        'description' => $faker->text(50),
        'created_at' => $faker->dateTimeBetween('-90 days', '-30 days'),
        'updated_at' => $faker->dateTimeBetween('-90 days', '-30 days'),
        'status_id' => function () {
            return factory(PaymentStatus::class)->create()->id;
        },
        "payment_type_uuid" => function () {
            return factory(PaymentType::class)->create()->uuid;
        },
        "transaction_uuid" => function () {
            return factory(Transaction::class)->create()->uuid;
        },
    ];
});
