<?php

namespace Tests\Feature\Unit\Models\PaymentType;

use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTypeTest extends TestCase
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

        $paymentType = factory(PaymentType::class)->create();
        $paymentType->payments()->save($payment);

        $this->assertDatabaseHas('payment_types', [
            'uuid' => $paymentType->uuid,
        ]);

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'payment_type_uuid' => $paymentType->uuid
        ]);
    }
}
