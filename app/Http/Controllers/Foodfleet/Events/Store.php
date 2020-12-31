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

    public function store (Request $request, $uuid) {
        $event = Event::where('uuid', $uuid)->firstOrFail();
        $this->validate($request, [
            'store_uuid' => 'required|exists:stores,uuid'
        ]);
        Db::table('events_stores')->insert([
            'store_uuid' => $request->get('store_uuid'),
            'event_uuid' => $event->uuid
        ]);
        return response()->json(new EventResource($event), 201);
    }
}
