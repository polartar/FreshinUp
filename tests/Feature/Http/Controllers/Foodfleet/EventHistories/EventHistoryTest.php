<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\EventHistories;

use App\Http\Resources\Foodfleet\EventStatus;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventHistoryTest extends TestCase
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

        $eventHistories = factory(EventHistory::class, 5)->create();

        $data = $this
            ->json('GET', "/api/foodfleet/event/status/histories")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($eventHistories as $idx => $eventHistory) {
            $this->assertArraySubset([
                'id' => $eventHistory->id,
                'status_id' => $eventHistory->status_id,
                'event_uuid' => $eventHistory->event_uuid,
                'description' => $eventHistory->description,
                'date' => $eventHistory->date,
                'completed' => $eventHistory->completed
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $event = factory(Event::class)->create();
        factory(EventHistory::class, 5)->create();
        $eventHistoryToFind = factory(EventHistory::class)->create([
            'event_uuid' => $event->uuid
        ]);

        $data = $this
            ->json('get', '/api/foodfleet/event/status/histories?'
            . 'filter[event_uuid]=' . $event->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $this->assertArraySubset([
            'id' => $eventHistoryToFind->id,
            'status_id' => $eventHistoryToFind->status_id,
            'event_uuid' => $eventHistoryToFind->event_uuid,
            'description' => $eventHistoryToFind->description,
            'date' => $eventHistoryToFind->date,
            'completed' => $eventHistoryToFind->completed
        ], $data[0]);
    }

    public function testGetListWithInclude()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $eventHistories = factory(EventHistory::class, 5)->create();

        $response = $this
            ->json('get', '/api/foodfleet/event/status/histories?'
            . 'include=status');
        $this->assertNotExceptionResponse($response);
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));
        $request = app()->make(Request::class);
        foreach ($eventHistories as $idx => $eventHistory) {
            $this->assertArraySubset([
                'id' => $eventHistory->id,
                'status_id' => $eventHistory->status_id,
                'status' => (new EventStatus($eventHistory->status))->toArray($request),
                'event_uuid' => $eventHistory->event_uuid,
                'description' => $eventHistory->description,
                'date' => $eventHistory->date,
                'completed' => $eventHistory->completed
            ], $data[$idx]);
        }
    }
}
