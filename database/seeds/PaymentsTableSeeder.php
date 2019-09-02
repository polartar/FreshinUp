<?php

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        $locations = Location::get();
        $devices = Device::get();
        $customers = Customer::get();
        $paymentTypes = PaymentType::get();
        $items = Item::get();

        for ($i = 0; $i < 500; $i++) {
            $payment = factory(Payment::class)->create([
                'location_uuid' => $locations->random()->uuid,
                'device_uuid' => $devices->random()->uuid,
                'customer_uuid' => $customers->random()->uuid,
                'payment_type_uuid' => $paymentTypes->random()->uuid,
                'square_created_at' => $faker->dateTimeBetween('+1 days', '+2 days')
            ]);
            $itemRandomUuids = $items->random(2)->pluck('uuid')->toArray();
            $payment->items()->sync($itemRandomUuids);
        }
    }
}
