<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Square\Payment;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        $payment = factory(Payment::class)->create();

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
            'status_id' => $payment->status_id,
            'store_uuid' => $payment->store_uuid,
            'event_uuid' => $payment->event_uuid,
        ]);

        // Relations
        $this->assertEquals($payment->status_id, $payment->status->id);
        $this->assertEquals($payment->device_uuid, $payment->device->uuid);
        $this->assertEquals($payment->payment_type_uuid, $payment->type->uuid);
        $this->assertEquals($payment->transaction_uuid, $payment->transaction->uuid);
        $this->assertEquals($payment->store_uuid, $payment->store->uuid);
        $this->assertEquals($payment->event_uuid, $payment->event->uuid);
    }
}
