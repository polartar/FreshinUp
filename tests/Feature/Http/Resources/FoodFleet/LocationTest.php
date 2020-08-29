<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Location as LocationResource;
use App\Models\Foodfleet\Location;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocationTest extends TestCase {

    public function testResource () {
        $location = factory(Location::class)->make();
        $resource = new LocationResource($location);
        $venue = $location->venue;
        $expected = [
            "uuid" => $location->uuid,
            "name" => $location->name,
            "venue" => [
                "uuid" => $venue->uuid,
                "name" => $venue->name,
                "address" => $venue->address
            ],
            "venue_uuid" => $location->venue_uuid,
            "spot" => $location->spots,
            "capacity" => $location->capacity,
            "details" => $location->details
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
