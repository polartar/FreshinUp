<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Staffs;

use App\Models\Foodfleet\Square\Staff;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StaffTest extends TestCase
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

        $staffs = factory(Staff::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/staffs")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($staffs as $idx => $staff) {
            $this->assertArraySubset([
                'uuid' => $staff->uuid,
                'name' => $staff->first_name . ' ' . $staff->last_name,
                'first_name' => $staff->first_name,
                'last_name' => $staff->last_name,
                'square_id' => $staff->square_id
            ], $data[$idx]);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithTermFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Staff::class, 5)->create([
            'first_name' => 'Not visibles',
            'last_name' => 'Not visibles'
        ]);

        $staffsToFind = factory(Staff::class, 5)->create([
            'first_name' => 'To find'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/staffs")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(10, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/staffs?term=find")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));

        foreach ($staffsToFind as $idx => $staff) {
            $this->assertArraySubset([
                'uuid' => $staff->uuid,
                'name' => $staff->first_name . ' ' . $staff->last_name,
                'first_name' => $staff->first_name,
                'last_name' => $staff->last_name,
                'square_id' => $staff->square_id
            ], $data[$idx]);
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListWithSquareIdFilter()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);

        factory(Staff::class)->create([
            'square_id' => '111222',
        ]);

        $staffToFind = factory(Staff::class)->create([
            'square_id' => '333222'
        ]);

        $data = $this
            ->json('get', "/api/foodfleet/staffs")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(2, count($data));


        $data = $this
            ->json('get', "/api/foodfleet/staffs?filter[square_id]=333")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(1, count($data));

        $this->assertArraySubset([
            'uuid' => $staffToFind->uuid,
            'name' => $staffToFind->first_name . ' ' . $staffToFind->last_name,
            'first_name' => $staffToFind->first_name,
            'last_name' => $staffToFind->last_name,
            'square_id' => $staffToFind->square_id
        ], $data[0]);
    }
}
