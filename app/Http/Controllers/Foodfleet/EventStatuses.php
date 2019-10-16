<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\EventStatus as EventStatusResource;
use App\Models\Foodfleet\EventStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EventStatuses extends Controller
{
    public function index(Request $request)
    {
        $eventStatuses = QueryBuilder::for(EventStatus::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return EventStatusResource::collection($eventStatuses);
    }
}
