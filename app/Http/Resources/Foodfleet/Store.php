<?php


namespace App\Http\Resources\Foodfleet;

use FreshinUp\FreshBusForms\Http\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use FreshinUp\FreshBusForms\Http\Resources\Address\Address;

class Store extends JsonResource
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
            "name" => $this->name,
            "status" => $this->status,
            "addresses" => Address::collection($this->addresses),
            "tags" => StoreTag::collection($this->whenLoaded('tags')),
            "event_stores" => EventStore::collection($this->whenLoaded('events')),
            "menuItems" => EventMenuItem::collection($this->whenLoaded('menuItems')),
            "documents" => Document\Document::collection($this->whenLoaded('documents')),
            "messages" => Message::collection($this->whenLoaded('messages')),
            "square_id" => $this->square_id,
            "supplier" => new Company($this->whenLoaded('supplier'))
        ];
    }
}
