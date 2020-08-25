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
        $eventHistories = QueryBuilder::for(\App\Models\Foodfleet\EventHistory::class, $request)
            ->allowedIncludes([
                'status',
            ])
            ->allowedFilters([
                Filter::exact('event_uuid')
            ])->get();

        // TODO: should return a list of all statuses, not just the current status changes
        // if filter event_uuid is detected then apply this logic
        // $event_uuid = $request->query('event_uuid');
        // for status in statuses

//        $histories = [
//            [
//                'id' => 1,
//                'event_uuid' => $event_uuid,
//                'status_id' => $status->id,
//                'name' => $status->name,
//                'completed' => // depend on whether or not the status has been found and completed,
//                'date' => // $history->date  ?? '',
//                'description' => // $history->description ?? getEventStatusDescription
//            ]
//        ];

        return EventHistoryResource::collection($eventHistories);
    }
}
