<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Documents;

use App\User;
use App\Models\Foodfleet\Message;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use FreshinUp\FreshBusForms\Models\Company\Company;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Str;

class MessageTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $event->stores()->sync([$store->uuid]);

        $messages = factory(Message::class, 5)->create([
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid,
            'created_by_uuid' => $user->uuid
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/messages")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertCount(5, $data);
        foreach ($messages as $idx => $message) {
            $this->assertArraySubset([
                'uuid' => $message->uuid,
                'content' => $message->content
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $event1 = factory(Event::class)->create();
        $store1 = factory(Store::class)->create();
        $event1->stores()->sync([$store1->uuid]);

        factory(Message::class, 2)->create([
            'content' => 'message1',
            'event_uuid' => $event1->uuid,
            'store_uuid' => $store1->uuid,
            'created_by_uuid' => $user->uuid
        ]);

        $event2 = factory(Event::class)->create();
        $store2 = factory(Store::class)->create();
        $event2->stores()->sync([$store2->uuid]);

        factory(Message::class, 3)->create([
            'content' => 'message2',
            'event_uuid' => $event2->uuid,
            'store_uuid' => $store2->uuid,
            'created_by_uuid' => $user->uuid
        ]);

        $url = 'api/foodfleet/messages?filter[event_uuid]=' . $event1->uuid;
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertCount(2, $result);
        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('message1', $result[0]['content']);

        $url = 'api/foodfleet/messages?filter[store_uuid]=' . $store2->uuid;
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');
        
        $this->assertCount(3, $result);
        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('message2', $result[0]['content']);
    }

    public function testGetListWithIncludes()
    {
        $user = factory(User::class)->create();
        $user1 = factory(User::class)->create();

        Passport::actingAs($user);

        $event = factory(Event::class)->create();
        $store = factory(Store::class)->create();
        $event->stores()->sync([$store->uuid]);

        factory(Message::class)->create([
            'content' => 'message',
            'event_uuid' => $event->uuid,
            'store_uuid' => $store->uuid,
            'recipient_uuid' => $user1->uuid,
            'created_by_uuid' => $user->uuid
        ]);

        $url = 'api/foodfleet/messages?include=owner';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertCount(1, $result);
        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('message', $result[0]['content']);
        $this->assertEquals($user->uuid, $result[0]['owner']['uuid']);

        $url = 'api/foodfleet/messages?include=recipient';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertCount(1, $result);
        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('message', $result[0]['content']);
        $this->assertEquals($user1->uuid, $result[0]['recipient']['uuid']);
    }

    public function testGetNewItemRecommendation()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $data = $this->json('GET', 'api/foodfleet/messages/new')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [],
            ])
            ->json('data');

        $this->assertEquals($data['content'], null);
    }

    public function testCreatedItem()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        $event = factory(Event::class)->create([
            'manager_uuid' => $user->uuid
        ]);
        
        $user1 = factory(User::class)->create();
        $supplier = factory(Company::class)->create([
            'users_id' => $user1->id
        ]);
        $store = factory(Store::class)->create([
            'supplier_uuid' => $supplier->uuid
        ]);
        
        $event->stores()->sync([$store->uuid]);

        $data = $this
            ->json('POST', 'api/foodfleet/messages', [
                'content' => 'create message test',
                'event_uuid' => $event->uuid,
                'store_uuid' => $store->uuid
            ])
            ->assertStatus(201)
            ->json('data');

        $url = 'api/foodfleet/messages?filter[event_uuid]=' . $event->uuid . '&include=owner,recipient';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('create message test', $result[0]['content']);
        $this->assertEquals($user->uuid, $result[0]['owner']['uuid']);
        $this->assertEquals($user1->uuid, $result[0]['recipient']['uuid']);

        $url = 'api/foodfleet/messages?filter[store_uuid]=' . $store->uuid . '&include=owner,recipient';
        $result = $this->json('GET', $url)
            ->assertStatus(200)
            ->json('data');

        $this->assertNotEmpty($result[0]['created_at']);
        $this->assertEquals('create message test', $result[0]['content']);
        $this->assertEquals($user->uuid, $result[0]['owner']['uuid']);
        $this->assertEquals($user1->uuid, $result[0]['recipient']['uuid']);
    }
}
