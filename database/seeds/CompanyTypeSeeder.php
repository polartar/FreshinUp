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
                'name' => 'Contractor'
            ],
            [
                'name' => 'Contractor',
                //'key_id' => 'contractor'
            ]
        );
    }
}
