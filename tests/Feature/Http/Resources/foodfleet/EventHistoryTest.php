<?php

namespace Tests\Feature\Http\Resources\foodfleet;


use App\Http\Resources\Foodfleet\EventHistory as EventHistoryResource;
use App\Models\Foodfleet\EventHistory as EventHistoryModel;
use App\Models\Foodfleet\EventStatus as EventStatusModel;
use App\Models\Foodfleet\Event as EventModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventHistoryTest extends TestCase
{
    public function testResource () {
        $eventType = factory(EventStatusModel::class)->make();
        $event = factory(EventModel::class)->make();
        $eventHistory = factory(EventHistoryModel::class)->make();
        $resource = new EventHistoryResource($eventType);
        $expected = [
            'id' => $eventType->id,
            'status_id' => $eventType->id,
            'event_uuid' => $event->uuid,
            'description' => $eventHistory->description,
            'date' => $eventHistory->date,
            'completed' => $eventHistory->completed
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
