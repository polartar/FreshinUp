<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Events;

use App\User;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventTag;
use App\Models\Foodfleet\EventStatus;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;

use App\Enums\EventStatus as EventStatusEnum;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Carbon;
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

    public function testGetListWithHostUuidFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $nonhost = factory(Company::class)->create();

        factory(Event::class, 5)->create([
            'name' => 'Not visibles',
            'host_uuid' => $nonhost->uuid
        ]);

        $hosts = factory(Company::class, 2)->create();

        $eventToFind1 = factory(Event::class)->create([
            'name' => 'To find 1',
            'host_uuid' => $hosts->first()->uuid
        ]);

        $eventToFind2 = factory(Event::class)->create([
            'name' => 'To find 2',
            'host_uuid' => $hosts->last()->uuid
        ]);

        $hostUuid = $hosts->map(function ($host) {
            return $host->uuid;
        })->join(',');

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[host_uuid]=" . $hostUuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertEquals($eventToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($eventToFind2->uuid, $data[1]['uuid']);
    }

    public function testGetListWithManagerUuidFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $nonuser = factory(User::class)->create();

        factory(Event::class, 5)->create([
            'name' => 'Not visibles',
            'manager_uuid' => $nonuser->uuid
        ]);

        $usersToFind = factory(User::class, 2)->create();

        $eventToFind1 = factory(Event::class)->create([
            'name' => 'To find',
            'manager_uuid' => $usersToFind->first()->uuid
        ]);

        $eventToFind2 = factory(Event::class)->create([
            'name' => 'To find',
            'manager_uuid' => $usersToFind->last()->uuid
        ]);

        $userUuid = $usersToFind->map(function ($user) {
            return $user->uuid;
        })->join(',');

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[manager_uuid]=" . $userUuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertEquals($eventToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($eventToFind2->uuid, $data[1]['uuid']);
    }

    public function testGetListWithStatusIdFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $nonstatus = factory(EventStatus::class)->create();

        factory(Event::class, 5)->create([
            'name' => 'Not visibles',
            'status_id' => $nonstatus->id
        ]);

        $statuses = factory(EventStatus::class, 2)->create();

        $eventToFind1 = factory(Event::class)->create([
            'name' => 'To find 1',
            'status_id' => $statuses->first()->id
        ]);

        $eventToFind2 = factory(Event::class)->create([
            'name' => 'To find 2',
            'status_id' => $statuses->last()->id
        ]);

        $statusId = $statuses->map(function ($status) {
            return $status->id;
        })->join(',');

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[status_id]=" . $statusId)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertEquals($eventToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($eventToFind2->uuid, $data[1]['uuid']);
    }

    public function testGetListWithEventTagUuidFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Event::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventTags = factory(EventTag::class, 2)->create();

        $eventToFind1 = factory(Event::class)->create([
            'name' => 'To find 1'
        ]);
        $eventToFind1->eventTags()->save($eventTags->first());

        $eventToFind2 = factory(Event::class)->create([
            'name' => 'To find 2'
        ]);
        $eventToFind2->eventTags()->save($eventTags->last());

        $eventTagUuid = $eventTags->map(function ($eventTag) {
            return $eventTag->uuid;
        })->join(',');

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[event_tag_uuid]=" . $eventTagUuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertEquals($eventToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($eventToFind2->uuid, $data[1]['uuid']);
    }

    public function testGetListWithStartAtFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        Carbon::setTestNow(Carbon::createFromTimeString('2019-10-01 01:03:40.930965'));

        factory(Event::class, 5)->create([
            'name' => 'Not visibles',
            'start_at' => Carbon::now()->subDays(5),
            'end_at' => Carbon::now()->subDays(10)
        ]);

        $eventToFind = factory(Event::class)->create([
            'name' => 'To find',
            'start_at' => Carbon::now(),
            'end_at' => Carbon::now()->subDays(1)
        ]);

        $startAt = Carbon::now()->subDays(2)->toDateTimeString();
        $endAt = Carbon::now()->addDays(2)->toDateTimeString();

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[start_at]=" . $startAt . '&filter[end_at]='. $endAt)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertEquals($eventToFind->uuid, $data[0]['uuid']);
        Carbon::setTestNow();
    }

    public function testGetListWithInclude()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $eventTag = factory(EventTag::class)->create();
        $status = factory(EventStatus::class)->create();
        $location = factory(Location::class)->create();
        $host = factory(Company::class)->create();

        $event = factory(Event::class)->create([
            'manager_uuid' => $user->uuid,
            'status_id' => $status->id,
            'location_uuid' => $location->uuid,
            'host_uuid' => $host->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this->json('GET', '/api/foodfleet/events?include=status,host,location,manager,event_tags')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');

        $this->assertArraySubset([
            'uuid' => $event->uuid,
            'name' => $event->name,
        ], $data[0]);

        $this->assertArraySubset([
            'uuid' => $eventTag->uuid,
            'name' => $eventTag->name,
        ], $data[0]['event_tags'][0]);

        $this->assertArraySubset([
            'uuid' => $location->uuid,
            'name' => $location->name,
        ], $data[0]['location']);

        $this->assertArraySubset([
            'uuid' => $user->uuid,
            'name' => $user->name,
        ], $data[0]['manager']);

        $this->assertArraySubset([
            'uuid' => $host->uuid,
            'name' => $host->name,
        ], $data[0]['host']);
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
            'location_uuid' => $location->uuid,
            'manager_uuid' => $user->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('GET', 'api/foodfleet/events/' . $event->uuid . '?include=manager,host,location,event_tags')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertEquals($event->uuid, $data['uuid']);
        $this->assertEquals($event->name, $data['name']);
        $this->assertEquals($user->uuid, $data['manager']['uuid']);
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
                'manager_uuid' => $admin->uuid,
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

        $url = 'api/foodfleet/events/' . $data['uuid'] . '?include=manager,host,location,event_tags';
        $returnedEvent = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('test event', $returnedEvent['name']);
        $this->assertEquals(1, $returnedEvent['status_id']);
        $this->assertEquals('2019-09-18', $returnedEvent['start_at']);
        $this->assertEquals('2019-09-20', $returnedEvent['end_at']);
        $this->assertEquals(30, $returnedEvent['commission_rate']);
        $this->assertEquals(1, $returnedEvent['commission_type']);
        $this->assertEquals($admin->uuid, $returnedEvent['manager']['uuid']);
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

        $user2 = factory(User::class)->create();
        $company2 = factory(Company::class)->create();
        $location2 = factory(Location::class)->create();
        $eventTag2 = factory(EventTag::class)->create();

        $event = factory(Event::class)->create([
            'status_id' => 1,
            'manager_uuid' => $user->uuid,
            'host_uuid' => $company->uuid,
            'location_uuid' => $location->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('PUT', 'api/foodfleet/events/' . $event->uuid, [
                'name' => 'test event',
                'manager_uuid' => $user2->uuid,
                'host_uuid' => $company2->uuid,
                'location_uuid' => $location2->uuid,
                'event_tags' => [$eventTag2->name],
                'status_id' => 2
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/events/' . $event->uuid . '?include=manager,host,location,event_tags';
        $returnedEvent = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('test event', $returnedEvent['name']);
        $this->assertEquals(2, $returnedEvent['status_id']);
        $this->assertEquals($user2->uuid, $returnedEvent['manager']['uuid']);
        $this->assertEquals($company2->uuid, $returnedEvent['host']['uuid']);
        $this->assertEquals($location2->uuid, $returnedEvent['location']['uuid']);
        $this->assertEquals($eventTag2->uuid, $returnedEvent['event_tags'][0]['uuid']);
        $this->assertEquals($eventTag2->name, $returnedEvent['event_tags'][0]['name']);
    }

    public function testAssignStores()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $company = factory(Company::class)->create();
        $location = factory(Location::class)->create();
        $eventTag = factory(EventTag::class)->create();
        $stores = factory(Store::class, 3)->create();
        $storeUuids = $stores->map(function ($item) {
            return $item->uuid;
        });

        $event = factory(Event::class)->create([
            'status_id' => 1,
            'manager_uuid' => $user->uuid,
            'host_uuid' => $company->uuid,
            'location_uuid' => $location->uuid
        ]);
        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('PUT', 'api/foodfleet/events/' . $event->uuid, [
                'store_uuids' => $storeUuids
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/events/' . $event->uuid . '?include=stores';
        $returnedEvent = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($returnedEvent);
        $this->assertEquals(3, count($returnedEvent['stores']));
        foreach ($stores as $idx => $store) {
            $this->assertArraySubset([
                'uuid' => $store->uuid,
                'name' => $store->name,
                'square_id' => $store->square_id
            ], $returnedEvent['stores'][$idx]);
        }
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $company = factory(Company::class)->create();
        $location = factory(Location::class)->create();
        $eventTag = factory(EventTag::class)->create();

        $event = factory(Event::class)->create([
            'host_uuid' => $company->uuid,
            'location_uuid' => $location->uuid,
            'manager_uuid' => $user->uuid
        ]);

        $event->eventTags()->save($eventTag);

        $data = $this
            ->json('GET', 'api/foodfleet/events/' . $event->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals($event->uuid, $data['uuid']);
        $this->assertDatabaseHas('events_event_tags', [
            'event_uuid' => $event->uuid,
            'event_tag_uuid' => $eventTag->uuid,
        ]);

        $this->json('DELETE', 'api/foodfleet/events/' . $event->uuid)
            ->assertStatus(204);

        $this->json('GET', 'api/foodfleet/events/' . $event->uuid)
            ->assertStatus(404);
    }

    public function testGetNewItemRecommendation()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this->json('GET', 'api/foodfleet/events/new')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');

        $this->assertEquals($data['status_id'], EventStatusEnum::DRAFT);
    }
}
