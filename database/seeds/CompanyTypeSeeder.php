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
        CompanyType::truncate();
        CompanyType::updateOrCreate(
            [
                'key_id' => 'supplier'
            ],
            [
                'name' => 'Supplier',
                'key_id' => 'supplier'
            ]
        );
        CompanyType::updateOrCreate(
            [
                'key_id' => 'host'
            ],
            [
                'name' => 'Customer',
                'key_id' => 'host'
            ]
        );
       
    }
}
