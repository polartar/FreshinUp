<?php


namespace App\Http\Resources\Foodfleet;

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
            "uuid" => $this->uuid,
            "name" => $this->name,
            "address" => $this->address,
            "address_line_1" => $this->address_line_1,
            "address_line_2" => $this->address_line_2,
            "locations" => Location::collection($this->whenLoaded('locations')),
        ];
    }
}
