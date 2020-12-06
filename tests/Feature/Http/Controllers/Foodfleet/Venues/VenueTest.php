<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Venues;

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Venue;
use App\Models\Foodfleet\VenueStatus;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class VenueTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $venues = factory(Venue::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/venues")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($venues as $idx => $venue) {
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name,
                'address' => $venue->address,
                'address_line_1' => $venue->address_line_1,
                'address_line_2' => $venue->address_line_2,
                'longitude' => $venue->longitude,
                'latitude' => $venue->latitude,
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Venue::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $venuesToFind = factory(Venue::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/venues")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($venuesToFind as $idx => $venue) {
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name,
                'address' => $venue->address,
                'address_line_1' => $venue->address_line_1,
                'address_line_2' => $venue->address_line_2,
                'longitude' => $venue->longitude,
                'latitude' => $venue->latitude,
            ], $data[$idx]);
        }
        $venue = $venuesToFind->first();
        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[uuid]=".$venue->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $venue->uuid,
            'name' => $venue->name,
            'address' => $venue->address,
            'address_line_1' => $venue->address_line_1,
            'address_line_2' => $venue->address_line_2,
            'latitude' => $venue->latitude,
            'longitude' => $venue->longitude,
        ], $data[0]);
    }

    public function testGetListIncludingLocations()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $venues = factory(Venue::class, 5)->create();
        $venueLocations = [];
        foreach ($venues as $venue) {
            $venueLocations[$venue->uuid] = factory(Location::class, mt_rand(1, 3))
                ->create([
                    'venue_uuid' => $venue->uuid
                ]);
        }
        $data = $this
            ->json('get', "/api/foodfleet/venues?include=locations")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($venues as $idx => $venue) {
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name,
                'address' => $venue->address,
                'address_line_1' => $venue->address_line_1,
                'address_line_2' => $venue->address_line_2,
                'longitude' => $venue->longitude,
                'latitude' => $venue->latitude,
            ], $data[$idx]);
            $this->assertArrayHasKey('locations', $data[$idx]);
            foreach ($venueLocations[$venue->uuid] as $locationIndex => $location) {
                $this->assertArraySubset([
                    "uuid" => $location->uuid,
                    "name" => $location->name,
                    "venue_uuid" => $location->venue_uuid,
                    "spots" => $location->spots,
                    "capacity" => $location->capacity,
                    "details" => $location->details
                ], $data[$idx]['locations'][$locationIndex]);
            }
        }
    }

    public function testGetListWithOwnerUuidFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(Venue::class, 4)->create();

        $owner = factory(User::class)->create();
        $store = factory(Venue::class)->create([
            'owner_uuid' => $owner->uuid
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[owner_uuid]=" . $owner->uuid)
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

    public function testGetListWithStatusIdFilter()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $nonstatus = factory(VenueStatus::class)->create();
        factory(Venue::class, 5)->create([
            'name' => 'Not visibles',
            'status_id' => $nonstatus->id
        ]);
        $statuses = factory(VenueStatus::class, 2)->create();
        $storeToFind1 = factory(Venue::class)->create([
            'name' => 'To find 1',
            'status_id' => $statuses->first()->id
        ]);
        $storeToFind2 = factory(Venue::class)->create([
            'name' => 'To find 2',
            'status_id' => $statuses->last()->id
        ]);
        $statusId = $statuses->map(function ($status) {
            return $status->id;
        })->join(',');
        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[status_id]=" . $statusId)
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

    public function testGetListWithIncludeStatusAndOwner()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $status = factory(VenueStatus::class)->create();

        $venue = factory(Venue::class)->create([
            'owner_uuid' => $user->uuid,
            'status_id' => $status->id,
        ]);

        $data = $this->json('GET', '/api/foodfleet/venues?include=status,owner')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');

        $this->assertArraySubset([
            'uuid' => $venue->uuid,
            'name' => $venue->name,
        ], $data[0]);

        $this->assertArraySubset([
            'uuid' => $user->uuid,
            'name' => $user->name,
        ], $data[0]['owner']);
    }

    public function testUpdateNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Venue::class)->make()->toArray();

        $this->json('PUT', 'api/foodfleet/venues/abc', $payload)
            ->assertStatus(404);
    }

    public function testUpdateWithInvalidPayload()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $venue = factory(Venue::class)->create();
        $payload = factory(Venue::class)->make()->toArray();
        $payload['owner_uuid'] = 'abc';

        $this->json('PUT', '/api/foodfleet/venues/'.$venue->uuid, $payload)
            ->assertStatus(422);

        $payload['status_id'] = 999;
        $this->json('PUT', '/api/foodfleet/venues/'.$venue->uuid, $payload)
            ->assertStatus(422);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $venue = factory(Venue::class)->create();
        $payload = factory(Venue::class)->make([
            'status_id' => factory(VenueStatus::class)->create()->id
        ])->toArray();

        $data = $this->json('PUT', '/api/foodfleet/venues/'.$venue->uuid, $payload)
            ->assertStatus(200)
            ->json('data');
        $expected = [
          'id' => $venue->id,
          'uuid' => $venue->uuid,
          'name' => $payload['name'],
          'address' => $payload['address'],
          'address_line_1' => $payload['address_line_1'],
          'address_line_2' => $payload['address_line_2'],
          'status_id' => $payload['status_id'],
          'owner_uuid' => $payload['owner_uuid'],
          'longitude' => $payload['longitude'],
          'latitude' => $payload['latitude'],
        ];
        $this->assertArraySubset($expected, $data);
    }

    public function testDeleteNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->json('DELETE', '/api/foodfleet/venues/abc123')
            ->assertStatus(404);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $venue = factory(Venue::class)->create();

        $this
            ->json('DELETE', '/api/foodfleet/venues/'.$venue->uuid)
            ->assertStatus(204);
    }

    public function testCreatedItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Venue::class)->make()->toArray();
        $data = $this
            ->json('POST', 'api/foodfleet/venues', $payload)
            ->assertStatus(201)
            ->json('data');

        $this->assertArraySubset([
            'name' => $payload['name'],
            'address_line_1' => $payload['address_line_1'],
            'address_line_2' => $payload['address_line_2'],
            'status_id' => $payload['status_id'],
            'owner_uuid' => $payload['owner_uuid'],
            'latitude' => $payload['latitude'],
            'longitude' => $payload['longitude'],
        ], $data);
    }

    public function testGetItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $venue = factory(Venue::class)->create();

        $data = $this
            ->json('GET', '/api/foodfleet/venues/'.$venue->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertArraySubset([
            "id" => $venue->id,
            "uuid" => $venue->uuid,
            "name" => $venue->name,
            "address" => $venue->address,
            "address_line_1" => $venue->address_line_1,
            "address_line_2" => $venue->address_line_2,
            "status_id" => $venue->status_id,
            'owner_uuid' => $venue->owner_uuid,
            'latitude' => $venue->latitude,
            'longitude' => $venue->longitude,
        ], $data);
    }

    public function testGetItemIncludingOwner()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $owner = factory(User::class)->create();
        $venue = factory(Venue::class)->create([
            'owner_uuid' => $owner->uuid
        ]);

        $data = $this
            ->json('GET', '/api/foodfleet/venues/'.$venue->uuid . '?include=owner')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertArraySubset([
            "id" => $venue->id,
            "uuid" => $venue->uuid,
            "name" => $venue->name,
            "address" => $venue->address,
            "address_line_1" => $venue->address_line_1,
            "address_line_2" => $venue->address_line_2,
            "status_id" => $venue->status_id,
            'owner_uuid' => $venue->owner_uuid,
            'latitude' => $venue->latitude,
            'longitude' => $venue->longitude,
        ], $data);
        $this->assertArrayHasKey('owner', $data);
        $this->assertArraySubset([
            'uuid' => $owner->uuid,
            'mobile_phone' => $owner->mobile_phone,
            'name' => $owner->name,
            'email' => $owner->email,
        ], $data['owner']);
    }
}
