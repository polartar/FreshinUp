<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;

class Modifier extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'resource_name' => $this->resource_name,
            'label' => $this->label,
            'placeholder' => $this->placeholder,
            'type' => $this->type,
            'filter' => $this->filter
        ];

        return $data;
    }
}
