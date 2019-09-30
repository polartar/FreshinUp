<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Events;

use App\Models\Foodfleet\Event;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
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

        $events = factory(Event::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($events as $idx => $event) {
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name
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

        factory(Event::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventsToFind = factory(Event::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/events")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/events?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($eventsToFind as $idx => $event) {
            $this->assertArraySubset([
                'uuid' => $event->uuid,
                'name' => $event->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('get', "/api/foodfleet/events?filter[uuid]=" . $eventsToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $eventsToFind->first()->uuid,
            'name' => $eventsToFind->first()->name
        ], $data[0]);
    }
}
