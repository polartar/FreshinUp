<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventHistory extends JsonResource
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
            "status" => new EventStatus($this->whenLoaded('status')),
            'status_id' => (int) $this->status_id,
            "date" => (string) $this->date,
            "description" => EventStatus::getDescriptionFor($this->status_id),
            "event_uuid" => $this->event_uuid,
        ];
    }
}
