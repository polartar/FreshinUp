<?php

namespace Tests\Feature\Http\Resources;


use App\Http\Resources\Foodfleet\EventHistory as EventHistoryResource;
use App\Http\Resources\Foodfleet\EventStatus as EventStatusResource;
use App\Models\Foodfleet\EventHistory as EventHistoryModel;
use App\Models\Foodfleet\EventStatus as EventStatusModel;
use App\Models\Foodfleet\Event;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventHistoryTest extends TestCase
{
    public function testResource () {
        $event = factory(Event::class)->make();
        $eventHistory = factory(EventHistoryModel::class)->make();
        $resource = new EventHistoryResource($event);
        $request = app()->make(Request::class);
        $status = EventStatusModel::find($eventHistory->status_id);
        $expected = [
            'id' => $eventHistory->id,
            'status_id' => $eventHistory->status_id,
            'status' => (new EventStatusResource($status))->toArray($request),
            'event_uuid' => $event->uuid,
            'description' => $eventHistory->description,
            'date' => $eventHistory->date,
            'completed' => $eventHistory->completed
        ];
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
