<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\LocationCategory as LocationCategoryModel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\LocationCategory as LocationCategoryResource;

class LocationCategory extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $locations = QueryBuilder::for(LocationCategoryModel::class, $request)
            ->allowedFilters([
                Filter::exact('id'),
                'name',
            ])
            ->allowedIncludes(['locations']);

        return LocationCategoryResource::collection($locations->jsonPaginate());
    }
}
