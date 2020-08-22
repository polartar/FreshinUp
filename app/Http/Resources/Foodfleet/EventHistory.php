<?php

namespace App\Http\Resources\Foodfleet;

use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventStatus;
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
            "status" => EventStatus::collection($this->whenLoaded('status')),
            "date" => $this->date,
            "description" => $this->description,
            "completed" => $this->completed,
            "event_uuid" => $this->event_uuid,
        ];
    }
}
