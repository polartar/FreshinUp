<?php

namespace App\Http\Controllers\Foodfleet\Suppliers;

use App\Filters\BelongsToWhereInIdEquals;
use App\Filters\BelongsToWhereInUuidEquals;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Store\Store as StoreResource;
use App\Models\Foodfleet\Store as StoreModel;
use App\Sorts\Stores\OwnerNameSort;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Sort;

class Stores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @param  $uuid
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, $uuid)
    {
        $stores = QueryBuilder::for(StoreModel::class, $request)
            ->where('supplier_uuid', $uuid)
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
}
