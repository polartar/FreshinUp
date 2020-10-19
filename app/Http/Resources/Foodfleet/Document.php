<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\DocumentAssigned as DocumentAssignedEnum;
use App\Enums\DocumentStatus as DocumentStatusEnum;
use App\Enums\DocumentType as DocumentTypeEnum;
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
        $assignedType = DocumentAssignedEnum::getKeyUseDescription($this->assigned_type, $this->event_store_uuid);
        $assignedResource = DocumentAssignedEnum::getResource($assignedType);
        $attachment = $this->getFirstMedia('attachment');
//        dd($attachment);
        $file = [
            'name' => optional($attachment)->file_name,
            'src' => optional($attachment)->getPath()
        ];
        $data = [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'status_id' => $this->status_id ? intval($this->status_id) : DocumentStatusEnum::PENDING,
            'status' => new DocumentStatus($this->whenLoaded('status')),
            'type_id' => $this->type_id ? intval($this->type_id) : DocumentTypeEnum::FROM_TEMPLATE,
            'type' => new DocumentStatus($this->whenLoaded('type')),
            'description' => $this->description,
            'file' => $file,
            'notes' => $this->notes,
            'owner' => new UserResource($this->whenLoaded('owner')),
            'assigned' => new $assignedResource($this->whenLoaded('assigned')),
            'assigned_type' => $assignedType,
            'event_store_uuid' => $this->event_store_uuid,
            'expiration_at' => $this->expiration_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by_uuid' => $this->created_by_uuid,
        ];

        return $data;
    }
}
