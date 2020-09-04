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
        $eventHistory = factory(EventHistoryModel::class)->create();
        $resource = new EventHistoryResource($eventHistory);
        $status = $eventHistory->status;
        $event = $eventHistory->event;
        $request = app()->make(Request::class);
        $expected = [
            'id' => $eventHistory->id,
            'status_id' => $eventHistory->status_id,
            'event_uuid' => $event->uuid,
            'description' => $eventHistory->description,
            'date' => $eventHistory->date->format('Y-m-d H:i:s'),
            'completed' => $eventHistory->completed
        ];
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
        $this->assertArrayHasKey('status', $result);
        $this->assertArraySubset([
            'id' => $status->id,
            'name' => $status->name,
            'color' => EventStatusResource::getColorFor($status->id)
        ], $result['status']->toArray($request));
    }
}
