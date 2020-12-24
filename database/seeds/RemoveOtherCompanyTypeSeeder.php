<?php

use Illuminate\Database\Seeder;
use FreshinUp\FreshBusForms\Models\Company\CompanyType;

class RemoveOtherCompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        CompanyType::truncate();

        CompanyType::firstOrCreate([
            'id' => 1,
            'name' => 'Supplier'
        ]);
        
        CompanyType::firstOrCreate([
            'id' => 2,
            'name' => 'Customer'
        ]);
    }
}
