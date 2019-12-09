<?php


namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventStore extends JsonResource
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
            "event_uuid" => $this->pivot->event_uuid,
            "store_uuid" => $this->pivot->store_uuid,
            "commission_rate" => $this->pivot->commission_rate,
            "commission_type" => $this->pivot->commission_type
        ];
    }
}
