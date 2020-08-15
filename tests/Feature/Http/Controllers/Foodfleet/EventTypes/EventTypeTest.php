<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\EventTypes;

use App\Models\Foodfleet\EventType;
use App\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTypeTest extends TestCase
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

        $eventTypes = factory(EventType::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/event-types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($eventTypes as $idx => $eventType) {
            $this->assertArraySubset([
                'id' => $eventType->id,
                'name' => $eventType->name,
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

        factory(EventType::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $eventTypesToFind = factory(EventType::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/event-types")
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
        echo $data;
        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($eventTypesToFind as $idx => $eventType) {
            $this->assertArraySubset([
                'id' => $eventType->id,
                'name' => $eventType->name,
            ], $data[$idx]);
        }
    }
}
