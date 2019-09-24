<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;

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
        $assigned_type = DocumentAssignedEnum::getKeyUseDescription($this->assigned_type);
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => intval($this->status),
            'type' => intval($this->type),
            'description' => $this->description,
            'notes' => $this->notes,
            'owner' => $this->owner,
            'assigned' => $this->assigned,
            'assigned_type' => $assigned_type,
            'expiration_at' => $this->expiration_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];

        return $data;
    }
}
