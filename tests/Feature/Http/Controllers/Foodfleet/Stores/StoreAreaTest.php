<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Stores;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\StoreArea;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoresAreaTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $areas = factory(StoreArea::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/store/areas")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($areas as $idx => $area) {
            $this->assertArraySubset([
                "id" => $area->id,
                "name" => $area->name,
                "radius" => $area->radius,
                "state" => $area->state,
                "store_uuid" => $area->store_uuid,
            ], $data[$idx]);
        }
    }

    public function testGetListFilteredByName()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(StoreArea::class, 3)->create();
        $areas = factory(StoreArea::class, 7)->create([
            'name' => 'aname'
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/store/areas?filter[name]=aname")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(7, $data);
        foreach ($areas as $idx => $area) {
            $this->assertArraySubset([
                "id" => $area->id,
                "name" => $area->name,
                "radius" => $area->radius,
                "state" => $area->state,
                "store_uuid" => $area->store_uuid,
            ], $data[$idx]);
        }
    }

    public function testGetListFilteredByState()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(StoreArea::class, 3)->create();
        $areas = factory(StoreArea::class, 7)->create([
            'state' => 'atlanta'
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/store/areas?filter[state]=atlanta")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(7, $data);
        foreach ($areas as $idx => $area) {
            $this->assertArraySubset([
                "id" => $area->id,
                "name" => $area->name,
                "radius" => $area->radius,
                "state" => $area->state,
                "store_uuid" => $area->store_uuid,
            ], $data[$idx]);
        }
    }

    public function testGetListFilteredByRadius()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(StoreArea::class, 3)->create();
        $areas = factory(StoreArea::class, 7)->create([
            'radius' => 123
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/store/areas?filter[radius]=123")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(7, $data);
        foreach ($areas as $idx => $area) {
            $this->assertArraySubset([
                "id" => $area->id,
                "name" => $area->name,
                "radius" => $area->radius,
                "state" => $area->state,
                "store_uuid" => $area->store_uuid,
            ], $data[$idx]);
        }
    }

    public function testGetListFilteredByStoreUuid()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(StoreArea::class, 3)->create();
        $store = factory(Store::class)->create();
        $areas = factory(StoreArea::class, 7)->create([
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/store/areas?filter[store_uuid]=" . $store->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(7, $data);
        foreach ($areas as $idx => $area) {
            $this->assertArraySubset([
                "id" => $area->id,
                "name" => $area->name,
                "radius" => $area->radius,
                "state" => $area->state,
                "store_uuid" => $area->store_uuid,
            ], $data[$idx]);
        }
    }

    public function testGetListSortedByName()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        for ($i = 0; $i<3; $i++) {
            factory(StoreArea::class)->create([
                'name' => "name" . $i
            ]);
        }

        $data = $this
            ->json('GET', "/api/foodfleet/store/areas?sort=name")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        for ($i = 0; $i<3; $i++) {
            $this->assertEquals("name" . $i, $data[$i]['name']);
        }
    }

    public function testCreateWithoutStoreUuid()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(StoreArea::class)->make()->toArray();
        unset($payload['store_uuid']);
        $this
            ->json('POST', '/api/foodfleet/store/areas', $payload)
            ->assertStatus(422);
    }

    public function testCreate()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(StoreArea::class)->make()->toArray();
        $data = $this
            ->json('POST', '/api/foodfleet/store/areas', $payload)
            ->assertStatus(201)
            ->json('data');
        $this->assertArraySubset([
            'name' => $payload['name'],
            'radius' => $payload['radius'],
            'state' => $payload['state'],
            'store_uuid' => $payload['store_uuid'],
        ], $data);
    }
}
