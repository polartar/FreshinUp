<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Venue;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Venue as VenueResource;

class Venues extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $venues = QueryBuilder::for(Venue::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);
        return VenueResource::collection($venues->jsonPaginate());
    }
}
