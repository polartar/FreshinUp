<?php

namespace App\Observers;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Enums\DocumentStatus as  DocumentStatusEnum;
use App\Enums\EventStatus as EventStatusEnum;
use App\Models\Foodfleet\Document;

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
            if ($event->status_id == EventStatusEnum::FLEET_MEMBER_CONTRACTS) {
                Document::updateOrCreate([
                    'assigned_type' => Event::class,
                    'assigned_uuid' => $event->uuid,
                    'status_id' => DocumentStatusEnum::PENDING,
                    'title' => 'Fleet Member Contracts',
                    'description' => 'Fleet Member Contracts',
                ]);
            }
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
}
