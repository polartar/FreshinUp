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
        for ($i = 0; $i < 10; $i++) {

        $venue = factory(Venue::class)->create();
        $resource = new VenueResource($venue);
        $expected = [
            "id" => $venue->id,
            "uuid" => $venue->uuid,
            "name" => $venue->name,
            "address" => $venue->address,
            "address_line_1" => $venue->address_line_1,
            "address_line_2" => $venue->address_line_2,
            'status_id' => $venue->status_id,
            'owner_uuid' => $venue->owner_uuid,
            'latitude' => $venue->latitude,
            'longitude' => $venue->longitude,
        ];
        dump($expected);
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
        $this->assertArrayHasKey('locations', $result);
        }
    }
}
