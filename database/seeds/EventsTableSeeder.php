<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
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
        $locations = Location::get();
        $hosts = Company::whereHas('company_types', function ($query) {
            $query->where('key_id', 'host');
        })->get();

        for ($i = 0; $i < 50; $i++) {
            $event = factory(Event::class)->create([
                'location_uuid' => $locations->random()->uuid,
                'host_uuid' => $hosts->random()->uuid
            ]);
            $eventTagRandomUuids = $eventTags->random(2)->pluck('uuid')->toArray();
            $event->eventTags()->sync($eventTagRandomUuids);
            $storeUuids = $stores->random(2)->pluck('uuid')->toArray();
            $event->stores()->sync($storeUuids);
        }
    }
}
