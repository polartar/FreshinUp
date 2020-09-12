<?php

namespace Tests\Feature\Unit\Models;

use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
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
        $transaction = factory(Transaction::class)->create();

        $payment = factory(Payment::class)->create();
        $payment->transaction()->associate($transaction);
        $payment->device()->associate($device);
        $payment->paymentType()->associate($paymentType);
        $payment->save();

        $this->assertDatabaseHas('payments', [
            'uuid' => $payment->uuid,
            'device_uuid' => $device->uuid,
            'payment_type_uuid' => $paymentType->uuid,
            'transaction_uuid' => $transaction->uuid
        ]);
    }
}
