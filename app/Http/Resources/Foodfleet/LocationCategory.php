<?php


namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationCategory extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "locations" => Location::collection($this->whenLoaded('locations')),
        ];
    }
}
