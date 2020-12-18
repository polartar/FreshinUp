<?php

use App\Http\Resources\Foodfleet\Square\Payment as PaymentResource;
use App\Models\Foodfleet\Square\Payment as PaymentModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    public function testResource ()
    {
        $payment = factory(PaymentModel::class)->create();
        $resource = new PaymentResource($payment);
        $request = app()->make(Request::class);
        $expected = [
            'id' => $payment->id,
            'uuid' => $payment->uuid,
            'square_id' => $payment->square_id,
            'device_uuid' => $payment->device_uuid,
            'payment_type_uuid' => $payment->payment_type_uuid,
            'amount_money' => $payment->amount_money,
            'tip_money' => $payment->tip_money,
            'square_created_at' => $payment->square_created_at,
            'transaction_uuid' => $payment->transaction_uuid,
            'processing_fee_money' => $payment->processing_fee_money,
            'name' => $payment->name,
            'due_date' => $payment->due_date,
            'description' => $payment->description,
            'status_id' => $payment->status_id,
            'store_uuid' => $payment->store_uuid,
            'event_uuid' => $payment->event_uuid
        ];
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
