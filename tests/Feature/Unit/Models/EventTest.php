<?php

namespace Tests\Feature\Unit\Models\Event;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Transaction;
use App\Models\Foodfleet\Venue;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
        $store = factory(Store::class)->create();
        $location = factory(Location::class)->create();
        $transaction = factory(Transaction::class)->create();
        $host = factory(Company::class)->create();
        $venue = factory(Venue::class)->create();

        $event = factory(Event::class)->create();
        $event->transactions()->save($transaction);
        $event->location()->associate($location);
        $event->host()->associate($host);
        $event->venue()->associate($venue);
        $event->save();
        $event->eventTags()->sync([$eventTag->uuid]);
        $event->stores()->sync($store->uuid);

        $this->assertDatabaseHas('events', [
            'uuid' => $event->uuid,
            'location_uuid' => $location->uuid,
            'host_uuid' => $host->uuid,
            'venue_uuid' => $venue->uuid
        ]);

        $this->assertDatabaseHas('transactions', [
            'uuid' => $transaction->uuid,
            'event_uuid' => $event->uuid
        ]);

        $this->assertDatabaseHas('events_event_tags', [
            'event_uuid' => $event->uuid,
            'event_tag_uuid' => $eventTag->uuid
        ]);

        $this->assertDatabaseHas('events_stores', [
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);
    }
}
