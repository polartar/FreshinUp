<?php

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Database\Seeder;

class EventHistorySeeder extends Seeder
{

    public function run()
    {
        EventHistory::truncate();
        $events = Event::get();
        foreach ($events as $event) {
            for ($i = 1; $i <= $event->status_id; $i++) {
                factory(EventHistory::class)->create([
                    'status_id' => $i,
                    'event_uuid' => $event->uuid
                ]);
            }
        }

    }
}
