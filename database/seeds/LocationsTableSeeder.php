<?php

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
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
        $venues = Venue::get();
        for ($i = 0; $i < 50; $i++) {
            factory(Location::class)->create([
                'venue_uuid' => $venues->random()->uuid
            ]);
        }
    }
}
