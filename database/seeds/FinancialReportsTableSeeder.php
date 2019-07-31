<?php

use App\Models\Foodfleet\FinancialModifier as Modifier;
use App\Models\Foodfleet\FinancialReport;
use App\User;
use Illuminate\Database\Seeder;

class FinancialReportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modifierPaymentType = Modifier::where('name', 'payment_type_uuid')->first();
        $modifierDevice = Modifier::where('name', 'device_uuid')->first();
        $modifierMinPrice = Modifier::where('name', 'min_price')->first();
        $modifierMaxPrice = Modifier::where('name', 'max_price')->first();
        $demoAdmin = User::where('email', 'demoAdmin@example.com')->first();

        $saved = [
            [
                'name' => 'My Custom Report #1',
                'filters' => json_encode([
                    'transaction_id' => 1,
                    'category' => 'Test',
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id
            ],
            [
                'name' => 'My Custom Report #2',
                'filters' => json_encode([
                    'customer_id' => 2,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id,
            ],
            [
                'name' => 'My Custom Report #3',
                'filters' => json_encode([
                    'customer_name' => 'John',
                    'reference_id' => 2
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierMaxPrice->id
            ],
            [
                'name' => 'My Custom Report #4',
                'filters' => json_encode([
                    'transaction_id' => 4,
                    'customer_name' => 'Rover'
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierPaymentType->id
            ]
        ];

        foreach ($saved as $item) {
            $report = FinancialReport::firstOrCreate([
                'name' => $item['name'],
            ], $item);
            $report->update($item);
        }
    }
}
