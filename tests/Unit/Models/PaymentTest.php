<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Square\Device;
use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentStatus;
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
            'name' => $payment->name,
            'created_at' => $payment->created_at,
            'updated_at' => $payment->updated_at,
            'deleted_at' => $payment->deleted_at,
            'device_uuid' => $payment->device_uuid,
            'payment_type_uuid' => $payment->payment_type_uuid,
            'amount_money' => $payment->amount_money,
            'tip_money' => $payment->tip_money,
            'square_created_at' => $payment->square_created_at,
            'transaction_uuid' => $payment->transaction_uuid,
            'processing_fee_money' => $payment->processing_fee_money,
            'due_date' => $payment->due_date,
            'description' => $payment->description,
            'status_id' => $payment->status_id

        ]);

        // Relations
        $this->assertEquals($payment->status_id, $payment->status->id);
        $this->assertEquals($payment->device_uuid, $payment->device->uuid);
        $this->assertEquals($payment->payment_type_uuid, $payment->paymentType->uuid);
        $this->assertEquals($payment->transaction_uuid, $payment->transaction->uuid);
    }
}
