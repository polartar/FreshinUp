<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Menus;

use App\User;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\Menu;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuTest extends TestCase
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
        $menus = factory(Menu::class, 3)->create([
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/menus")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
        foreach ($menus as $idx => $menu) {
            $this->assertArraySubset([
                'uuid' => $menu->uuid,
                'item' => $menu->item
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store1 = factory(Store::class)->create();
        factory(Menu::class, 2)->create([
            'item' => 'item1',
            'store_uuid' => $store1->uuid
        ]);

        $store2 = factory(Store::class)->create();
        factory(Menu::class, 3)->create([
            'item' => 'item2',
            'store_uuid' => $store2->uuid
        ]);

        $url = 'api/foodfleet/menus?filter[store_uuid]=' . $store1->uuid;
        $response = $this->json('GET', $url);
        $this->assertNotExceptionResponse($response);
        $result = $response->json('data');
        $this->assertCount(2, $result);
        $this->assertEquals('item1', $result[0]['item']);

        $url = 'api/foodfleet/menus?filter[item]=item2';
        $response = $this->json('GET', $url);
        $this->assertNotExceptionResponse($response);
        $result = $response->json('data');
        $this->assertCount(3, $result);
        $this->assertEquals('item2', $result[0]['item']);
    }

    public function testGetListWithTerm()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $store1 = factory(Store::class)->create();
        factory(Menu::class, 2)->create([
            'item' => 'qwertyui item1',
            'store_uuid' => $store1->uuid
        ]);

        $store2 = factory(Store::class)->create();
        $menus = factory(Menu::class, 3)->create([
            'item' => 'jdhf item2',
            'store_uuid' => $store2->uuid
        ]);

        $response = $this->json('GET', 'api/foodfleet/menus?q=item2');
        $this->assertNotExceptionResponse($response);
        $data = $response->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(3, $data);
    }

    public function testGetNewItemRecommendation()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        
        $data = $this->json('GET', 'api/foodfleet/menus/new')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');
        $this->assertEquals($data['item'], null);
    }

    public function testCreatedItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();

        $data = $this
            ->json('POST', 'api/foodfleet/menus', [
                'item' => 'create menu test',
                'category' => 'Salad',
                'description' => 'This is special food for you',
                'street_price' => 123,
                'store_uuid' => $store->uuid
            ])
            ->assertStatus(201)
            ->json('data');

        $url = 'api/foodfleet/menus?filter[uuid]=' . $data['uuid'] . '&include=store';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('create menu test', $result[0]['item']);
        $this->assertEquals($store->uuid, $result[0]['store']['uuid']);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();
        
        Passport::actingAs($user);

        $store = factory(Store::class)->create();
        $menu = factory(Menu::class)->create([
            'item' => 'item1',
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('PUT', 'api/foodfleet/menus/' . $menu->uuid, [
                'item' => 'create menu test',
                'category' => 'Salad',
                'description' => 'This is special food for you',
                'street_price' => 1234
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/menus/' . $menu->uuid . '?include=store';

        $response = $this->json('GET', $url);
        $this->assertNotExceptionResponse($response);
        $result = $response->json('data');

        $this->assertEquals('create menu test', $result['item']);
        $this->assertEquals('Salad', $result['category']);
        $this->assertEquals('This is special food for you', $result['description']);
        $this->assertEquals(1234, $result['street_price']);
        $this->assertEquals($store->uuid, $result['store']['uuid']);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $store = factory(Store::class)->create();
        $menu = factory(Menu::class)->create([
            'item' => 'item1',
            'store_uuid' => $store->uuid
        ]);

        $response = $this->json('GET', 'api/foodfleet/menus/' . $menu->uuid);
        $this->assertNotExceptionResponse($response);
        $data = $response->json('data');

        $this->assertEquals($menu->uuid, $data['uuid']);

        $this->json('DELETE', 'api/foodfleet/menus/' . $menu->uuid)
            ->assertStatus(204);

        $this->json('GET', 'api/foodfleet/menus/' . $menu->uuid)
            ->assertStatus(404);
    }
}
