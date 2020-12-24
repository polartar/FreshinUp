<?php

namespace App\Http\Controllers\Foodfleet\Suppliers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Document as DocumentResource;
use App\Models\Foodfleet\Document;
use FreshinUp\FreshBusForms\Filters\GreaterThanOrEqualTo as FilterGreaterThanOrEqualTo;
use FreshinUp\FreshBusForms\Filters\LessThanOrEqualTo as FilterLessThanOrEqualTo;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class Documents extends Controller
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
}
