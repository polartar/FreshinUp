<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\FinancialModifier as Modifier;
use App\Models\Foodfleet\FinancialReport;
use App\Models\Foodfleet\Square\Customer;
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
        $modifierCustomerId = Modifier::where('name', 'customer_uuid')->first();
        $demoAdmin = User::where('email', 'demoAdmin@example.com')->first();

        $events = Event::get();
        $items = \App\Models\Foodfleet\Square\Item::get();
        $customers = Customer::get();

        $saved = [
            [
                'name' => 'My Custom Report #1',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'item_uuid' => $items->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id
            ],
            [
                'name' => 'My Custom Report #2',
                'filters' => json_encode([
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id,
            ],
            [
                'name' => 'My Custom Report #3',
                'filters' => json_encode([
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierMaxPrice->id
            ],
            [
                'name' => 'My Custom Report #4',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierPaymentType->id
            ],
            [
                'name' => 'My Custom Report #5',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierCustomerId->id
            ],
            [
                'name' => 'My Custom Report #6',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'item_uuid' => $items->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id
            ],
            [
                'name' => 'My Custom Report #7',
                'filters' => json_encode([
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPaymentType->id,
                'modifier_2_id' => $modifierDevice->id,
            ],
            [
                'name' => 'My Custom Report #8',
                'filters' => json_encode([
                    'customer_uuid' => $customers->random()->uuid,
                    'event_uuid' => $events->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierMaxPrice->id
            ],
            [
                'name' => 'My Custom Report #9',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierMinPrice->id,
                'modifier_2_id' => $modifierPaymentType->id
            ],
            [
                'name' => 'My Custom Report #10',
                'filters' => json_encode([
                    'event_uuid' => $events->random()->uuid,
                    'customer_uuid' => $customers->random()->uuid,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierCustomerId->id
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
