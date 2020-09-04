<?php

namespace Tests\Feature\Unit\Models\Venue;

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
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        /** @var Venue $venue */
        $venue = factory(Venue::class)->create();
        $this->assertDatabaseHas('venues', [
            'uuid' => $venue->uuid,
            'name' => $venue->name,
            'address' => $venue->address,
        ]);

        // external table relations
        $location = factory(Location::class)->create([
            'venue_uuid' => $venue->uuid
        ]);
        $this->assertEquals(1, $venue->locations()->where('uuid', $location->uuid)->count());

        $event = factory(Event::class)->create([
            'venue_uuid' => $venue->uuid
        ]);
        $this->assertEquals(1, $venue->events()->where('uuid', $event->uuid)->count());

        // TODO: test documents
    }
}
