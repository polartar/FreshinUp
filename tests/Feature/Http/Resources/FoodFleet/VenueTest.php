<?php


namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Venue as VenueResource;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
use Illuminate\Http\Request;
use Tests\TestCase;

class VenueTest extends TestCase
{

    public function testResource()
    {
        $venue = factory(Venue::class)->create();
        $resource = new VenueResource($venue);
        $expected = [
            "uuid" => $venue->uuid,
            "name" => $venue->name,
            "address" => $venue->address,
            'status_id' => $venue->status_id,
            'owner_uuid' => $venue->owner_uuid
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
        $this->assertArrayHasKey('locations', $result);
    }
}
