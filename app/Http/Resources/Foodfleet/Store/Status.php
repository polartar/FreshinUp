<?php

namespace App\Http\Resources\Foodfleet\Store;

use Illuminate\Http\Resources\Json\JsonResource;

class Status extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'text' => $this->name,
        ];
    }
}
