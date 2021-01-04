<?php

namespace App\Http\Controllers\Foodfleet;

use FreshinUp\FreshBusForms\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Foodfleet\MenuItemPermissions as PermissionsResource;

class MenuItemPermissions extends Controller
{
    /**
     * Display a listing of Permissions for a menu items.
     *
     * @param  Request  $request
     * @return PermissionsResource
     */
    public function index(Request $request)
    {
        $requestFilters = $request->get('filter', []);
        return new PermissionsResource($requestFilters);
    }
}
