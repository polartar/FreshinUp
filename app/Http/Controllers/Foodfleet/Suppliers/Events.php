<?php

namespace App\Http\Controllers\Foodfleet\Suppliers;

use App\Filters\BelongsToManyWhereInUuidEquals;
use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Models\Foodfleet\Event;
use App\Sorts\Events\EventTagNameSort;
use App\Sorts\Events\HostNameSort;
use App\Sorts\Events\ManagerNameSort;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class Events extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param  $uuid
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $uuid)
    {

        $events = QueryBuilder::for(Event::class, $request)
            ->where('host_uuid', $uuid)
            ->with('stores')
            ->allowedIncludes([
                'status',
                'host',
                'location',
                'location.venue',
                'manager',
                'event_tags',
                'type',
                'venue'
            ])
            ->allowedSorts([
                'name',
                'start_at',
                'status_id',
                'type_id',
                Sort::custom('host', new HostNameSort()),
                Sort::custom('manager', new ManagerNameSort()),
                Sort::custom('event_tags', new EventTagNameSort()),
            ])
            ->allowedFilters([
                'name',
                Filter::exact('uuid'),
                Filter::custom('start_at', GreaterThanOrEqualTo::class, 'start_at'),
                Filter::custom('end_at', LessThanOrEqualTo::class, 'end_at'),
                Filter::custom('manager_uuid', BelongsToWhereInUuidEquals::class, 'manager'),
                Filter::custom('store_uuid', BelongsToManyWhereInUuidEquals::class, 'stores'),
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('event_tag_uuid', BelongsToWhereInUuidEquals::class, 'eventTags'),
                Filter::custom('type_id', BelongsToWhereInIdEquals::class, 'type'),
                Filter::custom('venue_uuid', BelongsToWhereInUuidEquals::class, 'venue'),
                Filter::custom('location_uuid', BelongsToWhereInUuidEquals::class, 'location'),
            ])
            ->jsonPaginate();
        return EventResource::collection($events);
    }
}
