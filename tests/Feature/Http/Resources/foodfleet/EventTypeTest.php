<?php

namespace Tests\Feature\Http\Resources;


use App\Http\Resources\Foodfleet\EventType as EventTypeResource;
use App\Models\Foodfleet\EventType as EventTypeModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventTypeTest extends TestCase {

    public function testResource () {
        $eventType = factory(EventTypeModel::class)->make();
        $resource = new EventTypeResource($eventType);
        $expected = [
            'id' => $eventType->id,
            'name' => $eventType->name,
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
