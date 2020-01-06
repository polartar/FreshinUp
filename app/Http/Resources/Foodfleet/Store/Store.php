<?php


namespace App\Http\Resources\Foodfleet\Store;

use App\Http\Resources\Foodfleet\Store\Type;
use FreshinUp\FreshBusForms\Http\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use FreshinUp\FreshBusForms\Http\Resources\Address\Address;
use App\Http\Resources\Foodfleet\EventMenuItem;
use App\Http\Resources\Foodfleet\Message;
use App\Http\Resources\Foodfleet\Document\Document;

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
            "tags" => Tag::collection($this->whenLoaded('tags')),
            "event_stores" => EventStore::collection($this->whenLoaded('events')),
            "menuItems" => EventMenuItem::collection($this->whenLoaded('menuItems')),
            "documents" => Document::collection($this->whenLoaded('documents')),
            "messages" => Message::collection($this->whenLoaded('messages')),
            "square_id" => $this->square_id,
            "supplier" => new Company($this->whenLoaded('supplier')),
            "events_count" => $this->when($this->events_count, $this->events_count),
            "type" => new Type($this->whenLoaded('type')),
            "website" => $this->website,
            "contact_phone" => $this->contact_phone,
            "size" => $this->size,
            "image" => $this->image,

        ];
    }
}
