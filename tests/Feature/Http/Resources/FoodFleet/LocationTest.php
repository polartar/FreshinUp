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
        $expected = [
            "uuid" => $location->uuid,
            "name" => $location->name,
            "venue_uuid" => $location->venue_uuid,
            "category_id" => $location->category_id,
            "spots" => $location->spots,
            "capacity" => $location->capacity,
            "details" => $location->details
        ];
        $request = app()->make(Request::class);
        $this->assertArraySubset($expected, $resource->toArray($request));
    }
}
