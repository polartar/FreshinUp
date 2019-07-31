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
        $modifierPreparedAfter = Modifier::where('name', 'prepared_after')->first();
        $modifierPreparedBefore = Modifier::where('name', 'prepared_before')->first();
        $modifierAppraisalStatus = Modifier::where('name', 'appraisal_status_uuid')->first();
        $modifierSellStatus = Modifier::where('name', 'sell_status_uuid')->first();
        $modifierOwningLocation = Modifier::where('name', 'owning_location_id')->first();
        $demoAdmin = User::where('email', 'demoAdmin@example.com')->first();

        $saved = [
            [
                'name' => 'My Custom Report #1',
                'filters' => json_encode([
                    'prepared_by_uuid' => User::get()->random()->uuid,
                    'prepared_after' => '2019-01-01',
                    'prepared_before' => '2019-07-01',
                    'appraisal_status_uuid' => \App\Models\CSM\AppraisalStatus::get()->random()->uuid,
                    'taken_in_by_uuid' => User::get()->random()->uuid,
                    'customer_uuid' => \App\Models\CSM\Company::get()->random()->uuid,
                    'sell_status_uuid' => \App\Models\CSM\SellStatus::get()->random()->uuid,
                    'vehicle_uuid' => \App\Models\CSM\Vehicle::get()->random()->uuid,
                    'authorized_by_uuid' => User::get()->random()->uuid,
                    'owning_location_id' => \App\Models\CSM\Branch::get()->random()->id,
                    'current_location_id' => \App\Models\CSM\Branch::get()->random()->id,
                    'matrix_value_range' => [30000, 50000],
                    'appraised_value_range' => [null, 100000],
                    'minimum_sell_range' => [5000, null],
                    'scheduled_after' => '1972-01-01',
                    'acquired_before' => '2020-01-01',
                    'delivery_after' => '1972-01-01',
                    'delivery_before' => '2020-01-01',
                    'vin' => \App\Models\CSM\Vehicle::get()->random()->vin,
                    'stock_number' => \App\Models\CSM\Vehicle::get()->random()->stock_number
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPreparedAfter->id,
                'modifier_2_id' => $modifierPreparedBefore->id
            ],
            [
                'name' => 'My Custom Report #2',
                'type' => ReportableType::SAVED,
                'filters' => json_encode([
                    'current_location_id' => 2,
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierPreparedAfter->id,
                'modifier_2_id' => $modifierPreparedBefore->id,
            ],
            [
                'name' => 'My Custom Report #3',
                'type' => ReportableType::SAVED,
                'filters' => json_encode([
                    'current_location_id' => 2,
                    'prepared_after' => '2019-01-01',
                    'prepared_before' => '2019-07-01',
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierAppraisalStatus->id,
                'modifier_2_id' => $modifierSellStatus->id
            ],
            [
                'name' => 'My Custom Report #4',
                'type' => ReportableType::SAVED,
                'filters' => json_encode([
                    'current_location_id' => 4,
                    'prepared_after' => '2019-01-01',
                    'prepared_before' => '2019-07-01',
                ]),
                'user_id' => $demoAdmin->id,
                'modifier_1_id' => $modifierAppraisalStatus->id,
                'modifier_2_id' => $modifierOwningLocation->id
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
