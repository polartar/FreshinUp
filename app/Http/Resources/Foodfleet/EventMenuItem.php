<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foodfleet\Store\Store;

class EventMenuItem extends JsonResource
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
            "servings" => $this->servings,
            "cost" => $this->cost,
            "description" => $this->description,
            "flag" => $this->flag,
            "menu" => new Menu($this->whenLoaded('menu')),
            "store" => new Store($this->whenLoaded('store')),
            "event" => new Event($this->whenLoaded('event'))
        ];
    }
}
