<?php


namespace App\Http\Resources\Foodfleet\Store;
use App\User;
use App\Models\Foodfleet\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreSummary extends JsonResource
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
            "name" => $this->name,
            "status" => $this->status,
            "owner" => $this->owner,
            "tags" => Tag::collection($this->whenLoaded('tags'))
        ];
    }
}
