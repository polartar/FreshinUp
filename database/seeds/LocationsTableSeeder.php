<?php

use App\Models\Foodfleet\Square\Staff;
use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $staffs = Staff::get();
        $locations = factory(\App\Models\Foodfleet\Location::class, 50)->create();
        foreach ($locations as $location) {
            $staffRandomUuids = $staffs->random(2)->pluck('uuid')->toArray();
            $location->staffs()->sync($staffRandomUuids);
        }
    }
}
