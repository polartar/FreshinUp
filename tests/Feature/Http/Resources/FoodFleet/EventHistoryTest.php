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
        $eventHistory = factory(EventHistoryModel::class)->make();
        $resource = new EventHistoryResource($eventHistory);
        $status = EventStatusModel::find($eventHistory->status_id);
        $event = Event::whereUuid($eventHistory->event_uuid)->first();
        $request = app()->make(Request::class);
        $expected = [
            'id' => $eventHistory->id,
            'status_id' => $eventHistory->status_id,
            'status' => [
                'id' => $status->id,
                'name' => $status->name,
                'color' => EventStatusResource::getColorFor($status->id)
            ],
            'event_uuid' => $event->uuid,
            'description' => $eventHistory->description,
            'date' => $eventHistory->date->format('Y-m-d H:i:s'),
            'completed' => $eventHistory->completed
        ];
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
