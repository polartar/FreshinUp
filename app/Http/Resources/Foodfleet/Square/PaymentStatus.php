<?php

namespace App\Http\Resources\Foodfleet\Square;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\PaymentStatus as PaymentStatusEnum;

class PaymentStatus extends JsonResource
{
    public static function getColorFor ($id) {
        $colors = [
            PaymentStatusEnum::PENDING => 'grey',
            PaymentStatusEnum::OVERDUE => 'warning',
            PaymentStatusEnum::FAILED => 'error',
            PaymentStatusEnum::REFUNDED => 'orange',
            PaymentStatusEnum::PAID => 'success'
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
            'color' => self::getColorFor($this->id)
        ];
    }
}
