<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Store;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        $customers = Customer::get();
        $items = Item::get();
        $events = Event::get();
        $stores = Store::get();

        for ($i = 0; $i < 1000; $i++) {
            $transaction = factory(Transaction::class)->create([
                'customer_uuid' => $customers->random()->uuid,
                'event_uuid' => $events->random()->uuid,
                'square_created_at' => $faker->dateTimeBetween('-190 days', 'now'),
                'store_uuid' => $stores->random()->uuid
            ]);
            $itemRandomUuids = $items->random(2)->pluck('uuid')->toArray();
            $transaction->items()->sync([
                $itemRandomUuids[0] => [
                    'quantity' => $faker->numberBetween(1, 10),
                    "total_money" => $faker->numberBetween(700, 10000),
                    "total_tax_money" => $faker->numberBetween(700, 10000),
                    "total_discount_money" => $faker->numberBetween(700, 10000)
                ],
                $itemRandomUuids[1] => [
                    'quantity' => $faker->numberBetween(1, 10),
                    "total_money" => $faker->numberBetween(700, 10000),
                    "total_tax_money" => $faker->numberBetween(700, 10000),
                    "total_discount_money" => $faker->numberBetween(700, 10000)
                ]
            ]);
        }
    }
}
