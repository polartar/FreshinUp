<?php

namespace Tests\Feature\Http\Controllers\Foodfleet\Square\Devices;

use App\Models\Foodfleet\Square\Device;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeviceTest extends TestCase
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

        $devices = factory(Device::class, 5)->create();

        $data = $this
            ->json('get', "/api/foodfleet/devices")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ])
            ->json('data');

        $this->assertNotEmpty($data);
        $this->assertEquals(5, count($data));
        foreach ($devices as $idx => $device) {
            $this->assertArraySubset([
                'uuid' => $device->uuid,
                'name' => $device->name
            ], $data[$idx]);
        }
    }
}
