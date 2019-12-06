<?php


namespace App\Http\Controllers\Foodfleet\Events;

use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Store as StoreResource;

class Stores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $uuid)
    {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $stores = QueryBuilder::for(Store::whereIn('uuid', $event->stores()->pluck('stores.uuid')->toArray()), $request)
            ->with('tags')
            ->allowedIncludes([
                'addresses'
            ])
            ->allowedSorts([
                'name',
                'status',
                'created_at',
            ])
            ->allowedFilters([
                'name',
                Filter::exact('status'),
                Filter::custom('tag', BelongsToWhereInUuidEquals::class, 'tags'),
                Filter::exact('uuid'),
                Filter::exact('supplier_uuid')
            ]);

        return StoreResource::collection($stores->jsonPaginate());
    }
}
