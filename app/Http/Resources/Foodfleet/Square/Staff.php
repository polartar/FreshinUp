<?php


namespace App\Http\Resources\Foodfleet\Square;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Staff extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "uuid" => $this->uuid,
            "name" => $this->first_name . ' ' . $this->last_name,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "square_id" => $this->square_id
        ];
    }
}
