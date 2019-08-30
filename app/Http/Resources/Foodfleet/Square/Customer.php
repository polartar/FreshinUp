<?php


namespace App\Http\Resources\Foodfleet\Square;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Customer extends JsonResource
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
            "name" => $this->given_name . ' ' . $this->family_name,
            "first_name" => $this->given_name,
            "last_name" => $this->family_name,
            "square_id" => $this->square_id,
            "reference_id" => $this->reference_id
        ];
    }
}
