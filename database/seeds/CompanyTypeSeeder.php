<?php

use FreshinUp\FreshBusForms\Models\Company\CompanyType;
use Illuminate\Database\Seeder;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $supplier = CompanyType::updateOrCreate(
            [
                'key_id' => 'supplier'
            ],
            [
                'name' => 'Supplier',
                'key_id' => 'supplier'
            ]
        );
        $customer = CompanyType::updateOrCreate(
            [
                'key_id' => 'customer'
            ],
            [
                'name' => 'Customer',
                'key_id' => 'customer'
            ]
        );
        CompanyType::whereNotIn('id', [$supplier->id, $customer->id])->delete();
    }
}
