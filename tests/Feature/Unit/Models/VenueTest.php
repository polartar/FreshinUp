<?php

namespace Tests\Feature\Unit\Models\Venue;

use App\Models\Foodfleet\Venue;
use App\Models\Foodfleet\Location;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VenueTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $location = factory(Location::class)->create();
        $venue = factory(Venue::class)->create();

        $venue->locations()->save($location);
        $venue->save();

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
            'venue_uuid' => $venue->uuid
        ]);
    }
}
