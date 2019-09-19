<?php

namespace App\Http\Resources\Foodfleet;

use App\Models\Foodfleet\Square\Payment;
use App\Models\Foodfleet\Square\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FinancialSummary extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $transactions = $this->collection;

        // Sales over time points
        $salesTime = [];
        if ($transactions->count() != 0 ) {
            $minDate = Carbon::parse($transactions->sortBy('square_created_at')->first()->square_created_at);
            $maxDate = Carbon::parse($transactions->sortBy('square_created_at')->last()->square_created_at);

            // Iterate on each date between first date and last date
            for ($date = clone($minDate); $date->lessThanOrEqualTo($maxDate); $date->addDay()) {
                $clonedQuery = clone($transactions);
                $copyOfDate = clone($date);
                $copyOfDate->toImmutable();

                // Set lower bound as start of the day only if it is not min date
                $lowerBound = clone($copyOfDate->startOfDay());
                if ($date->equalTo($minDate)) {
                    $lowerBound = clone($minDate);
                }

                // Set upper bound as end of the day only if it is lower than max date
                $upperBound = clone($copyOfDate->endOfDay());
                if ($upperBound->greaterThanOrEqualTo($maxDate)) {
                    $upperBound = clone($maxDate);
                }

                // Get sum of total money attribute for the specified date
                $value = $clonedQuery->filter(function ($item) use ($lowerBound, $upperBound) {
                    return (data_get($item, 'square_created_at') >= $lowerBound) && (data_get($item, 'square_created_at') <= $upperBound);
                })->sum('total_money');

                $salesTime[] = [
                    'value' => $value,
                    'date' => $date->toDateString()
                ];
            }
        }

        // Total calculation
        $gross = $transactions->sum('total_money');
        $net = $gross - $transactions->sum('total_tax_money');

        $paymentUuids = [];
        foreach ($transactions as $transaction) {
            $paymentUuids = array_merge($paymentUuids, $transaction->payments->pluck('uuid')->toArray());
        }
        $payments = Payment::whereIn('uuid', $paymentUuids)->get();
        $clonedQuery = clone($payments);
        $cash = ($clonedQuery->where('payment_type_uuid', PaymentType::where('name', 'CASH')->first()->uuid)->sum('amount_money'));
        $clonedQuery = clone($payments);
        $credit = ($clonedQuery->where('payment_type_uuid', '!=', PaymentType::where('name', 'CASH')->first()->uuid)->sum('amount_money'));

        // Sales per method type
        $salesType = [
            [
                'name' => 'CASH',
                'value' => $cash
            ]
        ];

        // Iterate on the remaining payment type
        $paymentTypes = PaymentType::where('name', '!=', 'CASH')->get();
        foreach ($paymentTypes as $paymentType) {
            $clonedQuery = clone($payments);
            $salesType[] = [
                'name' => $paymentType->name,
                'value' => ($clonedQuery->where('payment_type_uuid', $paymentType->uuid)->sum('amount_money'))
            ];
        }

        // Average ticket
        $avgTicket = 0;
        if ($transactions->count() != 0 ) {
            $avgTicket = round(($gross / $transactions->count()));
        }

        return [
            'data' => [
                'sales_time' => $salesTime,
                'gross' => $gross,
                'net' => $net,
                'cash' => $cash,
                'credit' => $credit,
                'sales_type' => $salesType,
                'avg_ticket' => $avgTicket
            ]
        ];

    }
}
