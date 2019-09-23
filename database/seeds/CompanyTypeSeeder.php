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
        CompanyType::updateOrCreate(
            [
                'name' => 'Supplier'
            ],
            [
                'name' => 'Supplier',
                'key_id' => 'supplier'
            ]
        );
        CompanyType::updateOrCreate(
            [
                'name' => 'Host'
            ],
            [
                'name' => 'Host',
                'key_id' => 'host'
            ]
        );
    }
}
