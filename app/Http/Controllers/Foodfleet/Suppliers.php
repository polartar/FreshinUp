<?php

namespace App\Http\Controllers\Foodfleet;

use App\Filters\BelongsToManyWhereInUuidEquals;
use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Document as DocumentResource;
use App\Http\Resources\Foodfleet\Store\Statistic;
use App\Http\Resources\Foodfleet\Store\Store as StoreResource;
use App\Http\Resources\Foodfleet\Event as EventResource;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\Store as StoreModel;
use App\Models\Foodfleet\StoreStatus;
use App\Sorts\Events\EventTagNameSort;
use App\Sorts\Events\HostNameSort;
use App\Sorts\Events\ManagerNameSort;
use App\Sorts\Stores\OwnerNameSort;
use App\User;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo as FilterGreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo as FilterLessThanOrEqualTo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class Suppliers extends Controller
{
    public function documents(Request $request, $uuid)
    {
        $documents = QueryBuilder::for(Document::class, $request)
            ->where('assigned_uuid', $uuid)
            ->with('assigned')
            ->allowedSorts([
                'title',
                'type_id',
                'status_id',
                'created_at',
                'expiration_at',
                'created_by',
                'signed_at'
            ])
            ->allowedFilters([
                'title',
                Filter::exact('type_id'),
                Filter::exact('status_id'),
                Filter::exact('event_store_uuid'),
                Filter::custom('expiration_from', FilterGreaterThanOrEqualTo::class, 'expiration_at'),
                Filter::custom('expiration_to', FilterLessThanOrEqualTo::class, 'expiration_at'),
                Filter::custom('signed_from', FilterGreaterThanOrEqualTo::class, 'signed_at'),
                Filter::custom('signed_to', FilterLessThanOrEqualTo::class, 'signed_at')
            ])
            ->jsonPaginate();

        return DocumentResource::collection($documents);
    }

    public function events(Request $request, $uuid)
    {
        $supplier = User::where('uuid', $uuid)->firstOrFail();
        $events = QueryBuilder::for(Event::class, $request)
            ->where('host_uuid', $supplier->company->uuid)
            ->with('stores')
            ->allowedIncludes([
                'status',
                'host',
                'location',
                'location.venue',
                'manager',
                'event_tags',
                'type',
                'venue'
            ])
            ->allowedSorts([
                'name',
                'start_at',
                'status_id',
                'type_id',
                Sort::custom('host', new HostNameSort()),
                Sort::custom('manager', new ManagerNameSort()),
                Sort::custom('event_tags', new EventTagNameSort()),
            ])
            ->allowedFilters([
                'name',
                Filter::exact('uuid'),
                Filter::custom('start_at', FilterGreaterThanOrEqualTo::class, 'start_at'),
                Filter::custom('end_at', FilterLessThanOrEqualTo::class, 'end_at'),
                Filter::custom('manager_uuid', BelongsToWhereInUuidEquals::class, 'manager'),
                Filter::custom('store_uuid', BelongsToManyWhereInUuidEquals::class, 'stores'),
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('event_tag_uuid', BelongsToWhereInUuidEquals::class, 'eventTags'),
                Filter::custom('type_id', BelongsToWhereInIdEquals::class, 'type'),
                Filter::custom('venue_uuid', BelongsToWhereInUuidEquals::class, 'venue'),
                Filter::custom('location_uuid', BelongsToWhereInUuidEquals::class, 'location'),
            ])
            ->jsonPaginate();
        return EventResource::collection($events);
    }

    public function stores(Request $request, $uuid)
    {
        $supplier = User::where('uuid', $uuid)->firstOrFail();
        $stores = QueryBuilder::for(StoreModel::class, $request)
            ->where('supplier_uuid', $supplier->company->uuid)
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
            ])
            ->allowedFilters([
                'name',
                'state_of_incorporation',
                Filter::custom('status_id', BelongsToWhereInIdEquals::class, 'status'),
                Filter::custom('tag', BelongsToWhereInUuidEquals::class, 'tags'),
                Filter::custom('owner_uuid', BelongsToWhereInUuidEquals::class, 'owner'),
                Filter::custom('type_id', BelongsToWhereInIdEquals::class, 'type'),
                Filter::exact('uuid'),
            ])
            ->jsonPaginate();

        return StoreResource::collection($stores);
    }

    public function stats($uuid)
    {
        $supplier = User::where('uuid', $uuid)->firstOrFail();
        $company = $supplier->company;
        if (!$company) {
            return response(new JsonResource([
                'company' => 'Supplier needs to complete profile, setup company first.'
            ]), 422);
        }
        $stores = StoreModel::where('supplier_uuid', $company->uuid)
            ->get();

        // TODO: this is most likely not optimized
        $stats = [];
        foreach ($stores as $store) {
            if (!isset($stats[$store->status_id])) {
                $stats[$store->status_id] = [];
            }
            $stats[$store->status_id][] = $store;
        }
        $resource = [];
        foreach ($stats as $status => $stores) {
            $resource[] = [
                'label' => \App\Enums\StoreStatus::getDescription($status),
                'value' => count($stores),
                'color' => \App\Http\Resources\Foodfleet\Store\StoreStatus::getColorFor($status)
            ];
        }
        return new JsonResource($resource);
    }
}
