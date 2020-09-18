<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\VenueStatuses;

use App\Models\Foodfleet\VenueStatus;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VenueStatusTest extends TestCase
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

        $venueStatuses = factory(VenueStatus::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/venue/statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($venueStatuses as $idx => $venueStatus) {
            $this->assertArraySubset([
                'id' => $venueStatus->id,
                'name' => $venueStatus->name,
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

        factory(VenueStatus::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $venueStatusesToFind = factory(VenueStatus::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/venue/statuses")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/venue/statuses?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($venueStatusesToFind as $idx => $venueStatus) {
            $this->assertArraySubset([
                'id' => $venueStatus->id,
                'name' => $venueStatus->name,
            ], $data[$idx]);
        }
    }
}
