<?php


namespace App\Http\Controllers\Foodfleet;

use App\Enums\StoreStatus as StoreStatusEnum;
use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Store\Store as StoreResource;
use App\Http\Resources\Foodfleet\Store\StoreServiceSummary as StoreServiceSummaryResource;
use App\Http\Resources\Foodfleet\Store\StoreSummary as StoreSummaryResource;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store as StoreModel;
use App\Sorts\Stores\OwnerNameSort;
use App\Sorts\Stores\StoreTagNameSort;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class Store extends Controller
{
    public function index(Request $request)
    {
        $stores = QueryBuilder::for(StoreModel::class, $request)
            ->allowedIncludes([
                'tags',
                'addresses',
                'events',
                'supplier',
                'supplier.admin',
                'status',
                'owner',
                'type'
            ])
            ->allowedSorts([
                'name',
                'status_id',
                'created_at',
                'state_of_incorporation',
                Sort::custom('owner', new OwnerNameSort()),
                Sort::custom('tags', new StoreTagNameSort()),
            ])
            ->allowedFilters([
                'name',
                'state_of_incorporation',
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('tag', BelongsToWhereInUuidEquals::class, 'tags'),
                Filter::custom('owner_uuid', BelongsToWhereInUuidEquals::class, 'owner'),
                Filter::custom('type_id', BelongsToWhereInIdEquals::class, 'type'),
                Filter::exact('uuid'),
                Filter::exact('supplier_uuid')
            ]);

        return StoreResource::collection($stores->jsonPaginate());
    }

    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'name' => 'required',
            'status_id' => 'integer',
            'commission_rate' => 'integer',
            'commission_type' => 'integer',
            'event_uuid' => 'string|exists:events,uuid',
            'tags' => 'array'
        ]);

        // TODO: avoid using all. Use only model::fillable
        $data = $request->all();
        $collection = collect($data);
        $updateData = $collection->except(['tags', 'event_uuid', 'commission_rate', 'commission_type'])->all();
        /** @var StoreModel $store */
        $store = StoreModel::where('uuid', $uuid)->first();
        if (!$store) {
            throw new ModelNotFoundException('No fleet member found to update.');
        }
        $store->update($updateData);

        // array of tag uuid
        if ($request->has('tags')) {
            // TODO: validate array of tag uuid
            $store->tags()->sync($request->input('tags'));
        }


        $event_uuid = $collection->get('event_uuid');
        $commission_rate = $collection->get('commission_rate');
        $commission_type = $collection->get('commission_type');
        if (!empty($event_uuid) && !empty($commission_rate) && !empty($commission_type)) {
            $event = Event::where('uuid', $event_uuid)->first();
            $store->events()->updateExistingPivot(
                $event,
                compact('commission_rate', 'commission_type')
            );
        }

        $store->load('tags');
        return new StoreResource($store);
    }

    public function show(Request $request, $uuid)
    {
        $store = QueryBuilder::for(StoreModel::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([
                'menus', 'tags', 'documents', 'events', 'supplier', 'supplier.admin', 'status',
                'owner'
            ]);

        // Include eventsCount in the query if needed
        if ($request->has('provide') && $request->get('provide') == 'events-count') {
            $store->withCount('events');
        }

        return new StoreResource($store->firstOrFail());
    }

    public function summary(Request $request, $uuid)
    {
        $store = QueryBuilder::for(StoreModel::class, $request)
            ->with('tags')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new StoreSummaryResource($store);
    }

    public function serviceSummary(Request $request, $uuid)
    {
        $store = QueryBuilder::for(StoreModel::class, $request)
            ->with('events')
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new StoreServiceSummaryResource($store);
    }

    public function destroy($uuid)
    {
        $store = StoreModel::where('uuid', $uuid)->firstOrFail();
        $store->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function showNewRecommendation()
    {
        return new StoreResource(
            StoreModel::make(
                ([
                    'status_id' => StoreStatusEnum::DRAFT
                ])
            )
        );
    }

    public function store(Request $request)
    {
        $rules = [
            'owner_uuid' => 'exists:users,uuid',
            'type_id' => 'exists:store_types,id',
            'status_id' => 'exists:store_statuses,id',
            'supplier_uuid' => 'exists:companies,uuid',
            'square_id' => 'integer',
            'name' => 'required|string',
            'tags' => 'array',
            'pos_system' => 'string',
            'size' => 'integer',
            'contact_phone' => 'string',
            'state_of_incorporation' => 'string',
            'website' => 'url',
            'twitter' => 'url',
            'facebook' => 'url',
            'instagram' => 'url',
            'staff_notes' => 'string',
        ];
        $this->validate($request, $rules);
        $data = $request->only(array_diff(array_keys($rules), ['tags']));
        /** @var StoreModel $store */
        $store = StoreModel::create($data);
        // list of tag uuid
        $tags = $request->input('tags');
        if ($tags) {
            // TODO: validate tags
            $store->tags()->sync($tags);
        }
        $store->load('tags');
        return new StoreResource($store);
    }
}
