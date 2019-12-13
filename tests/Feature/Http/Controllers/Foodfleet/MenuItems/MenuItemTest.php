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
    public function testCreatedItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();

        $data = $this
            ->json('POST', 'api/foodfleet/stores/{$store->uuid}/menu-items', [
                'title' => 'create menu item test',
                'servings' => 5,
                'cost' => 123,
                'description' => 'This is special food for you',
                'store_uuid' => $store->uuid
            ])
            ->assertStatus(201)
            ->json('data');

        $url = 'api/foodfleet/stores/{$store->uuid}/menu-items?filter[uuid]=' . $data['uuid'] . '&include=store';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('create menu item test', $result[0]['title']);
        $this->assertEquals($store->uuid, $result[0]['store']['uuid']);
    }
    public function testUpdateItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();
        $item = factory(MenuItem::class)->create([
            'title' => 'title1',
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('PUT', 'api/foodfleet/stores/{$store->uuid}/menu-items/' . $item->uuid, [
                'title' => 'create menu title test',
                'servings' => 5,
                'cost' => 123,
                'description' => 'This is special food for you'
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/stores/{$store->uuid}/menu-items/' . $item->uuid . '?include=store';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('create menu title test', $result['title']);
        $this->assertEquals('This is special food for you', $result['description']);
        $this->assertEquals(5, $result['servings']);
        $this->assertEquals(123, $result['cost']);
        $this->assertEquals($store->uuid, $result['store']['uuid']);
    }
    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();
        $item = factory(MenuItem::class)->create([
            'title' => 'item1',
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('GET', 'api/foodfleet/stores/{$store->uuid}/menu-items/' . $item->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals($item->uuid, $data['uuid']);

        $this->json('DELETE', 'api/foodfleet/stores/{$store->uuid}/menu-items/' . $item->uuid)
            ->assertStatus(204);

        $this->json('GET', 'api/foodfleet/stores/{$store->uuid}/menu-items/' . $item->uuid)
            ->assertStatus(404);
    }
}
