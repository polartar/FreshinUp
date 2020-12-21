<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Document;
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
     * @test
     * @group venues
     */
    public function testModel()
    {
        /** @var Venue $venue */
        $venue = factory(Venue::class)->create();
        $this->assertDatabaseHas('venues', [
            'uuid' => $venue->uuid,
            'name' => $venue->name,
            'address_line_1' => $venue->address_line_1,
            'address_line_2' => $venue->address_line_2,
            'latitude' => $venue->latitude,
            'longitude' => $venue->longitude,
        ]);

        // external table relations
        $location = factory(Location::class)->create([
            'venue_uuid' => $venue->uuid
        ]);

        $this->assertEquals(1, $venue->locations()->where('uuid', $location->uuid)->count());

        $event = factory(Event::class)->create([
            'venue_uuid' => $venue->uuid,
            'location_uuid' => $location->uuid,
        ]);

        $this->assertEquals(1, $venue->events()->count());
    }

    /**
     * @test
     * @group venues
     */
    public function testVenueOnlyHasEventsThroughLocations()
    {
        //Given

        //there exists a venue
        $venue = factory(Venue::class)->create();

        //events that have this venue as their venue
        $events = factory(Event::class, 3)->create([
            'venue_uuid' => $venue->uuid,
        ]);

        //there are locations for this venue
        $locations = factory(Location::class, 4)->create([
            'venue_uuid' => $venue->uuid,
        ]);

        //there are currently 7 locations but only 3 events
        foreach($locations as $location) {
            $new_events = factory(Event::class)->create([
                'location_uuid' => $location->uuid,
            ]);
        }

        //now there are 7 locations and 7 events
        $this->assertCount(7, Event::all());
        $this->assertCount(7, Location::all());

        //When the venue events are accessed, it should be only 4, since only 4 locations
        $this->assertCount(4, $venue->events()->get());
    }
}
