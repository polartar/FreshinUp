<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Events;

use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use FreshinUp\FreshBusForms\Models\Company\Company;
use App\Models\Foodfleet\Location;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * test get event list.
     *
     * @return void
     */
    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $events = factory(Event::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($events as $idx => $event) {
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name
            ], $data[$idx]);
        }
    }

    /**
     * test get event list with filters.
     *
     * @return void
     */
    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Event::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventsToFind = factory(Event::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('GET', "/api/foodfleet/events?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($eventsToFind as $idx => $event) {
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('GET', "/api/foodfleet/events?filter[uuid]=" . $eventsToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $eventsToFind->first()->uuid,
            'name' => $eventsToFind->first()->name
        ], $data[0]);
    }

    public function testGetItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $company = factory(Company::class)->create();
        $location = factory(Location::class)->create();
        $eventTag = factory(EventTag::class)->create();

        $event = factory(Event::class)->create([
            'host_uuid' => $company->uuid,
            'location_uuid' => $location->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('GET', 'api/foodfleet/events/' . $event->uuid . '?include=host,location,event_tags')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertEquals($event->uuid, $data['uuid']);
        $this->assertEquals($event->name, $data['name']);
        $this->assertEquals($company->uuid, $data['host']['uuid']);
        $this->assertEquals($location->uuid, $data['location']['uuid']);
        $this->assertEquals($eventTag->uuid, $data['event_tags'][0]['uuid']);
        $this->assertEquals($eventTag->name, $data['event_tags'][0]['name']);
    }

    public function testCreatedItem()
    {
        $admin = factory(User::class)->create([
            'level' => 1
        ]);

        Passport::actingAs($admin);

        $company = factory(Company::class)->create();
        $location = factory(Location::class)->create();
        $eventTags = factory(EventTag::class, 5)->create();
        $eventTagNames = $eventTags->map(function ($item) {
            return $item->name;
        });

        $data = $this
            ->json('POST', 'api/foodfleet/events', [
                'name' => 'test event',
                'host_uuid' => $company->uuid,
                'location_uuid' => $location->uuid,
                'event_tags' => $eventTagNames,
                'status_id' => 1,
                'start_at' => '2019-09-18',
                'end_at' => '2019-09-20',
                'commission_rate' => 30,
                'commission_type' => 1
            ])
            ->assertStatus(201)
            ->json('data');

        $url = 'api/foodfleet/events/' . $data['uuid'] . '?include=host,location,event_tags';
        $returnedEvent = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('test event', $returnedEvent['name']);
        $this->assertEquals(1, $returnedEvent['status_id']);
        $this->assertEquals('2019-09-18', $returnedEvent['start_at']);
        $this->assertEquals('2019-09-20', $returnedEvent['end_at']);
        $this->assertEquals(30, $returnedEvent['commission_rate']);
        $this->assertEquals(1, $returnedEvent['commission_type']);
        $this->assertEquals($company->uuid, $returnedEvent['host']['uuid']);
        $this->assertEquals($location->uuid, $returnedEvent['location']['uuid']);
        $this->assertArraySubset($eventTags->map(function ($item) {
            return [
                'uuid' => $item->uuid,
                'name' => $item->name
            ];
        }), $returnedEvent['event_tags']);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $company = factory(Company::class)->create();
        $location = factory(Location::class)->create();
        $eventTag = factory(EventTag::class)->create();

        $company2 = factory(Company::class)->create();
        $location2 = factory(Location::class)->create();
        $eventTag2 = factory(EventTag::class)->create();

        $event = factory(Event::class)->create([
            'status_id' => 1,
            'host_uuid' => $company->uuid,
            'location_uuid' => $location->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('PUT', 'api/foodfleet/events/' . $event->uuid, [
                'name' => 'test event',
                'host_uuid' => $company2->uuid,
                'location_uuid' => $location2->uuid,
                'event_tags' => [$eventTag2->name],
                'status_id' => 2
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/events/' . $event->uuid . '?include=host,location,event_tags';
        $returnedEvent = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('test event', $returnedEvent['name']);
        $this->assertEquals(2, $returnedEvent['status_id']);
        $this->assertEquals($company2->uuid, $returnedEvent['host']['uuid']);
        $this->assertEquals($location2->uuid, $returnedEvent['location']['uuid']);
        $this->assertEquals($eventTag2->uuid, $returnedEvent['event_tags'][0]['uuid']);
        $this->assertEquals($eventTag2->name, $returnedEvent['event_tags'][0]['name']);
    }
}
