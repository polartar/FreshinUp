<?php


namespace Tests\Feature\Http\Controllers\Foodfleet\EventHistories;

use App\Http\Resources\Foodfleet\EventStatus as EventStatusResource;
use App\Models\Foodfleet\Event as EventModel;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\EventStatus;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;

class EventHistoryTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;
    protected function setUp(): void
    {
        parent::setUp();
        EventHistory::unsetEventDispatcher();
    }

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
                'description' => EventStatusResource::getDescriptionFor($eventHistory->status_id),
                'date' => $eventHistory->date->format('Y-m-d H:i:s')
            ], $data[$idx]);
        }
    }

    public function testGetListWithFilters()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        $event = factory(EventModel::class)->create();
        factory(EventHistory::class, 5)->create();
        $eventHistoriesToFind = factory(EventHistory::class, 3)->create([
            'event_uuid' => $event->uuid
        ]);

        $data = $this
            ->json('get', '/api/foodfleet/event/status/histories?'
                .'filter[event_uuid]='.$event->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(3, count($data));
        foreach ($eventHistoriesToFind as $index => $history) {
            $this->assertArraySubset([
                'id' => $history->id,
                'status_id' => $history->status_id,
                'event_uuid' => $history->event_uuid,
                'description' => EventStatusResource::getDescriptionFor($history->status_id),
                'date' => $history->date
            ], $data[$index]);
        }
    }

    public function testGetListWithInclude()
    {
        $user = factory(User::class)->create();
        Passport::actingAs($user);
        /** @var EventHistory[] $eventHistories */
        $eventHistories = factory(EventHistory::class, 5)->create();

        $response = $this
            ->json('get', '/api/foodfleet/event/status/histories?'
                .'include=status');
        $this->assertNotExceptionResponse($response);
        $data = $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($eventHistories as $idx => $eventHistory) {
            /** @var EventStatus $status */
            $status = $eventHistory->status;
            $this->assertArraySubset([
                'id' => $eventHistory->id,
                'status_id' => (int) $eventHistory->status_id,
                'event_uuid' => $eventHistory->event_uuid,
                'description' => EventStatusResource::getDescriptionFor($eventHistory->status_id),
                'date' => $eventHistory->date->format('Y-m-d H:i:s')
            ], $data[$idx]);
            $this->assertArrayHasKey('status', $data[$idx]);
            $this->assertArraySubset([
                'id' => $status->id,
                'name' => $status->name,
                'color' => EventStatusResource::getColorFor($status->id)
            ], $data[$idx]['status']);
        }
    }
}
