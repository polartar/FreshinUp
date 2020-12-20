<?php

namespace App\Http\Resources\Foodfleet\Store;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Foodfleet\Store\StoreStatus as StoreResource;

class Statistic extends JsonResource
{
    public function toArray($request)
    {
        $color = StoreResource::getColorFor($this->id);
        return [
            'label' => $this->name,
            'value' => (int) $this->stores_count,
            'color' => $color,
        ];
    }
}
