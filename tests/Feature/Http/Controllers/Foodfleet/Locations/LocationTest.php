<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Locations;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $locations = factory(Location::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/locations")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($locations as $idx => $location) {
            $this->assertArraySubset([
                'uuid' => $location->uuid,
                'name' => $location->name,
                "venue_uuid" => $location->venue_uuid,
                "category_id" => $location->category_id,
                "spots" => $location->spots,
                "capacity" => $location->capacity,
                "details" => $location->details
            ], $data[$idx]);
        }
    }

    public function testGetListIncludingVenue()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $locations = factory(Location::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/locations?include=venue")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($locations as $idx => $location) {
            $this->assertArraySubset([
                'uuid' => $location->uuid,
                'name' => $location->name,
                "venue_uuid" => $location->venue_uuid,
                "category_id" => $location->category_id,
                "spots" => $location->spots,
                "capacity" => $location->capacity,
                "details" => $location->details
            ], $data[$idx]);

            $venue = $location->venue;
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name,
                'address' => $venue->address
            ], $data[$idx]['venue']);
        }
    }

    public function testGetListIncludingCategory()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $locations = factory(Location::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/locations?include=category")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($locations as $idx => $location) {
            $this->assertArraySubset([
                'uuid' => $location->uuid,
                'name' => $location->name,
                "venue_uuid" => $location->venue_uuid,
                "category_id" => $location->category_id,
                "spots" => $location->spots,
                "capacity" => $location->capacity,
                "details" => $location->details
            ], $data[$idx]);

            $category = $location->category;
            $this->assertArraySubset([
                'id' => $category->id,
                'name' => $category->name,
            ], $data[$idx]['category']);
        }
    }

    public function testGetListIncludingEvents()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $location = factory(Location::class)->create();
        $event = factory(Event::class)->create([
            'location_uuid' => $location->uuid
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/locations?include=events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'uuid' => $location->uuid,
            'name' => $location->name,
            "venue_uuid" => $location->venue_uuid,
            "category_id" => $location->category_id,
            "spots" => $location->spots,
            "capacity" => $location->capacity,
            "details" => $location->details
        ], $data[0]);

        $this->assertArraySubset([
            'id' => $event->id,
            'name' => $event->name,
        ], $data[0]['events'][0]);
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Location::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $locationsToFind = factory(Location::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/locations")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/locations?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($locationsToFind as $idx => $location) {
            $this->assertArraySubset([
                'uuid' => $location->uuid,
                'name' => $location->name,
                "venue_uuid" => $location->venue_uuid,
                "category_id" => $location->category_id,
                "spots" => $location->spots,
                "capacity" => $location->capacity,
                "details" => $location->details
            ], $data[$idx]);
        }

        $location = $locationsToFind->first();

        $data = $this
            ->json('get', "/api/foodfleet/locations?filter[uuid]=".$location->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $location->uuid,
            'name' => $location->name,
            "venue_uuid" => $location->venue_uuid,
            "category_id" => $location->category_id,
            "spots" => $location->spots,
            "capacity" => $location->capacity,
            "details" => $location->details
        ], $data[0]);
    }

    public function testCreatedItemWithWrongVenueUuid()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = [
            'venue_uuid' => 'aaa'
        ];
        $this
            ->json('POST', 'api/foodfleet/locations', $payload)
            ->assertStatus(422);
    }

    public function testCreatedItemWithWrongCategoryId()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = [
            'category_id' => 999
        ];
        $this
            ->json('POST', 'api/foodfleet/locations', $payload)
            ->assertStatus(422);
    }

    public function testCreatedItemWithMissingCategoryId()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Location::class)->make()->toArray();
        unset($payload['category_id']);
        $this
            ->json('POST', 'api/foodfleet/locations', $payload)
            ->assertStatus(422);
    }

    public function testCreatedItemWithMissingVenueUuid()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Location::class)->make()->toArray();
        unset($payload['venue_uuid']);
        $this
            ->json('POST', 'api/foodfleet/locations', $payload)
            ->assertStatus(422);
    }

    public function testCreatedItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Location::class)->make()->toArray();
        $data = $this
            ->json('POST', 'api/foodfleet/locations', $payload)
            ->assertStatus(201)
            ->json('data');

        $this->assertArraySubset([
            'name' => $payload['name'],
            'spots' => $payload['spots'],
            'capacity' => $payload['capacity'],
            'details' => $payload['details'],
            'venue_uuid' => $payload['venue_uuid'],
            'category_id' => $payload['category_id'],
        ], $data);

        $this->assertDatabaseHas('locations', [
            'uuid' => $data['uuid'],
            'name' => $payload['name'],
            'spots' => $payload['spots'],
            'capacity' => $payload['capacity'],
            'details' => $payload['details'],
            'venue_uuid' => $payload['venue_uuid'],
            'category_id' => $payload['category_id'],
        ]);
    }

    public function testDeleteNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this->json('DELETE', '/api/foodfleet/locations/abc123')
            ->assertStatus(404);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $location = factory(Location::class)->create();

        $this
            ->json('DELETE', '/api/foodfleet/locations/'.$location->uuid)
            ->assertStatus(204);

        $this->assertEquals(0, Location::where('uuid', $location->uuid)->count());
    }
}
