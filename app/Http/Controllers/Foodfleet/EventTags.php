<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\EventTag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\EventTag as EventTagResource;

class EventTags extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $eventTags = QueryBuilder::for(EventTag::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return EventTagResource::collection($eventTags->get());
    }
}
