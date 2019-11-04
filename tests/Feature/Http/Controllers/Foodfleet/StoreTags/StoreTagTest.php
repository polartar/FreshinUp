<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\StoreTags;

use App\Models\Foodfleet\StoreTag;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTagTest extends TestCase
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

        $storeTags = factory(StoreTag::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/store-tags")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($storeTags as $idx => $storeTag) {
            $this->assertArraySubset([
                'uuid' => $storeTag->uuid,
                'name' => $storeTag->name
            ], $data[$idx]);
        }
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

        factory(StoreTag::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $storeTagsToFind = factory(StoreTag::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/store-tags")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/store-tags?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($storeTagsToFind as $idx => $storeTag) {
            $this->assertArraySubset([
                'uuid' => $storeTag->uuid,
                'name' => $storeTag->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('get', "/api/foodfleet/store-tags?filter[uuid]=" . $storeTagsToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $storeTagsToFind->first()->uuid,
            'name' => $storeTagsToFind->first()->name
        ], $data[0]);
    }
}
