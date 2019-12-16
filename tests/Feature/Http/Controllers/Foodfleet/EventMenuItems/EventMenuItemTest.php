<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\EventMenuItems;

use App\User;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use App\Models\Foodfleet\EventMenuItem;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventMenuItemTest extends TestCase
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

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $items = factory(EventMenuItem::class, 3)->create([
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/event-menu-items")
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
                'item' => $item->item
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $event1 = factory(Event::class)->create();
        $store1 = factory(Store::class)->create();
        factory(EventMenuItem::class, 3)->create([
            'item' => 'item1',
            'event_uuid' => $event1->uuid,
            'store_uuid' => $store1->uuid
        ]);

        $event2 = factory(Event::class)->create();
        $store2 = factory(Store::class)->create();
        factory(EventMenuItem::class, 2)->create([
            'item' => 'item2',
            'event_uuid' => $event2->uuid,
            'store_uuid' => $store2->uuid
        ]);

        $url = 'api/foodfleet/event-menu-items?filter[event_uuid]=' . $event1->uuid;
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');
        $this->assertCount(3, $result);
        $this->assertEquals('item1', $result[0]['item']);

        $url = 'api/foodfleet/event-menu-items?filter[store_uuid]=' . $store2->uuid;
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');
        $this->assertCount(2, $result);
        $this->assertEquals('item2', $result[0]['item']);

        $url = 'api/foodfleet/event-menu-items?filter[item]=item2';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');
        $this->assertCount(2, $result);
        $this->assertEquals('item2', $result[0]['item']);
    }

    public function testGetNewItemRecommendation()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        
        $data = $this->json('GET', 'api/foodfleet/event-menu-items/new')
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

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();

        $data = $this
            ->json('POST', 'api/foodfleet/event-menu-items', [
                'item' => 'create menu item test',
                'servings' => 5,
                'cost' => 123,
                'description' => 'This is special food for you',
                'event_uuid' => $event->uuid,
                'store_uuid' => $store->uuid
            ])
            ->assertStatus(201)
            ->json('data');

        $url = 'api/foodfleet/event-menu-items?filter[uuid]=' . $data['uuid'] . '&include=event,store';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('create menu item test', $result[0]['item']);
        $this->assertEquals($event->uuid, $result[0]['event']['uuid']);
        $this->assertEquals($store->uuid, $result[0]['store']['uuid']);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();
        
        Passport::actingAs($user);

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $item = factory(EventMenuItem::class)->create([
            'item' => 'item1',
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('PUT', 'api/foodfleet/event-menu-items/' . $item->uuid, [
                'item' => 'create menu item test',
                'servings' => 5,
                'cost' => 123,
                'description' => 'This is special food for you'
            ])
            ->assertStatus(200)
            ->json('data');

        $url = 'api/foodfleet/event-menu-items/' . $item->uuid . '?include=event,store';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals('create menu item test', $result['item']);
        $this->assertEquals('This is special food for you', $result['description']);
        $this->assertEquals(5, $result['servings']);
        $this->assertEquals(123, $result['cost']);
        $this->assertEquals($event->uuid, $result['event']['uuid']);
        $this->assertEquals($store->uuid, $result['store']['uuid']);
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $item = factory(EventMenuItem::class)->create([
            'item' => 'item1',
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid
        ]);

        $data = $this
            ->json('GET', 'api/foodfleet/event-menu-items/' . $item->uuid)
            ->assertStatus(200)
            ->json('data');

        $this->assertEquals($item->uuid, $data['uuid']);

        $this->json('DELETE', 'api/foodfleet/event-menu-items/' . $item->uuid)
            ->assertStatus(204);

        $this->json('GET', 'api/foodfleet/event-menu-items/' . $item->uuid)
            ->assertStatus(404);
    }
}
