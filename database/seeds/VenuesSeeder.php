<?php

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
use App\Models\Foodfleet\VenueStatus;
use Illuminate\Database\Seeder;
use App\User;


class VenuesSeeder extends Seeder
{
    /**
     * Seed the application's database.*
     * @return void
     */
    public function run()
    {
        $statuses = VenueStatus::get();
        $users = User::get();
        for ($i = 0; $i < 20; $i++) {
            $venue = factory(Venue::class)->create([
                'status_id' => $statuses->random()->id,
                'owner_uuid' => $users->random()->uuid
            ]);
            factory(Location::class, mt_rand(1, 10))->create([
                'venue_uuid' => $venue->uuid
            ]);
        }
    }
}
