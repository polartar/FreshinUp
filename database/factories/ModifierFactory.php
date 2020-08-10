<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\Foodfleet\FinancialModifier::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'resource_name' => $faker->word,
        'label' => $faker->word,
        'placeholder' => $faker->word,
        'type' => $faker->randomElement(['autocomplete', 'select', 'date', 'text']),
        'filter' => $faker->randomElement([null, $faker->word]),
        'value_param' => $faker->randomElement([null, $faker->word]),
        'text_param' => $faker->randomElement([null, $faker->word])
    ];
});
