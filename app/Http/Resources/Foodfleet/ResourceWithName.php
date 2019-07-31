<?php


namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResourceWithName extends JsonResource
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
            "uuid" => $this->resource->uuid,
            "label" => $this->resource->name
        ];
    }
}
