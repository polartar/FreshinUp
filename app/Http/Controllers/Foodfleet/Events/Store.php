<?php


namespace App\Http\Controllers\Foodfleet\Events;

use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store as StoreModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Store\Store as StoreResource;

class Store extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param $uuid
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $uuid)
    {
        /** @var Event $event */
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $stores = QueryBuilder::for(StoreModel::whereIn(
            'uuid',
            $event->stores()
            ->pluck('stores.uuid')->toArray()
        ), $request)
            ->with(['tags','events'])
            ->allowedIncludes([
                'addresses',
                'owner'
            ])
            ->allowedSorts([
                'name',
                'status',
                'created_at',
            ])
            ->allowedFilters([
                'name',
                'state_of_incorporation',
                'type_id',
                Filter::exact('status'),
                Filter::custom('tag', BelongsToWhereInUuidEquals::class, 'tags'),
                Filter::exact('uuid'),
                Filter::exact('supplier_uuid')
            ])
            ->jsonPaginate();

        return StoreResource::collection($stores);
    }

    public function store(Request $request, $eventUuid, $storeUuid)
    {
        $event = Event::where('uuid', $eventUuid)->firstOrFail();
        Db::table('events_stores')->insert([
            'store_uuid' => $storeUuid,
            'event_uuid' => $event->uuid
        ]);
        // TODO: until we know a nicer way to return the data
        return response()->json([
            'data' => new EventResource($event),
        ], 201);
    }

    public function destroy(Request $request, $eventUuid, $storeUuid)
    {
        $event = Event::where('uuid', $eventUuid)->firstOrFail();
        Db::table('events_stores')
            ->where([
                'store_uuid' => $storeUuid,
                'event_uuid' => $event->uuid
            ])
            ->delete();
        // TODO: until we know a nicer way to return the data
        return response()->json([
            'data' => new EventResource($event),
        ], 204);
    }
}
