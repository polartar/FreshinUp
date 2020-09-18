<?php


namespace App\Http\Controllers\Foodfleet;

use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
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
                'name',
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('owner_uuid', BelongsToWhereInUuidEquals::class, 'owner')
            ])
            ->allowedIncludes([
                'locations',
                'owner',
                'status'
            ])
            ->allowedSorts([
                'name',
                'status_id',
                'owner_uuid'
            ]);
        return VenueResource::collection($venues->jsonPaginate());
    }
}
