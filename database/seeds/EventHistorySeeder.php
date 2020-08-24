<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Database\Seeder;

class EventHistorySeeder extends Seeder
{

    public function run()
    {
        $events = Event::get();
        $status = EventStatus::get();

        for ($i = 0; $i < 10; $i++) {
            factory(EventHistory::class)->create([
                'status_id' => $status->random()->id,
                'event_uuid' => $events->random()->uuid
            ]);
        }
    }
}
