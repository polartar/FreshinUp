<?php

namespace App\Observers;

use App\Enums\DocumentStatus;
use App\Enums\DocumentTemplateStatus;
use App\Enums\EventStatus;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Document\Template\Template;
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

            if ($event->status_id == EventStatus::CUSTOMER_AGREEMENT) {
                $template = Template::getClientAgreement();
                Document::updateOrCreate([
                    'assigned_uuid' => $event->uuid,
                    'assigned_type' => Event::class,
                    'status_id' => DocumentStatus::PENDING,
                    'title' => $event->name . ' - Customer Agreement',
                    'template_uuid' => $template->uuid
                ], [
                    'description' => $event->name . ' - Customer Agreement',
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
