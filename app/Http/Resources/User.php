<?php


namespace App\Http\Resources;

use Illuminate\Http\Request;

class User extends \FreshinUp\FreshBusForms\Http\Resources\User\User
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            "manager_uuid" => $this->manager_uuid,
        ]);
    }
}
