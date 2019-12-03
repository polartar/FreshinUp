<?php

namespace App\Http\Resources\Foodfleet;

use FreshinUp\FreshBusForms\Http\Resources\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            "content" => $this->content,
            "owner" => new User($this->whenLoaded('owner')),
            "recipient" => new User($this->whenLoaded('recipient')),
            "created_at" => $this->created_at
        ];
    }
}
