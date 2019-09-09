<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\FleetMember;
use App\Models\Foodfleet\Location;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $fleetMembers = FleetMember::get();
        $eventTags = EventTag::get();
        $locations = Location::get();

        for ($i = 0; $i < 50; $i++) {
            $event = factory(Event::class)->create([
                'fleet_member_uuid' => $fleetMembers->random()->uuid,
                'location_uuid' => $locations->random()->uuid
            ]);
            $eventTagRandomUuids = $eventTags->random(2)->pluck('uuid')->toArray();
            $event->eventTags()->sync($eventTagRandomUuids);
        }
    }
}
