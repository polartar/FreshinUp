<?php


namespace App\Http\Resources\Foodfleet;

use FreshinUp\FreshBusForms\Http\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Venue extends JsonResource
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
            "address" => $this->address,
            "address_line_1" => $this->address_line_1,
            "address_line_2" => $this->address_line_2,
            "locations" => Location::collection($this->whenLoaded('locations')),
            "status_id" => $this->status_id,
            "status" => new VenueStatus($this->whenLoaded('status')),
            "owner" => new User($this->whenLoaded('owner')),
            'owner_uuid' => $this->owner_uuid,
            'created_at' => $this->created_at,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
