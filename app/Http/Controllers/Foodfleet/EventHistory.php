<?php

namespace App\Http\Controllers\Foodfleet;

use App\Enums\EventStatus as EventStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\EventHistory as EventHistoryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class EventHistory extends Controller
{
    /**
     * @param  Request  $request
     * @return JsonResource
     */
    public function index(Request $request)
    {
        $eventHistories = QueryBuilder::for(\App\Models\Foodfleet\EventHistory::class, $request)
            ->allowedIncludes([
                'status',
            ])
            ->allowedFilters([
                Filter::exact('event_uuid')
            ])
            ->get();

        return EventHistoryResource::collection($eventHistories);
    }
}
