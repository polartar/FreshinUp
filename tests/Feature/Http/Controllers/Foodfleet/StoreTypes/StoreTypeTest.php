<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\StoreStatuses;

use App\User;
use App\Models\Foodfleet\StoreType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreTypeTest extends TestCase
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

        $storeTypes = factory(StoreType::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/store-types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($storeTypes as $idx => $storeType) {
            $this->assertArraySubset([
                'id' => $storeType->id,
                'name' => $storeType->name,
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

        factory(StoreType::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $storeTypesToFind = factory(StoreType::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/store-types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/store-types?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($storeTypesToFind as $idx => $storeType) {
            $this->assertArraySubset([
                'id' => $storeType->id,
                'name' => $storeType->name,
            ], $data[$idx]);
        }
    }
}
