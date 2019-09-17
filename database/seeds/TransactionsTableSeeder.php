<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Transaction;
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

        for ($i = 0; $i < 1000; $i++) {
            $transaction = factory(Transaction::class)->create([
                'customer_uuid' => $customers->random()->uuid,
                'event_uuid' => $events->random()->uuid,
                'square_created_at' => $faker->dateTimeBetween('-190 days', 'now')
            ]);
            $itemRandomUuids = $items->random(2)->pluck('uuid')->toArray();
            $transaction->items()->sync([
                $itemRandomUuids[0] => ['quantity' => $faker->numberBetween(1, 10)],
                $itemRandomUuids[1] => ['quantity' => $faker->numberBetween(1, 10)]
            ]);
        }
    }
}
