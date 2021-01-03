<?php

namespace App\Http\Resources\Foodfleet;

use App\Helpers\Permissions\MenuItemPermissions as Permissions;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MenuItemPermissions extends ResourceCollection
{
    protected $filters;

    /**
     * Permissions constructor.
     * @param $filters
     */
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $permissions = new Permissions($request->user());

        return [
            'properties' => $permissions->getProperties($this->filters)
        ];
    }
}
