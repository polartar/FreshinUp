<?php

namespace App\Http\Resources;

use FreshinUp\FreshBusForms\Http\Resources\User\CurrentUser as CurrentUserResource;

class CurrentUser extends CurrentUserResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'data_visibility' => $this->data_visibility
        ];
        return array_merge(parent::toArray($request), $data);

    }
}
