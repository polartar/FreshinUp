<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\MenuItems;

use App\User;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\MenuItem;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuItemTest extends TestCase
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

        $store = factory(Store::class)->create();

       
        $items = factory(MenuItem::class, 3)->create([
            'store_uuid' => $store->uuid,
            'title' => 'A',
        ]);
        
       
        $data = $this
            ->json('get', "/api/foodfleet/stores/{$store->uuid}/menu-items")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        foreach ($items as $idx => $item) {
            $this->assertArraySubset([
                'uuid' => $item->uuid,
                'title' => 'A'
            ], $data[$idx]);
        }
    }

}
