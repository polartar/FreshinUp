<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foodfleet\Store\Store;

class MenuItem extends JsonResource
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
            "title" => $this->title,
            "description" => $this->description,
            "servings" => $this->servings,
            "cost" => $this->cost,
            "store" => new Store($this->whenLoaded('store'))
        ];
    }
}
