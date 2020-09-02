<?php

namespace Tests\Feature\Unit\Models\Event;

use App\Models\Foodfleet\Event;
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
}
