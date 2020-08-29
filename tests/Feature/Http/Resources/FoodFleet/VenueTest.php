<?php


namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Venue as VenueResource;
use App\Models\Foodfleet\Venue;
use Illuminate\Http\Request;
use Tests\TestCase;

class VenueTest extends TestCase
{

    public function testResource()
    {
        $venue = factory(Venue::class)->make();
        $resource = new VenueResource($venue);
        $expected = [
            "uuid" => $venue->uuid,
            "name" => $venue->name,
            "address" => $venue->address
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
