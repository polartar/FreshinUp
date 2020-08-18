<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\EventTypes;

use App\Models\Foodfleet\EventType;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTypeTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testGetList()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $eventTypes = factory(EventType::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/event/types")
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

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(EventType::class, 5)->create();
        $eventTypeToFind = factory(EventType::class)->create([
            'name' => 'some random name'
        ]);
        $data = $this
            ->json('get', "/api/foodfleet/event/types")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(6, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/event/types?filter[name]=".$eventTypeToFind->name)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');
        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'id' => $eventTypeToFind->id,
            'name' => $eventTypeToFind->name,
        ], $data[0]);
    }
}
