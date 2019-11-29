<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\StoreStatuses;

use App\User;
use App\Models\Foodfleet\StoreStatus;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StoreStatusTest extends TestCase
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

        $storeStatuses = factory(StoreStatus::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/store-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($storeStatuses as $idx => $storeStatus) {
            $this->assertArraySubset([
                'id' => $storeStatus->id,
                'name' => $storeStatus->name,
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

        factory(StoreStatus::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $storeStatusesToFind = factory(StoreStatus::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/store-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/store-statuses?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($storeStatusesToFind as $idx => $storeStatus) {
            $this->assertArraySubset([
                'id' => $storeStatus->id,
                'name' => $storeStatus->name,
            ], $data[$idx]);
        }
    }
}
