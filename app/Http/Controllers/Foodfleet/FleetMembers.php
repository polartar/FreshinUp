<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\FleetMember;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\FleetMember as FleetMemberResource;

class FleetMembers extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $fleetMembers = QueryBuilder::for(FleetMember::class, $request)
            ->allowedFilters(['name']);

        return FleetMemberResource::collection($fleetMembers->jsonPaginate());
    }
}
