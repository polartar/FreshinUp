<?php

namespace App\Http\Resources\Foodfleet;

use App\Enums\EventStatus as EventStatusEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class EventStatus extends JsonResource
{
    public function getColorFor ($id) {
        $statusColors = [
            EventStatusEnum::DRAFT => 'grey',
            EventStatusEnum::FF_INITIAL_REVIEW => 'warning',
            EventStatusEnum::CUSTOMER_AGREEMENT => 'warning',
            EventStatusEnum::FLEET_MEMBER_SELECTION => 'warning',
            EventStatusEnum::CUSTOMER_REVIEW => 'warning',
            EventStatusEnum::FLEET_MEMBER_CONTRACTS => 'warning',
            EventStatusEnum::CONFIRMED => 'success',
            EventStatusEnum::CANCELLED => 'error',
            EventStatusEnum::PAST => 'grey',
        ];
        return $statusColors[$id] ?? 'grey';
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
            'color' => $this->getColorFor($this->id),
        ];
    }
}
