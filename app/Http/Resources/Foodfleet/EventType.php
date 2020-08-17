<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class EventType extends JsonResource {

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
            'name' => $this->name
        ];
    }
}
