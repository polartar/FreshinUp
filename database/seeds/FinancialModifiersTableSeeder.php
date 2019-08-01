<?php

use App\Models\Foodfleet\FinancialModifier as Modifier;
use Illuminate\Database\Seeder;

class FinancialModifiersTableSeeder extends Seeder
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
                'name' => 'payment_type_uuid',
                'resource_name' => 'payment_types',
                'label' => 'Payment type',
                'placeholder' => 'Payment type'
            ], [
                'name' => 'device_uuid',
                'resource_name' => 'devices',
                'label' => 'Device',
                'placeholder' => 'Device'
            ], [
                'name' => 'min_price',
                'resource_name' => null,
                'label' => 'Min price',
                'placeholder' => 'Min price'
            ], [
                'name' => 'max_price',
                'resource_name' => null,
                'label' => 'Max price',
                'placeholder' => 'Max price'
            ]
        ];

        foreach ($modifiers as $item) {
            $modifier = Modifier::firstOrCreate([
                'name' => $item['name']
            ], $item);
            $modifier->update($item);
        }
    }
}
