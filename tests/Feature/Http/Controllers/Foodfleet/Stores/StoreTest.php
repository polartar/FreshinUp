<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Stores;

use App\Models\Foodfleet\Company;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreStatus;
use App\Models\Foodfleet\StoreType;
use App\Models\Foodfleet\StoreTag;
use App\Models\Foodfleet\EventMenuItem;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoresTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
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
            'size' => 'A',
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

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Store::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $storesToFind = factory(Store::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/stores")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


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

        $data = $this
            ->json('get', "/api/foodfleet/stores?filter[uuid]=" . $storesToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $storesToFind->first()->uuid,
            'name' => $storesToFind->first()->name
        ], $data[0]);


        $company = factory(\FreshinUp\FreshBusForms\Models\Company\Company::class)->create();
        $storesToFind->first()->supplier_uuid = $company->uuid;
        $storesToFind->first()->save();

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
            'uuid' => $storesToFind->first()->uuid,
            'name' => $storesToFind->first()->name
        ], $data[0]);
    }

    public function testUpdate()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create([
            'status_id' => 1
        ]);

        $data = $this
            ->json('PUT', '/api/foodfleet/stores/' . $store->uuid, [
                'status_id' => 2
            ])
            ->assertStatus(200);

        $url = 'api/foodfleet/stores/' . $store->uuid;
        $returnedStore = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals(2, $returnedStore['status_id']);
    }

    /**
     * test for the sort options
     */
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

        $data = $this
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
            'supplier_uuid' => $company->uuid
        ]);

        $tags = factory(StoreTag::class, 3)->create();
        $store->tags()->sync($tags->map(function ($tag) {
            return $tag->uuid;
        }));

        $data = $this
            ->json('get', "/api/foodfleet/store-summary/" . $store->uuid)
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
        $items = factory(EventMenuItem::class, 3)->create([
            'servings' => 2,
            'cost' => 70,
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $data = $this
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
}
