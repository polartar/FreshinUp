<?php


namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventStatus;
use App\Models\Foodfleet\EventType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTypeTest extends TestCase
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

        $eventType = factory(EventType::class)->create();
        $eventType->events()->save($event);

        $this->assertDatabaseHas('event_types', [
            'id' => $eventType->id
        ]);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'type_id' => $eventType->id
        ]);
    }
}
