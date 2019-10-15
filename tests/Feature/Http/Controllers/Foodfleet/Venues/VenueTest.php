<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Venues;

use App\Models\Foodfleet\Venue;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VenueTest extends TestCase
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

        $venues = factory(Venue::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/venues")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($venues as $idx => $venue) {
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name
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

        factory(Venue::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $venuesToFind = factory(Venue::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/venues")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($venuesToFind as $idx => $venue) {
            $this->assertArraySubset([
                'uuid' => $venue->uuid,
                'name' => $venue->name
            ], $data[$idx]);
        }

        $data = $this
            ->json('get', "/api/foodfleet/venues?filter[uuid]=" . $venuesToFind->first()->uuid)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $venuesToFind->first()->uuid,
            'name' => $venuesToFind->first()->name
        ], $data[0]);
    }
}
