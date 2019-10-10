<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Event;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Filters\Transaction\BelongsToWhereUuidEquals;

class Events extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $events = QueryBuilder::for(Event::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name',
                Filter::exact('status'),
                Filter::exact('host_uuid'),
                Filter::exact('manager_uuid'),
                Filter::custom('event_tag_uuid', BelongsToWhereUuidEquals::class, 'eventTags'),
            ])
            ->allowedIncludes([
                'host',
                'location',
                'manager',
                'event_tags'
            ]);

        return EventResource::collection($events->jsonPaginate());
    }
}
