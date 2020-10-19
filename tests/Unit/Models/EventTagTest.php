<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Transaction;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTagTest extends TestCase
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

        $eventTag = factory(EventTag::class)->create();
        $eventTag->events()->sync([$event->uuid]);

        $this->assertDatabaseHas('event_tags', [
            'uuid' => $eventTag->uuid
        ]);

        $this->assertDatabaseHas('events_event_tags', [
            'event_uuid' => $event->uuid,
            'event_tag_uuid' => $eventTag->uuid
        ]);
    }
}
