<?php

namespace App\Http\Resources\Foodfleet;

use App\Enums\VenueStatus as VenueStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class VenueStatus extends JsonResource
{
    public static function getColorFor ($id) {
        $colors = [
            VenueStatusEnum::PENDING => 'warning',
            VenueStatusEnum::APPROVED => 'success',
            VenueStatusEnum::REJECTED => 'danger',
            VenueStatusEnum::EXPIRING => 'warning',
            VenueStatusEnum::EXPIRED => 'danger'
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
            'name' => $this->name,
            'color' => VenueStatus::getColorFor($this->id)
        ];
    }
}
