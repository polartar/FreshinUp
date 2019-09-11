<?php

use App\Models\Foodfleet\Square\PaymentType;
use Illuminate\Database\Seeder;

class PaymentTypesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $paymentTypes = [
            'VISA',
            'AMEX',
            'MASTERCARD',
            'CASH'
        ];

        foreach ($paymentTypes as $paymentType) {
            PaymentType::firstOrCreate(['name' => $paymentType]);
        }
    }
}
