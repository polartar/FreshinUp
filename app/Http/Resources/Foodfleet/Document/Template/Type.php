<?php

namespace App\Http\Resources\Foodfleet\Document\Template;

use Illuminate\Http\Resources\Json\JsonResource;

class Type extends JsonResource {

    /**
     * @param \Illuminate\Http\Request
     * @param mixed $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
