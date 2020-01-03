<?php

namespace App\Http\Resources\Foodfleet\Company;

use Illuminate\Http\Resources\Json\JsonResource;

class SquareLocation extends JsonResource
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
            'square_id' => $this->square_id,
            'name' => $this->name,
        ];
    }
}
