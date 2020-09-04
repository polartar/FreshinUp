<?php

use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventType;
use App\Models\Foodfleet\Venue;
use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\EventStatus;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Location;
use FreshinUp\FreshBusForms\Models\Company\Company;
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
        $stores = Store::get();
        $eventTags = EventTag::get();
        $statuses = EventStatus::get();
        $locations = Location::get();
        $venues = Venue::get();
        $users = User::where(["type" => 1])->get();
        $hosts = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'host');
        })->get();
        $eventType = EventType::get();

        for ($i = 0; $i < 30; $i++) {
            $status_id = $statuses->random()->id;
            $event = factory(Event::class)->create([
                'manager_uuid' => $users->random()->uuid,
                'status_id' => $status_id,
                'location_uuid' => $locations->random()->uuid,
                'host_uuid' => $hosts->random()->uuid,
                'type_id' => $eventType->random()->id,
                'venue_uuid' => $venue->uuid
            ]);
            for ($j = 1; $j <= $status_id; $j++) {
                factory(EventHistory::class)->create([
                    'event_uuid' => $event->uuid,
                    'status_id' => $j,
                    'completed' => true
                ]);
            }
            $eventTagRandomUuids = $eventTags->random(2)->pluck('uuid')->toArray();
            $event->eventTags()->sync($eventTagRandomUuids);
            $storeUuids = $stores->random(2)->pluck('uuid')->toArray();
            $event->stores()->sync($storeUuids);
        }
    }
}
