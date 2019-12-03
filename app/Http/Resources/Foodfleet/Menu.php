<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class Menu extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "item" => $this->item,
            "category" => $this->category,
            "description" => $this->description,
            "street_price" => $this->street_price,
            "store" => new Store($this->whenLoaded('store'))
        ];
    }
}
