<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {

        /** @var EventStatus $eventStatus */
        $eventStatus = factory(EventStatus::class)->create();
        $event = factory(Event::class)->create([
            'status_id' => $eventStatus->id
        ]);


        $this->assertDatabaseHas('event_statuses', [
            'id' => $eventStatus->id
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'status_id' => $eventStatus->id
        ]);
    }
}
