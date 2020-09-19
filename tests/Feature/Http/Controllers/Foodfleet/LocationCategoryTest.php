<?php

namespace Tests\Feature\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\LocationCategory;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LocationCategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $categories = factory(LocationCategory::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/location/categories")
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($categories as $idx => $category) {
            $this->assertArraySubset([
                'id' => $category->id,
                'name' => $category->name,
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(LocationCategory::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $categoriesToFind = factory(LocationCategory::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/location/categories")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/location/categories?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($categoriesToFind as $idx => $category) {
            $this->assertArraySubset([
                'id' => $category->id,
                'name' => $category->name,
            ], $data[$idx]);
        }
    }



    public function testGetListIncludingLocations()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $category = factory(LocationCategory::class)->create();
        $location = factory(Location::class)->create([
            'category_id' => $category->id
        ]);

        $data = $this
            ->json('GET', "/api/foodfleet/location/categories?include=locations")
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'id' => $category->id,
            'name' => $category->name,
        ], $data[0]);

        $this->assertArraySubset([
            'uuid' => $location->uuid,
            'name' => $location->name,
            "venue_uuid" => $location->venue_uuid,
            "category_id" => $location->category_id,
            "spots" => $location->spots,
            "capacity" => $location->capacity,
            "details" => $location->details
        ], $data[0]['locations'][0]);
    }
}
