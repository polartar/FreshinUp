<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Event;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Event as EventResource;

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
            ->with('location')
            ->with('stores')
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return EventResource::collection($events->jsonPaginate());
    }
}
