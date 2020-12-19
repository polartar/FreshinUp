<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PaymentStatusTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var PaymentStatus $item */
        $item = factory(PaymentStatus::class)->create();

        $this->assertDatabaseHas('payment_statuses', [
            'id' => $item->id
        ]);

        $payment = factory(Payment::class)->create([
            'status_id' => $item->id
        ]);
        $this->assertEquals($item->id, $payment->status_id);
        $this->assertEquals($payment->uuid, $item->payments->first()->uuid);
    }
}
