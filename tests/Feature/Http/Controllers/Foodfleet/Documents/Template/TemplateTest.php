<?php

namespace Tests\Feature\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Document\Template\Template as Model;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $items = factory(Model::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/document/templates")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($items as $idx => $item) {
            $this->assertArraySubset([
                'id' => $item->id,
                'uuid' => $item->uuid,
                'title' => $item->title,
                'content' => $item->content,
                'description' => $item->description,
                'status_id' => $item->status_id,
                'created_at' => str_replace('"', '', json_encode($item->created_at)),
                'updated_at' => str_replace('"', '', json_encode($item->updated_at))
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        factory(Model::class, 5)->create([
            'title' => 'Not visibles'
        ]);

        $itemsToFind = factory(Model::class, 5)->create([
            'title' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/document/templates")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/document/templates?filter[title]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($itemsToFind as $idx => $item) {
            $this->assertArraySubset([
                'id' => $item->id,
                'uuid' => $item->uuid,
                'title' => $item->title,
                'content' => $item->content,
                'description' => $item->description,
                'status_id' => $item->status_id,
                'created_at' => str_replace('"', '', json_encode($item->created_at)),
                'updated_at' => str_replace('"', '', json_encode($item->updated_at))
            ], $data[$idx]);
        }
    }

    public function testGetItemNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);

        $this
            ->json('GET', "/api/foodfleet/document/templates/abc123")
            ->assertStatus(404);
    }

    public function testGetItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $item = factory(Model::class)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/document/templates/" . $item->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertArraySubset([
            'id' => $item->id,
            'uuid' => $item->uuid,
            'title' => $item->title,
            'content' => $item->content,
            'description' => $item->description,
            'status_id' => $item->status_id,
            'created_at' => str_replace('"', '', json_encode($item->created_at)),
            'updated_at' => str_replace('"', '', json_encode($item->updated_at))
        ], $data);
    }

    public function testUpdateItemNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $payload = factory(Model::class)->make()->toArray();

        $this
            ->json('PUT', "/api/foodfleet/document/templates/abc123", $payload)
            ->assertStatus(404);
    }

    public function testUpdateItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $item = factory(Model::class)->create();
        $payload = factory(Model::class)->make()->toArray();

        $data = $this
            ->json('PUT', "/api/foodfleet/document/templates/" . $item->uuid, $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertArraySubset([
            'id' => $item->id,
            'uuid' => $item->uuid,
            'title' => $payload['title'],
            'content' => $payload['content'],
            'description' => $payload['description'],
            'status_id' => $payload['status_id'],
        ], $data);
    }

    public function testDeleteItemNonExisting()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $this->assertEquals(0, Model::where('uuid', 'abc123')->count());
        $this
            ->json('DELETE', "/api/foodfleet/document/templates/abc123")
            ->assertStatus(404);
        $this->assertEquals(0, Model::where('uuid', 'abc123')->count());
    }

    public function testDeleteItem()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $item = factory(Model::class)->create();

        $this->assertEquals(1, Model::where('uuid', $item['uuid'])->count());
        $this
            ->json('DELETE', "/api/foodfleet/document/templates/" . $item->uuid)
            ->assertStatus(204);
        $this->assertEquals(0, Model::where('uuid', $item['uuid'])->count());
    }
}
