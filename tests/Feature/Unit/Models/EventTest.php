<?php

namespace Tests\Feature\Unit\Models\Event;

use App\Enums\EventStatus as EventStatusEnum;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $event = factory(Event::class)->create();

        $this->assertDatabaseHas('events', [
            "uuid" => $event->uuid,
            "name" => $event->name,
            "type_id" => $event->type_id,
            "status_id" => $event->status_id,
            "location_uuid" => $event->location_uuid,
            "start_at" => $event->start_at,
            "end_at" => $event->end_at,
            "staff_notes" => $event->staff_notes,
            "member_notes" => $event->member_notes,
            "customer_notes" => $event->customer_notes,
            "host_uuid" => $event->host_uuid,
            "host_status" => $event->host_status,
            "manager_uuid" => $event->manager_uuid,
            "budget" => $event->budget,
            "attendees" => $event->attendees,
            "commission_rate" => $event->commission_rate,
            "commission_type" => $event->commission_type,
            "created_at" => $event->created_at,
            "updated_at" => $event->updated_at,
            "venue_uuid" => $event->venue_uuid
        ]);

        // table relations
        $this->assertEquals($event->type_id, $event->type->id);
        $this->assertEquals($event->status_id, $event->status->id);
        $this->assertEquals($event->location_uuid, $event->location->uuid);
        $this->assertEquals($event->host_uuid, $event->host->uuid);
        $this->assertEquals($event->manager_uuid, $event->manager->uuid);
        $this->assertEquals($event->venue_uuid, $event->venue->uuid);

        // external table relations
        // TODO: transactions
        // TODO: eventTags
        // TODO: documents
        // TODO: menuItems
        // TODO: messages
        // TODO: schedule
        // TODO: histories
        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        $this->assertEquals($transaction->uuid, $event->transactions->first()->uuid);
    }

    public function testObserverWhenEventCreated()
    {
        $event = factory(Event::class)->make([
            'status_id' => EventStatusEnum::DRAFT
        ]);
        $this->assertEquals(0, EventHistory::where([
            'event_uuid' => $event->uuid
        ])->count());

        $event->save();
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => EventStatusEnum::DRAFT
        ])->whereNotNull('date')->count());
    }

    public function testObserverWhenEventUpdatedWithSameStatus()
    {
        $event = factory(Event::class)->create();
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => $event->status_id
        ])->count());

        $event->update(array_merge(factory(Event::class)->make()->toArray(), [
            'status_id' => $event->status_id
        ]));
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
        ])->count());
    }

    public function testObserverWhenEventUpdatedWithDifferentStatus()
    {
        $event = factory(Event::class)->create([
            'status_id' => EventStatusEnum::FF_INITIAL_REVIEW
        ]);
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => $event->status_id
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ]);
        $this->assertEquals(2, EventHistory::where([
            'event_uuid' => $event->uuid,
        ])->count());
        $history = EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ])->first();
        $this->assertNotNull($history->date);
    }

    public function testObserverWhenEventDeleted()
    {
        $event = factory(Event::class)->create();
        for ($i = 1; $i <= 9; $i++) {
            factory(EventHistory::class)->create([
                'event_uuid' => $event->uuid,
                'status_id' => $i
            ]);
        }
        $this->assertEquals(10, EventHistory::where([
            'event_uuid' => $event->uuid
        ])->count());

        $event->delete();
        $this->assertEquals(0, EventHistory::where('event_uuid', $event->uuid)->count());
    }
}
