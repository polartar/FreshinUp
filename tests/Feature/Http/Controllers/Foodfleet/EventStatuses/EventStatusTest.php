<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\EventStatuses;

use App\User;
use App\Models\Foodfleet\EventStatus;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventStatusTest extends TestCase
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

        $eventStatuses = factory(EventStatus::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/event-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($eventStatuses as $idx => $eventStatus) {
            $this->assertArraySubset([
                'value' => $eventStatus->id,
                'text' => $eventStatus->name,
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

        factory(EventStatus::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventStatusesToFind = factory(EventStatus::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/event-statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/event-statuses?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($eventStatusesToFind as $idx => $eventStatus) {
            $this->assertArraySubset([
                'value' => $eventStatus->id,
                'text' => $eventStatus->name,
            ], $data[$idx]);
        }
    }
}
