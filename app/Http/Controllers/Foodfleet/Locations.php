<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Location;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Location as LocationResource;

class Locations extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $locations = QueryBuilder::for(Location::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return LocationResource::collection($locations->jsonPaginate());
    }
}
