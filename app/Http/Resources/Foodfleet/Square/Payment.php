<?php


namespace App\Http\Resources\Foodfleet\Square;

use App\Http\Resources\Foodfleet\Event;
use App\Http\Resources\Foodfleet\Store\Store;
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
            'amount_money' => round($this->amount_money, 2),
            'tip_money' => round($this->tip_money, 2),
            'due_date' => $this->due_date,
            'processing_fee_money' => round($this->processing_fee_money, 2),
            'square_created_at' => $this->square_created_at,
            'store_uuid' => $this->store_uuid,
            'store' => new Store($this->whenLoaded('store')),
            'transaction_uuid' => $this->transaction_uuid,
            'device_uuid' => $this->device_uuid,
            'device' => new Device($this->whenLoaded('device')),
            'payment_type_uuid' => $this->payment_type_uuid,
            'payment_type' => new PaymentType($this->whenLoaded('payment_type')),
            'event_uuid' => $this->event_uuid,
            'event' => new Event($this->whenLoaded('event')),
        ];
    }
}
