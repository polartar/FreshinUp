<?php

namespace Tests\Feature\Unit\Models\EventStatus;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $event = factory(Event::class)->create();

        $eventStatus = factory(EventStatus::class)->create();
        $eventStatus->events()->save($event);

        $this->assertDatabaseHas('event_statuses', [
            'id' => $eventStatus->id
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'status' => $eventStatus->id
        ]);
    }
}
