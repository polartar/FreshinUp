<?php

namespace Tests\Feature\Unit\Models\Venue;

use App\Models\Foodfleet\Event;
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
        $venue = factory(Venue::class)->create();
        $location = factory(Location::class)->create([
            'venue_uuid' => $venue->uuid
        ]);
        $event = factory(Event::class)->create([
            'venue_uuid' => $venue->uuid
        ]);

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
            'venue_uuid' => $venue->uuid,
            'event_uuid' => $event->uuid,
        ]);
    }
}
