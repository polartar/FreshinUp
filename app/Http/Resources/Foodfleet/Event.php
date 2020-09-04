<?php


namespace App\Http\Resources\Foodfleet;

use FreshinUp\FreshBusForms\Http\Resources\User\User;
use FreshinUp\FreshBusForms\Http\Resources\Company\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foodfleet\Store\Store;

class Event extends JsonResource
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
            "uuid" => $this->uuid,
            "name" => $this->name,
            "status_id" => (int) $this->status_id,
            "status" => new EventStatus($this->whenLoaded('status')),
            "manager" => new User($this->whenLoaded('manager')),
            "location" => new Location($this->whenLoaded('location')),
            "venue" => new Venue($this->whenLoaded('venue')),
            "event_tags" => EventTag::collection($this->whenLoaded('eventTags')),
            "host" => new Company($this->whenLoaded('host')),
            'host_status' => (int) $this->host_status,
            "stores" => Store::collection($this->whenLoaded('stores')),
            "schedule" => new EventSchedule($this->whenLoaded('schedule')),
            "start_at" => $this->start_at,
            "end_at" => $this->end_at,
            "staff_notes" => $this->staff_notes,
            "member_notes" => $this->member_notes,
            "customer_notes" => $this->customer_notes,
            'budget' => $this->budget,
            'attendees' => (int) $this->attendees,
            'commission_rate' => (int) $this->commission_rate,
            'commission_type' => (int) $this->commission_type,
            'type_id' => (int) $this->type_id,
            'type' => new EventType($this->whenLoaded('type')),
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            'location_uuid' => $this->location_uuid,
            'host_uuid' => $this->host_uuid,
            'manager_uuid' => $this->manager_uuid,
            'venue_uuid' => $this->venue_uuid
        ];
    }
}
