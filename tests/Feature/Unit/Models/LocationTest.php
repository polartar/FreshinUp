<?php

namespace Tests\Feature\Unit\Models\Location;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
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
        $event = factory(Event::class)->create([
            'location_uuid' => $location->uuid
        ]);

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'location_uuid' => $location->uuid
        ]);
    }
}
