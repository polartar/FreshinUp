<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Http\Request;
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
            ->allowedFilters([
                'square_id'
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
                'event.location'
            ])->first();

        return new TransactionResource($transactionModel);
    }
}
