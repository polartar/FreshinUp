<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Artisan;
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
        Artisan::call('db:seed --class=EventStatusesSeeder');
        $event = factory(Event::class)->create();
        /** @var EventHistory $eventHistory */
        $eventStatuses = EventStatus::get();
        $eventStatus = $this->faker->randomElement($eventStatuses);
        $eventHistory = factory(EventHistory::class)->create([
            'status_id' => $eventStatus->id,
            'event_uuid' => $event->uuid
        ]);

        $this->assertDatabaseHas('event_histories', [
            'id' => $eventHistory->id,
            'status_id' => $eventStatus->id,
            'event_uuid' => $event->uuid
        ]);
    }
}
