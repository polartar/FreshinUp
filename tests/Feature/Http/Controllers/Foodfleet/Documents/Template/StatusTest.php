<?php

namespace Tests\Feature\Http\Controllers\Foodfleet;

use App\Models\Foodfleet\Document\Template\Status as Model;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $items = factory(Model::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/document/template/statuses")
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
                'name' => $item->name,
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Model::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $itemsToFind = factory(Model::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/document/template/statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/document/template/statuses?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($itemsToFind as $idx => $eventStatus) {
            $this->assertArraySubset([
                'id' => $eventStatus->id,
                'name' => $eventStatus->name,
            ], $data[$idx]);
        }
    }
}
