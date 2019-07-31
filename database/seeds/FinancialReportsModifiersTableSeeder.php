<?php

use App\Models\Foodfleet\FinancialModifier as Modifier;
use Illuminate\Database\Seeder;

class FinancialReportsModifiersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modifiers = [
            [
                'name' => 'prepared_after',
                'resource_name' => 'prepared_after',
                'label' => 'Prepared After',
                'placeholder' => 'Prepared After'
            ], [
                'name' => 'prepared_before',
                'resource_name' => 'prepared_before',
                'label' => 'Prepared Before',
                'placeholder' => 'Prepared Before'
            ], [
                'name' => 'appraisal_status_uuid',
                'resource_name' => 'appraisal_statuses',
                'label' => 'Appraisal Statuses',
                'placeholder' => 'All appraisal statuses'
            ], [
                'name' => 'sell_status_uuid',
                'resource_name' => 'sell_statuses',
                'label' => 'Sell Statuses',
                'placeholder' => 'All sell statuses'
            ], [
                'name' => 'owning_location_id',
                'resource_name' => 'branches',
                'label' => 'Owning Location',
                'placeholder' => 'All owning locations'
            ], [
                'name' => 'current_location_id',
                'resource_name' => 'branches',
                'label' => 'Current Location',
                'placeholder' => 'All current locations'
            ], [
                'name' => 'scheduled_after',
                'resource_name' => 'scheduled_after',
                'label' => 'Scheduled After',
                'placeholder' => 'Scheduled After'
            ], [
                'name' => 'scheduled_before',
                'resource_name' => 'scheduled_before',
                'label' => 'Scheduled Before',
                'placeholder' => 'Scheduled Before'
            ], [
                'name' => 'acquired_after',
                'resource_name' => 'acquired_after',
                'label' => 'Acquired After',
                'placeholder' => 'Acquired After'
            ], [
                'name' => 'acquired_before',
                'resource_name' => 'acquired_before',
                'label' => 'Acquired Before',
                'placeholder' => 'Acquired Before'
            ], [
                'name' => 'delivery_after',
                'resource_name' => 'delivery_after',
                'label' => 'Delivery After',
                'placeholder' => 'Delivery After'
            ], [
                'name' => 'delivery_before',
                'resource_name' => 'delivery_before',
                'label' => 'Delivery Before',
                'placeholder' => 'Delivery Before'
            ],
        ];

        foreach ($modifiers as $item) {
            $modifier = Modifier::firstOrCreate([
                'name' => $item['name']
            ], $item);
            $modifier->update($item);
        }
    }
}
