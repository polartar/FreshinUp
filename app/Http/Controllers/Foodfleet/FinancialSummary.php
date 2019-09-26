<?php

namespace App\Http\Controllers\Foodfleet;

use App\Helpers\TransactionQueryBuilderHelper;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\FinancialSummary as FinancialSummaryResource;

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

        return new FinancialSummaryResource($transactions->get());
    }
}
