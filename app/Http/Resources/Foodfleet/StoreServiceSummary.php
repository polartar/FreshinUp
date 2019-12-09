<?php


namespace App\Http\Resources\Foodfleet;

use App\Models\Foodfleet\EventMenuItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreServiceSummary extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $menuItems = EventMenuItem::where('store_uuid', $this->uuid)->get();
        $total_services = $menuItems->sum('servings');
        $total_cost = $menuItems->sum('cost');
        return [
            "total_services" => $total_services,
            "total_cost" => $total_cost
        ];
    }
}
