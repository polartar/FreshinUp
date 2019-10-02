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
                'resource_name' => 'foodfleet/events',
                'label' => 'Event name',
                'placeholder' => 'Event name',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'host_uuid',
                'resource_name' => 'companies?filter[key_id]=host',
                'label' => 'Customer company name',
                'placeholder' => 'Customer company name',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'store_uuid',
                'resource_name' => 'foodfleet/stores',
                'label' => 'Fleet member name',
                'placeholder' => 'Fleet member name',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'supplier_uuid',
                'resource_name' => 'companies?filter[key_id]=supplier',
                'label' => 'Supplier name',
                'placeholder' => 'Supplier name',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'event_tag_uuid',
                'resource_name' => 'foodfleet/event_tags',
                'label' => 'Event tags',
                'placeholder' => 'Event tags',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'location_uuid',
                'resource_name' => 'foodfleet/locations',
                'label' => 'Location',
                'placeholder' => 'Location',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'foodfleet/customers',
                'label' => 'Customer Name',
                'placeholder' => 'Customer Name',
                'type' => 'autocomplete',
                'filter' => 'term',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'foodfleet/customers',
                'label' => 'Customer ID',
                'placeholder' => 'Customer ID',
                'type' => 'autocomplete',
                'filter' => 'filter[square_id]',
                'value_param' => 'uuid',
                'text_param' => 'square_id'
            ],
            [
                'name' => 'customer_uuid',
                'resource_name' => 'foodfleet/customers',
                'label' => 'Reference ID',
                'placeholder' => 'Reference ID',
                'type' => 'autocomplete',
                'filter' => 'filter[reference_id]',
                'value_param' => 'uuid',
                'text_param' => 'reference_id'
            ],
            [
                'name' => 'staff_uuid',
                'resource_name' => 'foodfleet/staffs',
                'label' => 'Staff Name',
                'placeholder' => 'Staff Name',
                'type' => 'autocomplete',
                'filter' => 'term',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'staff_uuid',
                'resource_name' => 'foodfleet/staffs',
                'label' => 'Staff ID',
                'placeholder' => 'Staff ID',
                'type' => 'autocomplete',
                'filter' => 'filter[square_id]',
                'value_param' => 'uuid',
                'text_param' => 'square_id'
            ],
            [
                'name' => 'device_uuid',
                'resource_name' => 'devices',
                'label' => 'Device',
                'placeholder' => 'Device',
                'type' => 'select'
            ],
            [
                'name' => 'category_uuid',
                'resource_name' => 'foodfleet/categories',
                'label' => 'Category',
                'placeholder' => 'Category',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
            ],
            [
                'name' => 'item_uuid',
                'resource_name' => 'foodfleet/items',
                'label' => 'Item',
                'placeholder' => 'Item',
                'type' => 'autocomplete',
                'filter' => 'filter[name]',
                'value_param' => 'uuid',
                'text_param' => 'name'
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
                'resource_name' => 'foodfleet/transactions',
                'label' => 'Transaction ID',
                'placeholder' => 'Transaction ID',
                'type' => 'autocomplete',
                'filter' => 'filter[square_id]',
                'value_param' => 'uuid',
                'text_param' => 'square_id'
            ],
            [
                'name' => 'payment_uuid',
                'resource_name' => 'foodfleet/payments',
                'label' => 'Payment ID',
                'placeholder' => 'Payment ID',
                'type' => 'autocomplete',
                'filter' => 'filter[square_id]',
                'value_param' => 'uuid',
                'text_param' => 'square_id'
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
