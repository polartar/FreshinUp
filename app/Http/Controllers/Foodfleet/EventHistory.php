<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\EventHistory as EventHistoryResource;

class EventHistory extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $eventHistories = QueryBuilder::for(EventHistory::class, $request)
            ->allowedSorts([
                'id',
                'completed'
            ])
            ->allowedFilters([
                Filter::exact('event_uuid')
            ]);

        return EventHistoryResource::collection($eventHistories);
    }
}
