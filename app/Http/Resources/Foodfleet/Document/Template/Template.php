<?php

namespace App\Http\Resources\Foodfleet\Document\Template;

use Illuminate\Http\Resources\Json\JsonResource;

class Template extends JsonResource {

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'status_id' => $this->status_id,
            'status' => new Status($this->whenLoaded('status')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
