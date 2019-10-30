<?php


namespace App\Http\Resources\Foodfleet;

use FreshinUp\FreshBusForms\Http\Resources\User\User;
use FreshinUp\FreshBusForms\Http\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foodfleet\Store\Store;

class Event extends JsonResource
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
            "status_id" => $this->status_id,
            "status" => new EventStatus($this->whenLoaded('status')),
            "manager" => new User($this->whenLoaded('manager')),
            "location" => new Location($this->whenLoaded('location')),
            "event_tags" => EventTag::collection($this->whenLoaded('eventTags')),
            "host" => new Company($this->whenLoaded('host')),
            "stores" => Store::collection($this->whenLoaded('stores')),
            "start_at" => $this->start_at,
            "end_at" => $this->end_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at
        ];
    }
}
