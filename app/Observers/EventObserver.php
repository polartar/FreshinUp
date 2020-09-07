<?php

namespace App\Observers;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;

class EventObserver
{
    /**
     * Handle the event "created" event.
     *
     * @param  Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        EventHistory::create([
            'event_uuid' => $event->uuid,
            'status_id' => $event->status_id,
            'date' => now()
        ]);
    }

    /**
     * Handle the event "updated" event.
     *
     * @param  Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        if ($event->isDirty('status_id')) {
            EventHistory::create([
                'event_uuid' => $event->uuid,
                'status_id' => $event->status_id,
                'date' => now()
            ]);
        }
    }

    /**
     * Handle the event "deleted" event.
     *
     * @param  Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        EventHistory::where('event_uuid', $event->uuid)->delete();
    }

    /**
     * Handle the event "restored" event.
     *
     * @param  Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the event "force deleted" event.
     *
     * @param  Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
