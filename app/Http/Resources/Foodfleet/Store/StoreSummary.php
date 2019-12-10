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
        $supplier = Company::where('uuid', $this->supplier_uuid)->first();
        $owner = User::make();

        if (!empty($supplier->users_id)) {
            $owner = User::where('id', $supplier->users_id)->first();
        }
        
        return [
            "uuid" => $this->uuid,
            "name" => $this->name,
            "status" => $this->status,
            "owner" => $owner,
            "tags" => StoreTag::collection($this->whenLoaded('tags'))
        ];
    }
}
