<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Transaction;
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
        $devices = Device::get();
        $paymentTypes = PaymentType::get();
        $transactions = Transaction::get();

        for ($i = 0; $i < 500; $i++) {
            factory(Payment::class)->create([
                'device_uuid' => $devices->random()->uuid,
                'payment_type_uuid' => $paymentTypes->random()->uuid,
                'transaction_uuid' => $transactions->random()->uuid,
                'square_created_at' => $faker->dateTimeBetween('-190 days', 'now')
            ]);
        }
    }
}
