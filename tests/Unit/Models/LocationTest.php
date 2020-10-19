<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $location = factory(Location::class)->create();

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
            'spots' => $location->spots,
            'capacity' => $location->capacity,
            'details' => $location->details,
            'venue_uuid' => $location->venue_uuid,
            'category_id' => $location->category_id,
        ]);

        $this->assertEquals($location->category_id, $location->category->id);
    }
}
