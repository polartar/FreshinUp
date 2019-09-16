<?php

use App\Models\Foodfleet\Square\Staff;
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
        $staffs = Staff::get();
        $contractors = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'contractor');
        })->get();
        for ($i = 0; $i < 50; $i++) {
            $fleetMember = factory(\App\Models\Foodfleet\FleetMember::class)->create([
                'contractor_uuid' => $contractors->random()->uuid
            ]);
            $staffRandomUuids = $staffs->random(2)->pluck('uuid')->toArray();
            $fleetMember->staffs()->sync($staffRandomUuids);
        }
    }
}
