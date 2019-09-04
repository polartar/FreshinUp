<?php

namespace Tests\Feature\Unit\Models\Payment;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Location;
use App\Models\Foodfleet\Square\Customer;
use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Item;
use App\Models\Foodfleet\Square\Payment;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Laravel\Passport\Passport;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testPaymentModel()
    {
        $location = factory(Location::class)->create();
        $device = factory(Device::class)->create();
        $customer = factory(Customer::class)->create();
        $paymentType = factory(Payment::class)->create();
        $item = factory(Item::class)->create();
        $event = factory(Event::class)->create();

        $payment = factory(Payment::class)->create([
            'location_uuid' => $location->uuid,
            'device_uuid' => $device->uuid,
            'customer_uuid' => $customer->uuid,
            'payment_type_uuid' => $paymentType->uuid,
            'event_uuid' => $event->uuid,
        ]);
        $payment->items()->sync([$item->uuid]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'location_uuid' => $location->uuid,
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
