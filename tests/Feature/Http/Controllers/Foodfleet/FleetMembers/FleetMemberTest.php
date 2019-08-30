<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\FleetMembers;

use App\Models\Foodfleet\FleetMember;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FleetMemberTest extends TestCase
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

        $fleetMembers = factory(FleetMember::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/fleet-members")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($fleetMembers as $idx => $fleetMember) {
            $this->assertArraySubset([
                'uuid' => $fleetMember->uuid,
                'name' => $fleetMember->name
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

        factory(FleetMember::class, 5)->create([
            'name' => 'Not visibles'
        ]);

        $fleetMembersToFind = factory(FleetMember::class, 5)->create([
            'name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/fleet-members")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/fleet-members?filter[name]=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($fleetMembersToFind as $idx => $fleetMember) {
            $this->assertArraySubset([
                'uuid' => $fleetMember->uuid,
                'name' => $fleetMember->name
            ], $data[$idx]);
        }
    }
}
