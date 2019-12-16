<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class EventOccurrence extends JsonResource
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
            "start_at" => $this->start_at,
            "end_at" => $this->end_at
        ];
    }
}
