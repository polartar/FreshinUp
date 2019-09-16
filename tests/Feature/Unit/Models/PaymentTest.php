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
        $customer = factory(Customer::class)->create();
        $paymentType = factory(PaymentType::class)->create();
        $item = factory(Item::class)->create();
        $event = factory(Event::class)->create();

        $payment = factory(Payment::class)->create();
        $payment->device()->associate($device);
        $payment->customer()->associate($customer);
        $payment->paymentType()->associate($paymentType);
        $payment->event()->associate($event);
        $payment->save();
        $payment->items()->sync([$item->uuid]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'device_uuid' => $device->uuid,
            'customer_uuid' => $customer->uuid,
            'payment_type_uuid' => $paymentType->uuid,
            'event_uuid' => $event->uuid
        ]);

        $this->assertDatabaseHas('payments_items', [
            'payment_uuid' => $payment->uuid,
            'item_uuid' => $item->uuid
        ]);
    }
}
