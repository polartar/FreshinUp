<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventHistoryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $eventHistory = factory(EventHistory::class)->create();

        $event = factory(Event::class)->create();

        $eventStatus = factory(EventStatus::class)->create();

        $eventHistory->event()->associate($event);
        $eventHistory->status()->associate($eventStatus);

        $eventHistory->save();

        $this->assertDatabaseHas('event_histories', [
            'id' => $eventHistory->id,
            'status_id' => $eventStatus->id,
            'event_uuid' => $event->uuid
        ]);
    }
}
