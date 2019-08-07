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
                'name' => 'date_after',
                'resource_name' => 'date_after',
                'label' => 'Min date',
                'placeholder' => 'Min date',
                'type' => 'date'
            ],
            [
                'name' => 'date_before',
                'resource_name' => 'date_before',
                'label' => 'Max date',
                'placeholder' => 'Max date',
                'type' => 'date'
            ],
            [
                'name' => 'event_uuid',
                'resource_name' => 'events',
                'label' => 'Event name',
                'placeholder' => 'Event name',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'company_uuid',
                'resource_name' => 'companies',
                'label' => 'Company name',
                'placeholder' => 'Company name',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'fleet_member_uuid',
                'resource_name' => 'fleet_members',
                'label' => 'Fleet member name',
                'placeholder' => 'Fleet member name',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'contractor_uuid',
                'resource_name' => 'contractors',
                'label' => 'Customer',
                'placeholder' => 'Customer',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'event_tag_uuid',
                'resource_name' => 'event_tags',
                'label' => 'Event tags',
                'placeholder' => 'Event tags',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'location_uuid',
                'resource_name' => 'location',
                'label' => 'Location',
                'placeholder' => 'Location',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'customers',
                'label' => 'Customer Name',
                'placeholder' => 'Customer Name',
                'type' => 'autocomplete',
                'filter' => 'term'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'customers',
                'label' => 'Customer ID',
                'placeholder' => 'Customer ID',
                'type' => 'autocomplete',
                'filter' => 'square_id'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'customers',
                'label' => 'Reference ID',
                'placeholder' => 'Reference ID',
                'type' => 'autocomplete',
                'filter' => 'reference_id'
            ],
            [
                'name' => 'staff_uuid',
                'resource_name' => 'staffs',
                'label' => 'Staff Name',
                'placeholder' => 'Staff Name',
                'type' => 'autocomplete',
                'filter' => 'term'
            ],
            [
                'name' => 'staff_uuid',
                'resource_name' => 'staffs',
                'label' => 'Staff ID',
                'placeholder' => 'Staff ID',
                'type' => 'autocomplete',
                'filter' => 'square_id'
            ],
            [
                'name' => 'device_uuid',
                'resource_name' => 'devices',
                'label' => 'Device',
                'placeholder' => 'Device',
                'type' => 'select',
            ],
            [
                'name' => 'category_uuid',
                'resource_name' => 'categories',
                'label' => 'Category',
                'placeholder' => 'Category',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'item_uuid',
                'resource_name' => 'items',
                'label' => 'Item',
                'placeholder' => 'Item',
                'type' => 'autocomplete',
                'filter' => 'name'
            ],
            [
                'name' => 'min_price',
                'resource_name' => null,
                'label' => 'Min price',
                'placeholder' => 'Min price',
                'type' => 'text'
            ],
            [
                'name' => 'max_price',
                'resource_name' => null,
                'label' => 'Max price',
                'placeholder' => 'Max price',
                'type' => 'text'
            ],
            [
                'name' => 'payment_type_uuid',
                'resource_name' => 'payment_types',
                'label' => 'Payment type',
                'placeholder' => 'Payment type',
                'type' => 'select'
            ],
            [
                'name' => 'transaction_uuid',
                'resource_name' => 'transactions',
                'label' => 'Transaction ID',
                'placeholder' => 'Transaction ID',
                'type' => 'autocomplete',
                'filter' => 'square_id'
            ],
            [
                'name' => 'payment_uuid',
                'resource_name' => 'payments',
                'label' => 'Payment ID',
                'placeholder' => 'Payment ID',
                'type' => 'autocomplete',
                'filter' => 'square_id'
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
