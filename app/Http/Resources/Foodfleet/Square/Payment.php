<?php


namespace App\Http\Resources\Foodfleet\Square;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\PaymentStatus as PaymentStatusEnum;

class Payment extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "uuid" => $this->uuid,
            "name" => $this->name,
            "square_id" => $this->square_id,
            'description' => $this->description,
            'status_id' => $this->status_id ? intval($this->status_id) : PaymentStatusEnum::PENDING,
            'status' => new PaymentStatus($this->whenLoaded('status')),
            'amount_money' => $this->amount_money,
            'tip_money' => $this->tip_money,
            'due_date' => $this->due_date,
            'square_created_at' => $this->square_created_at,
            'store_uuid' => $this->store_uuid,
            'transaction_uuid' => $this->transaction_uuid,
            'payment_type' => $this->payment_type,
            'device_uuid' => $this->device_uuid,
            'payment_type_uuid' => $this->payment_type_uuid,
            'processing_fee_money' => $this->processing_fee_money
        ];
    }
}
