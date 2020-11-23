<?php

namespace App\Observers;

use App\Enums\DocumentStatus;
use App\Enums\DocumentType;
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
                    'template_uuid' => $template->uuid,
                    'type_id' => DocumentType::FROM_TEMPLATE
                ], [
                    'description' => $event->name . ' - Customer Agreement',
                ]);
            } elseif ($event->status_id == EventStatus::FLEET_MEMBER_CONTRACTS) {
                $template = Template::getFleetMemberEventContract();
                Document::updateOrCreate([
                    // TODO: see https://github.com/FreshinUp/foodfleet/issues/545
                    // seems like we need to create document for all fleet member not just for the event
                    // 'event_store_uuid' => $store->uuid,
                    // Question is who are the approved fleet member ?
                    // this will most likely be opened again
                    'assigned_uuid' => $event->uuid,
                    'type_id' => DocumentType::FROM_TEMPLATE,
                    'assigned_type' => Event::class,
                    'status_id' => DocumentStatus::PENDING,
                    'title' => $event->name . ' - Fleet member contract',
                    'template_uuid' => $template->uuid
                ], [
                    'description' => $event->name . ' - Fleet member contract',
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
