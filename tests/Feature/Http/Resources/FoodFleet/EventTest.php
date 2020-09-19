<?php

namespace Tests\Feature\Http\Resources;


use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Models\Foodfleet\Event as EventModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventTest extends TestCase
{
    public function testResource ()
    {
        $event = factory(EventModel::class)->create();
        $resource = new EventResource($event);
        $request = app()->make(Request::class);
        $expected = [
            "id" => $event->id,
            "uuid" => $event->uuid,
            "name" => $event->name,
            "status_id" => $event->status_id,
            'host_status' => $event->host_status,
            "start_at" => $event->start_at,
            "end_at" => $event->end_at,
            "staff_notes" => $event->staff_notes,
            "member_notes" => $event->member_notes,
            "customer_notes" => $event->customer_notes,
            'budget' => $event->budget,
            'attendees' => $event->attendees,
            'commission_rate' => $event->commission_rate,
            'commission_type' => $event->commission_type,
            'type_id' => $event->type_id,
            'location_uuid' => $event->location_uuid,
            'host_uuid' => $event->host_uuid,
            'manager_uuid' => $event->manager_uuid,
            'venue_uuid' => $event->venue_uuid,
        ];
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
