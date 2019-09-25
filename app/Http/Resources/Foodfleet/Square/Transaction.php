<?php


namespace App\Http\Resources\Foodfleet\Square;

use App\Http\Resources\Foodfleet\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Transaction extends JsonResource
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
            "uuid" => $this->uuid,
            "square_id" => $this->square_id,
            "square_created_at" => $this->square_created_at,
            "square_updated_at" => $this->square_updated_at,
            "total_money" => $this->total_money,
            "total_tax_money" => $this->total_tax_money,
            "total_discount_money" => $this->total_discount_money,
            "total_service_charge_money" => $this->total_service_charge_money,
            "event" => new Event($this->whenLoaded('event')),
            "customer" => new Customer($this->whenLoaded('customer')),
            "items" => TransactionItem::collection($this->whenLoaded('items')),
        ];
    }
}
