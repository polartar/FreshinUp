<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class Document extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $assigned = null;
        switch ($this->assigned_type) {
            case 1:
                $assigned = $this->assigned_user;
                break;
            case 2:
                $assigned = $this->assigned_fleet_member;
                break;
            case 3:
                $assigned = $this->assigned_event;
                break;
            case 4:
                $assigned = $this->assigned_venue;
                break;
            default:
                $assigned = $this->assigned_user;
                break;
        }

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'type' => $this->type,
            'description' => $this->description,
            'notes' => $this->notes,
            'owner' => $this->owner,
            'assigned_type' => $this->assigned_type,
            'assigned' => $assigned,
            'expiration_at' => $this->expiration_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $data;
    }
}
