<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class EventSchedule extends JsonResource
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
            "interval_unit" => $this->interval_unit,
            "interval_value" => $this->interval_value,
            "occurrences" => $this->occurrences,
            "ends_on" => $this->ends_on,
            "repeat_on" => $this->repeat_on,
            "description" => $this->description,
            "event" => new Event($this->whenLoaded('event')),
            "schedule_occurrences" => EventOccurrence::collection($this->whenLoaded('scheduleOccurrences'))
        ];
    }
}
