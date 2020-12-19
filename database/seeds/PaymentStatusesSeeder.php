<?php

use Illuminate\Database\Seeder;
use App\Models\Foodfleet\Square\PaymentStatus;
use App\Enums\PaymentStatus as PaymentStatusEnum;

class PaymentStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = PaymentStatusEnum::toKeyedSelectArray();

        foreach ($statuses as $id => $name) {
            PaymentStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
