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
                $assigned = $this->assignedUser;
                break;
            case 2:
                $assigned = $this->assignedFleetMember;
                break;
            case 3:
                $assigned = $this->assignedEvent;
                break;
            default:
                $assigned = $this->assignedUser;
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
