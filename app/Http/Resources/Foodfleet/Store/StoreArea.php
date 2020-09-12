<?php


namespace App\Http\Resources\Foodfleet\Store;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreArea extends JsonResource
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
            "name" => $this->name,
            "radius" => $this->radius,
            "state" => $this->state,
            "store_uuid" => $this->store_uuid,
            "store" => new Store($this->whenLoaded('store')),
        ];
    }
}
