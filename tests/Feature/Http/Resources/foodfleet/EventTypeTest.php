<?php

namespace Tests\Feature\Http\Resources;


use App\Http\Resources\Foodfleet\EventType as EventTypeResource;
use App\Models\Foodfleet\EventType as EventTypeModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventStatusTest extends TestCase {



    public function testResource ($typeId) {
        $eventType = factory(EventTypeModel::class)->make([
            'id' => $typeId
        ]);
        $resource = new EventTypeResource($eventType);
        $expected = [
            'id' => $eventType->id,
            'name' => $eventType->name,
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
