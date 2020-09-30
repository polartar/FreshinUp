<?php

namespace App\Http\Resources\Foodfleet;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\DocumentStatus as DocumentStatusEnum;

class DocumentStatus extends JsonResource
{
    public static function getColorFor ($id) {
        $colors = [
            DocumentStatusEnum::PENDING => 'grey',
            DocumentStatusEnum::APPROVED => 'success',
            DocumentStatusEnum::REJECTED => 'error',
            DocumentStatusEnum::EXPIRING => 'warning',
            DocumentStatusEnum::EXPIRED => 'error'
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
            'value' => $this->id,
            'text' => $this->name,
            'color' => DocumentStatus::getColorFor($this->id)
        ];
    }
}
