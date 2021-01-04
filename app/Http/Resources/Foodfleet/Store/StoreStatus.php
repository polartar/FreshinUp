<?php

namespace App\Http\Resources\Foodfleet\Store;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\StoreStatus as StoreStatusEnum;

class StoreStatus extends JsonResource
{
    public static function getColorFor ($id) {
        $colors = [
            StoreStatusEnum::DRAFT => 'grey',
            StoreStatusEnum::PENDING => 'warning',
            StoreStatusEnum::REVISION => 'success',
            StoreStatusEnum::REJECTED => 'error',
            StoreStatusEnum::APPROVED => 'success',
            StoreStatusEnum::ON_HOLD => 'accent',
        ];
        return $colors[$id] ?? 'grey';
    }
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
            'id' => $this->id,
            'value' => $this->id,
            'name' => $this->name,
            'text' => $this->name,
            'color' => StoreStatus::getColorFor($this->id)
        ];
    }
}
