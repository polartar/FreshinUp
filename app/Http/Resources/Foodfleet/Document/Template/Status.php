<?php

namespace App\Http\Resources\Foodfleet\Document\Template;

use App\Enums\DocumentTemplateStatus as Enum;
use Illuminate\Http\Resources\Json\JsonResource;

class Status extends JsonResource
{
    public static function getColorFor ($id) {
        $colors = [
            Enum::DRAFT => 'grey',
            Enum::PUBLISHED => 'success',
        ];
        return $colors[$id] ?? 'grey';
    }

    public static function getDescriptionFor ($id) {
        $descriptions = [
            Enum::DRAFT => 'means it\'s being edited, but not yet made available to be used as a doc template',
            Enum::PUBLISHED => 'released / made available to be used as a template',
        ];
        return $descriptions[$id] ?? '';
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
            'color' => self::getColorFor($this->id),
            'description' => self::getDescriptionFor($this->id)
        ];
    }
}
