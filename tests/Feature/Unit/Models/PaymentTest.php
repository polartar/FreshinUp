<?php

namespace Tests\Feature\Unit\Models\Payment;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LocationTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testModel()
    {
        $device = factory(Device::class)->create();
        $paymentType = factory(PaymentType::class)->create();

        $payment = factory(Payment::class)->create();
        $payment->device()->associate($device);
        $payment->paymentType()->associate($paymentType);
        $payment->save();

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'device_uuid' => $device->uuid,
            'payment_type_uuid' => $paymentType->uuid,
        ]);
    }
}
