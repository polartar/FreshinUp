<?php

namespace App\Http\Resources\Foodfleet\Document;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;
use FreshinUp\FreshBusForms\Http\Resources\User\User as UserResource;

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
        $assignedType = DocumentAssignedEnum::getKeyUseDescription($this->assigned_type, $this->event_store_uuid, $this->event_location_uuid);
        $assignedResource = DocumentAssignedEnum::getResource($assignedType);
        // var_dump($this->whenLoaded('assigned'));

        $data = [
            'uuid' => $this->uuid,
            'title' => $this->title,
            'status' => intval($this->status),
            'type' => intval($this->type),
            'description' => $this->description,
            'notes' => $this->notes,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'assigned' => new $assignedResource($this->whenLoaded('assigned')),
            'assigned_type' => $assignedType,
            'event_store_uuid' => $this->event_store_uuid,
            'event_location_uuid' => $this->event_location_uuid,
            'expiration_at' => $this->expiration_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $data;
    }
}
