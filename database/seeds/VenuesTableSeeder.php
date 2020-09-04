<?php

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
use Illuminate\Database\Seeder;

class VenuesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.*
     * @return void
     */
    public function run()
    {
        $venues = factory(Venue::class, 10)->create();
        foreach ($venues as $venue) {
            factory(Location::class, mt_rand(1, 10))->create([
                'venue_uuid' => $venue->uuid
            ]);
        }
    }
}
