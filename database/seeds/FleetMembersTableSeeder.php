<?php

use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Database\Seeder;

class FleetMembersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $contractors = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'contractor');
        })->get();
        for ($i = 0; $i < 50; $i++) {
            factory(\App\Models\Foodfleet\FleetMember::class)->create([
                'contractor_uuid' => $contractors->random()->uuid
            ]);
        }
    }
}
