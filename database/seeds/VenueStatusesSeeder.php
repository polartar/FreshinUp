<?php

use App\Models\Foodfleet\VenueStatus;
use Illuminate\Database\Seeder;
use App\Enums\VenueStatus as VenueStatusEnums;

class VenueStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = VenueStatusEnums::toKeyedSelectArray();
        foreach ($statuses as $id => $name) {
            VenueStatus::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
