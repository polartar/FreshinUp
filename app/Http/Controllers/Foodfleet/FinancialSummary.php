<?php

namespace App\Http\Controllers\Foodfleet;

use App\Helpers\TransactionQueryBuilderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Square\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class FinancialSummary extends Controller
{
    /**
     * Get financial summary report
     *
     * @param Request $request
     * @return FinancialSummaryResource
     */
    public function index(Request $request)
    {
        $transactions = QueryBuilder::for(Transaction::class, $request)
            ->allowedFilters(TransactionQueryBuilderHelper::getTransactionFilters());

        return FinancialSummaryResource($transactions->get());
    }
}
