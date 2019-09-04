<?php

namespace Tests\Feature\Unit\Models\Payment;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\FleetMember;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $eventTag = factory(EventTag::class)->create();
        $fleetMember = factory(FleetMember::class)->create();
        $location = factory(Location::class)->create();
        $payment = factory(Payment::class)->create();

        $event = factory(Event::class)->create();
        $event->payments()->save($payment);
        $event->location()->associate($location);
        $event->fleetMember()->associate($fleetMember);
        $event->save();
        $event->eventTags()->sync([$eventTag->uuid]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'location_uuid' => $location->uuid,
            'fleet_member_uuid' => $fleetMember->uuid
        ]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'event_uuid' => $event->uuid
        ]);

        $this->assertDatabaseHas('events_event_tags', [
            'event_uuid' => $event->uuid,
            'event_tag_uuid' => $eventTag->uuid
        ]);
    }
}
