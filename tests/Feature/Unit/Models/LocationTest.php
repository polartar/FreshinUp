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
        $payment = factory(Payment::class)->create();
        $staff = factory(Staff::class)->create();

        $location->events()->save($event);
        $location->payments()->save($payment);
        $location->staffs()->sync([$staff->uuid]);

        $this->assertDatabaseHas('locations', [
            'uuid' => $location->uuid,
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'location_uuid' => $location->uuid
        ]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'location_uuid' => $location->uuid
        ]);

        $this->assertDatabaseHas('locations_staffs', [
            'location_uuid' => $location->uuid,
            'staff_uuid' => $staff->uuid
        ]);
    }
}
