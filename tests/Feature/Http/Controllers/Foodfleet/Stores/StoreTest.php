<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Stores;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventMenuItem;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use App\Models\Foodfleet\StoreStatus;
use App\Models\Foodfleet\StoreTag;
use App\Models\Foodfleet\StoreType;
use App\User;
use Illuminate\Foundation\Testing\Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoresTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $store = factory(Store::class)->create();
        $data = $this
            ->json('GET', 'api/foodfleet/stores/' . $store->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertEquals($store->uuid, $data['uuid']);
        $this->assertEquals($store->name, $data['name']);
    }

    public function testGetItemIncludingArea()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $store = factory(Store::class)->create();
        $area = factory(StoreArea::class)->create([
            'store_uuid' => $store->uuid
        ]);
        $data = $this
            ->json('GET', 'api/foodfleet/stores/' . $store->uuid . "?include=areas")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'areas' => [
                        '*' => [
                            "id",
                            "name",
                            "radius",
                            "state",
                            "store_uuid",
                        ]
                    ]
                ]
            ])
            ->json('data');
        $this->assertEquals($store->uuid, $data['uuid']);
        $this->assertEquals($store->name, $data['name']);
        $this->assertArrayHasKey('areas', $data);
        $this->assertArraySubset([
            "id" => $area->id,
            "name" => $area->name,
            "radius" => $area->radius,
            "state" => $area->state,
            "store_uuid" => $area->store_uuid,
        ], $data['areas'][0]);
    }

    private function createStoreWithTags($tags)
    {
        $store = factory(Store::class)->create();
        $store->tags()->sync(array_map(function ($tag) {
            return $tag->uuid;
        }, $tags));
        return $store;
    }

    public function testFilterAndTags()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $tags = factory(StoreTag::class, 3)->create();

        $stores = [];
        $stores[] = $this->createStoreWithTags([$tags[0], $tags[1]]);
        $stores[] = $this->createStoreWithTags([$tags[0], $tags[2]]);
        $stores[] = $this->createStoreWithTags([$tags[1], $tags[2]]);

        $data = $this
            ->json('get', "/api/foodfleet/stores?include=tags&filter[tag]={$tags[0]->uuid}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));

        $this->assertArraySubset([
            'uuid' => $stores[0]->uuid,
            'name' => $stores[0]->name,
            'tags' => [['name' => $tags[0]->name]]
        ], $data[0]);

        $this->assertArraySubset([
            'uuid' => $stores[1]->uuid,
            'name' => $stores[1]->name,
            'tags' => [['name' => $tags[0]->name]]
        ], $data[1]);
    }

    public function testTypeAndContacts()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $type = factory(StoreType::class)->create();
        $stores = factory(Store::class, 1)->create([
            'status_id' => 1,
            'type_id' => $type->id,
            'size' => 123,
            'website' => 'a@a.com',
            'contact_phone' => '1234657890',
            'image' => 'a.png',
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/stores")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        foreach ($data as $key => $value) {
            $this->assertArraySubset([
                'uuid' => $stores[$key]->uuid,
                'name' => $stores[$key]->name,
                'size' => $stores[$key]->size,
                'website' => $stores[$key]->website,
                'contact_phone' => $stores[$key]->contact_phone,
                'image' => $stores[$key]->image,
            ], $value);
        }
    }

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $stores = factory(Store::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/stores")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($stores as $idx => $fleetMember) {
            $this->assertArraySubset([
                'uuid' => $fleetMember->uuid,
                'name' => $fleetMember->name
            ], $data[$idx]);
        }
    }

    public function testGetListWithManagerUuidFilter()
    {
        $user = factory(User::class)->create([
            'type' => 1,
            'level' => 5
        ]);
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
        $eventToFind3 = factory(Event::class)->create([
            'name' => 'To find',
            'manager_uuid' => $user->uuid
        ]);
        $userUuids = $usersToFind->map(function ($user) {
            return $user->uuid;
        })->join(',');

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[manager_uuid]=" . $userUuids)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($eventToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($eventToFind2->uuid, $data[1]['uuid']);
        $this->assertEquals($eventToFind3->uuid, $data[2]['uuid']);
    }

    public function testGetListWithStatusIdFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $nonstatus = factory(StoreStatus::class)->create();
        factory(Store::class, 5)->create([
            'name' => 'Not visibles',
            'status_id' => $nonstatus->id
        ]);
        $statuses = factory(StoreStatus::class, 2)->create();
        $storeToFind1 = factory(Store::class)->create([
            'name' => 'To find 1',
            'status_id' => $statuses->first()->id
        ]);
        $storeToFind2 = factory(Store::class)->create([
            'name' => 'To find 2',
            'status_id' => $statuses->last()->id
        ]);
        $statusId = $statuses->map(function ($status) {
            return $status->id;
        })->join(',');
        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[status_id]=" . $statusId)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));
        $this->assertEquals($storeToFind1->uuid, $data[0]['uuid']);
        $this->assertEquals($storeToFind2->uuid, $data[1]['uuid']);
    }

    public function testGetListWithNameFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(Store::class, 5)->create([
            'name' => 'Not visible'
        ]);
        $storesToFind = factory(Store::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($storesToFind as $idx => $fleetMember) {
            $this->assertArraySubset([
                'uuid' => $fleetMember->uuid,
                'name' => $fleetMember->name
            ], $data[$idx]);
        }
    }

    public function testGetListWithStateIncorporationFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        factory(Store::class, 2)->create([
            'state_of_incorporation' => 'Bamako'
        ]);
        $storesToFind = factory(Store::class, 3)->create([
            'state_of_incorporation' => 'Dakar'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[state_of_incorporation]=Dakar")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(3, count($data));
        foreach ($storesToFind as $idx => $fleetMember) {
            $this->assertArraySubset([
                'uuid' => $fleetMember->uuid,
                'state_of_incorporation' => $fleetMember->state_of_incorporation
            ], $data[$idx]);
        }
    }

    public function testGetListWithUuidFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $stores = factory(Store::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[uuid]=" . $stores->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'uuid' => $stores->first()->uuid,
            'name' => $stores->first()->name
        ], $data[0]);
    }

    public function testGetListWithSupplierUuidFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $stores = factory(Store::class, 5)->create();

        $company = factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create();
        $store = $stores->first();
        $store->supplier_uuid = $company->uuid;
        $store->save();

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[supplier_uuid]=" . $company->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'uuid' => $store->uuid,
            'name' => $store->name
        ], $data[0]);
    }

    public function testGetListWithOwnerUuidFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(Store::class, 4)->create();

        $owner = factory(User::class)->create();
        $store = factory(Store::class)->create([
            'owner_uuid' => $owner->uuid
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[owner_uuid]=" . $owner->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'uuid' => $store->uuid,
            'name' => $store->name
        ], $data[0]);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $store = factory(Store::class)->create([
            'status_id' => 1
        ]);
        $payload = [
            'status_id' => 2,
            'name' => $store->name
        ];
        $this
            ->json('PUT', '/api/foodfleet/stores/' . $store->uuid, $payload)
            ->assertStatus(200);

        $url = 'api/foodfleet/stores/' . $store->uuid;
        $returnedStore = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals(2, $returnedStore['status_id']);
    }

    public function testGetListBySorts()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $store1 = factory(Store::class)->create([
            'name' => 'A',
            'status_id' => 3,
            'created_at' => '2019-11-11 07:59:48'
        ]);
        $store2 = factory(Store::class)->create([
            'name' => 'B',
            'status_id' => 2,
            'created_at' => '2019-11-10 07:59:48'
        ]);
        $store3 = factory(Store::class)->create([
            'name' => 'C',
            'status_id' => 1,
            'created_at' => '2019-11-12 07:59:48'
        ]);
        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=name")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store1->uuid);

        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=-name")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store3->uuid);


        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=status_id")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store3->uuid);

        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=-status_id")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store1->uuid);

        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=created_at")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store2->uuid);

        $data = $this
            ->json('get', "/api/foodfleet/stores?sort=-created_at")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        $this->assertEquals($data[0]['uuid'], $store3->uuid);
    }

    public function testUpdateCommission()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $store->events()->sync([$event->uuid]);
        $this
            ->json('PUT', 'api/foodfleet/stores/' . $store->uuid, [
                'name' => 'test store',
                'event_uuid' => $event->uuid,
                'commission_rate' => 12,
                'commission_type' => 1
            ])
            ->assertStatus(200)
            ->json('data');

        $result = $this->json('GET', 'api/foodfleet/stores/' . $store->uuid . "?include=events")
            ->assertStatus(200)
            ->json('data');
        $this->assertEquals('test store', $result['name']);
    }

    public function testStoreSummary()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $company = factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create([
            'users_id' => $user->id
        ]);
        $store = factory(Store::class)->create([
            'supplier_uuid' => $company->uuid,
            'owner_uuid' => $user->uuid
        ]);
        $tags = factory(StoreTag::class, 3)->create();
        $store->tags()->sync($tags->map(function ($tag) {
            return $tag->uuid;
        }));
        $data = $this
            ->json('get', "/api/foodfleet/store-summary/" . $store->uuid . "?include=owner")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals($data['owner']['uuid'], $user->uuid);
    }

    public function testStoreServiceSummary()
    {
        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $store->events()->sync([$event->uuid]);
        factory(EventMenuItem::class, 3)->create([
            'servings' => 2,
            'cost' => 70,
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);
        $this
            ->json('PUT', 'api/foodfleet/stores/' . $store->uuid, [
                'name' => 'test store',
                'event_uuid' => $event->uuid,
                'commission_rate' => 12,
                'commission_type' => 1
            ])
            ->assertStatus(200)
            ->json('data');

        $data = $this
            ->json('get', "/api/foodfleet/store-service-summary/" . $store->uuid . "?event_uuid=" . $event->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals($data['total_services'], 6);
        $this->assertEquals($data['total_cost'], 210);
    }

    public function testCreateWithInvalid()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $store = [
        ];
        $response = $this->json('POST', '/api/foodfleet/stores', $store);
        $response
            ->assertStatus(422);
    }

    public function testCreate()
    {
        $user = factory(User::class)->create([
            'company_id' => factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create()->id
        ]);
        Passport::actingAs($user);
        $payload = factory(Store::class)->make()->toArray();
        $data = $this
            ->json('POST', '/api/foodfleet/stores', $payload)
            ->assertStatus(201)
            ->json('data');
        $this->assertArraySubset([
            'status_id' => $payload['status_id'],
            'address_uuid' => $payload['address_uuid'],
            'contact_phone' => $payload['contact_phone'],
            'size' => $payload['size'],
            'owner_uuid' => $payload['owner_uuid'],
            'type_id' => $payload['type_id'],
            'name' => $payload['name'],
            'state_of_incorporation' => $payload['state_of_incorporation'],
            'website' => $payload['website'],
            'twitter' => $payload['twitter'],
            'facebook' => $payload['facebook'],
            'instagram' => $payload['instagram'],
            'staff_notes' => $payload['staff_notes'],
            // Should not take it from the payload
            // 'supplier_uuid' => $payload['supplier_uuid'],
            'supplier_uuid' => $user->company->uuid,
        ], $data);
    }

    public function testCreateWithTags()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Store::class)->make()->toArray();
        $tags = factory(StoreTag::class, 3)->create();
        $payload['tags'] = array_map(function ($tag) {
            return $tag['uuid'];
        }, $tags->toArray());

        $response = $this
            ->json('POST', '/api/foodfleet/stores', $payload);
        $data = $response
            ->assertStatus(201)
            ->json('data');

        $this->assertArraySubset([
            'owner_uuid' => $payload['owner_uuid'],
            'type_id' => $payload['type_id'],
            'name' => $payload['name'],
            'size' => $payload['size'],
            'contact_phone' => $payload['contact_phone'],
            'state_of_incorporation' => $payload['state_of_incorporation'],
            'website' => $payload['website'],
            'twitter' => $payload['twitter'],
            'facebook' => $payload['facebook'],
            'instagram' => $payload['instagram'],
            'staff_notes' => $payload['staff_notes'],
        ], $data);
        $this->assertArrayHasKey('tags', $data);
        $this->assertArraySimilar(array_map(function ($tag) {
            return [
                'uuid' => $tag['uuid'],
                'name' => $tag['name'],
            ];
        }, $tags->toArray()), $data['tags']);
    }

    public function testUpdateWithTags()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Store::class)->make()->toArray();
        $store = factory(Store::class)->create();
        $tags = factory(StoreTag::class, 3)->create();
        $payload['tags'] = array_map(function ($tag) {
            return $tag['uuid'];
        }, $tags->toArray());

        $response = $this
            ->json('PUT', '/api/foodfleet/stores/' . $store->uuid, $payload);
        $data = $response
            ->assertStatus(200)
            ->json('data');

        $this->assertArraySubset([
            'owner_uuid' => $payload['owner_uuid'],
            'type_id' => $payload['type_id'],
            'name' => $payload['name'],
            'size' => $payload['size'],
            'contact_phone' => $payload['contact_phone'],
            'state_of_incorporation' => $payload['state_of_incorporation'],
            'website' => $payload['website'],
            'twitter' => $payload['twitter'],
            'facebook' => $payload['facebook'],
            'instagram' => $payload['instagram'],
            'staff_notes' => $payload['staff_notes'],
        ], $data);

        $this->assertArrayHasKey('tags', $data);

        $this->assertArraySimilar(array_map(function ($tag) {
            return [
                'uuid' => $tag['uuid'],
                'name' => $tag['name'],
            ];
        }, $tags->toArray()), $data['tags']);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $area = factory(StoreArea::class)->create();
        $this->json('DELETE', 'api/foodfleet/store/areas/' . $area->id)
            ->assertStatus(204);
        $this->assertEquals(0, StoreArea::where('id', $area->id)->count());
    }


    public function testGetEventListOnNotExistingStore()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this
            ->json('GET', "/api/foodfleet/stores/999/events")
            ->assertStatus(404);
    }

    public function testGetEventList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(Event::class, 5)->create();
        /** @var Store $store */
        $store = factory(Store::class)->create();
        $events = factory(Event::class, 3)->create();
        foreach ($events as $event) {
            $store->events()->attach($event->uuid);
        }
        $this->assertEquals(3, $store->events()->count());

        $data = $this
            ->json('GET', "/api/foodfleet/stores/{$store->uuid}/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals($store->events()->count(), count($data));
        // TODO a better way of asserting the following
    }

    public function testCanGetStatsOfStoresByStatuses()
    {
        //Given
        $statuses = StoreStatus::all();
        $state_count = count($statuses);//6 here
        $store_count = 1;
        $total_number_of_stores = $state_count * $store_count;

        foreach ($statuses as $status) {
            $state = factory(StoreStatus::class)->create([
                'name' => $status['label'],
            ]);

            factory(Store::class, $store_count)->create([
                'status_id' => $state->id,
            ]);
        }

        // 6 states, each with 5 stores, can expect there to be 30 stores now
        $this->assertCount($total_number_of_stores, Store::all());
        $this->assertCount($state_count, StoreStatus::all());

        // get all the various statuses and create stores to match

        // When
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $data = $this->getJson('/api/foodfleet/stores/stats')
            ->assertStatus(200)
            ->assertJsonStructure([ 'data' ])
            ->json('data');

        $this->assertEquals($state_count, count($data));//the number of states there are

        foreach ($data as $id => $state) {
            Assert::assertArraySubset([
                'label' => $state['label'],
                'color' => $state['color'],
                'value' => $state['value']
            ], $data[$id]);
        }
    }
}
