<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Locations;

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
}
