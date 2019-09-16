<?php

namespace Tests\Feature\Unit\Models\Location;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\Staff;
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
        $event = factory(Event::class)->create();

        $location->events()->save($event);

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'location_uuid' => $location->uuid
        ]);
    }
}
