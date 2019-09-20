<?php

namespace Tests\Feature\Unit\Models\Device;

use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Payment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
    public function testModel()
    {
        $payment = factory(Payment::class)->create();

        $device = factory(Device::class)->create();
        $device->payments()->save($payment);

        $this->assertDatabaseHas('devices', [
            'uuid' => $device->uuid,
        ]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'device_uuid' => $device->uuid
        ]);
    }
}
