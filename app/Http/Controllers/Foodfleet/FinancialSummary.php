<?php

namespace App\Http\Controllers\Foodfleet;

use App\Helpers\PaymentQueryBuilderHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Square\Payment;
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
        $payments = QueryBuilder::for(Payment::class, $request)
            ->allowedFilters(PaymentQueryBuilderHelper::getTransactionFilters());

        return new FinancialSummaryResource($payments->get());
    }
}
