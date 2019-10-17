<?php


namespace App\Http\Controllers\Foodfleet;

use App\Helpers\TransactionQueryBuilderHelper;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Transaction as TransactionResource;

class Transactions extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $transactions = QueryBuilder::for(Transaction::class, $request)
            ->allowedFilters(array_merge([
                Filter::exact('uuid'),
                'square_id'
            ], TransactionQueryBuilderHelper::getTransactionFilters()))
            ->allowedIncludes([
                'items',
                'store.supplier',
                'event.host',
                'event.event_tags',
                'event.location.venue',
                'customer'
            ]);

        return TransactionResource::collection($transactions->jsonPaginate());
    }

    public function show(Request $request, $uuid)
    {
        $transactionModel = QueryBuilder::for(Transaction::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([
                'items.category',
                'store',
                'event.location.venue'
            ])->first();

        return new TransactionResource($transactionModel);
    }
}
