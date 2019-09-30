<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\EventTags;

use App\Models\Foodfleet\EventTag;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTagTest extends TestCase
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

        $eventTags = factory(EventTag::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/event-tags")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($eventTags as $idx => $eventTag) {
            $this->assertArraySubset([
                'uuid' => $eventTag->uuid,
                'name' => $eventTag->name
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

        factory(EventTag::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventTagsToFind = factory(EventTag::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/event-tags")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/event-tags?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($eventTagsToFind as $idx => $eventTag) {
            $this->assertArraySubset([
                'uuid' => $eventTag->uuid,
                'name' => $eventTag->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('get', "/api/foodfleet/event-tags?filter[uuid]=" . $eventTagsToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $eventTagsToFind->first()->uuid,
            'name' => $eventTagsToFind->first()->name
        ], $data[0]);
    }
}
