<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Location as LocationModel;
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
        $locations = QueryBuilder::for(LocationModel::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                Filter::exact('category_id'),
                'name',
            ])
            ->allowedIncludes(['venue', 'category', 'events']);

        return LocationResource::collection($locations->jsonPaginate());
    }
}
