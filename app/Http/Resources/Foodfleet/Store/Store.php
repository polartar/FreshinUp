<?php


namespace App\Http\Resources\Foodfleet\Store;

use FreshinUp\FreshBusForms\Http\Resources\Company\Company;
use FreshinUp\FreshBusForms\Http\Resources\User\User;
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
            "id" => $this->id,
            "uuid" => $this->uuid,
            "name" => $this->name,
            "status_id" => $this->status_id,
            "status" => new Status($this->whenLoaded('status')),
            "addresses" => Address::collection($this->addresses),
            "tags" => Tag::collection($this->whenLoaded('tags')),
            "event_stores" => EventStore::collection($this->whenLoaded('events')),
            "menuItems" => EventMenuItem::collection($this->whenLoaded('menuItems')),
            "documents" => Document::collection($this->whenLoaded('documents')),
            "messages" => Message::collection($this->whenLoaded('messages')),
            "square_id" => $this->square_id,
            "events_count" => $this->when($this->events_count, $this->events_count),
            "type" => new Type($this->whenLoaded('type')),
            'type_id' => $this->type_id,
            'supplier_uuid' => $this->supplier_uuid,
            "supplier" => new Company($this->whenLoaded('supplier')),
            'address_uuid' => $this->address_uuid,
            'contact_phone' => $this->contact_phone,
            'size' => $this->size,
            'image' => $this->image,
            "owner" => new User($this->whenLoaded('owner')),
            'owner_uuid' => $this->owner_uuid,
            'pos_system' => $this->pos_system,
            'state_of_incorporation' => $this->state_of_incorporation,
            "website" => $this->website,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'staff_notes' => $this->staff_notes,
            'area' => new StoreArea($this->whenLoaded('area')),
        ];
    }
}
