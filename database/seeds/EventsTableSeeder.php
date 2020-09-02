<?php

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
        Event::truncate();
        $stores = Store::get();
        $eventTags = EventTag::get();
        $status = EventStatus::get();
        $venues = Venue::get();
        $users = User::where(["type" => 1])->get();
        $hosts = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'host');
        })->get();
        $eventType = EventType::get();

        for ($i = 0; $i < 50; $i++) {
            $venue = $venues->random();
            $event = factory(Event::class)->create([
                'manager_uuid' => $users->random()->uuid,
                'status_id' => $status->random()->id,
                'location_uuid' => $venue->locations->random()->uuid,
                'host_uuid' => $hosts->random()->uuid,
                'type_id' => $eventType->random()->id,
                'venue_uuid' => $venue->uuid
            ]);
            $eventTagRandomUuids = $eventTags->random(2)->pluck('uuid')->toArray();
            $event->eventTags()->sync($eventTagRandomUuids);
            $storeUuids = $stores->random(2)->pluck('uuid')->toArray();
            $event->stores()->sync($storeUuids);
        }
    }
}
