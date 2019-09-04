<?php

namespace App\Http\Resources\Foodfleet;

use App\Models\Foodfleet\Square\PaymentType;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FinancialSummary extends ResourceCollection
{
    const TAXES = 0.2;


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $payments = $this->collection;

        // Sales over time points
        $salesTime = [];
        $minDate = Carbon::parse($payments->sortBy('square_created_at')->first()->square_created_at);
        $maxDate = Carbon::parse($payments->sortBy('square_created_at')->last()->square_created_at);

        // Iterate on each date between first date and last date
        for ($date = $minDate; $date->lessThanOrEqualTo($maxDate); $date->addDay()) {
            $clonedQuery = clone($payments);
            $date->toImmutable();

            // Set lower bound as start of the day only if it is not min date
            $lowerBound = $date->startOfDay();
            if ($date === $minDate) {
                $lowerBound = $minDate;
            }

            // Set upper bound as end of the day only if it is lower than max date
            $upperBound = $date->endOfDay();
            if ($upperBound->greaterThanOrEqualTo($maxDate)) {
                $upperBound = $maxDate;
            }

            // Get sum of total money attribute for the specified date
            $value = $clonedQuery->where('square_created_at', '>=', $lowerBound)
                ->where('square_created_at', '<=', $upperBound)
                ->sum('total_money');

            $salesTime[] = [
                'value' => $value / 100,
                'date' => $date->toDateString()
            ];

            $date->toMutable();
        }

        // Total calculation
        $gross = $payments->sum('total_money') / 100;
        $net = $gross - ($gross * self::TAXES);
        $clonedQuery = clone($payments);
        $cash = ($clonedQuery->where('payment_type_uuid', PaymentType::where('name', 'CASH')->first()->uuid)->sum('total_money')) / 100;
        $clonedQuery = clone($payments);
        $credit = ($clonedQuery->where('payment_type_uuid', '!=', PaymentType::where('name', 'CASH')->first()->uuid)->sum('total_money')) / 100;

        // Sales per method type
        $salesType = [
            [
                'name' => 'CASH',
                'value' => $cash
            ]
        ];

        // Iterate on the remaining payment type
        foreach (PaymentType::where('name', '!=', 'CASH') as $paymentType) {
            $clonedQuery = clone($payments);
            $salesType[] = [
                'name' => $paymentType->name,
                'value' => ($clonedQuery->where('payment_type_uuid', $paymentType->uuid)->sum('total_money')) / 100
            ];
        }

        // Average ticket
        $clonedQuery = clone($payments);
        $numberOfCustomer = $clonedQuery->groupBy('customer_uuid')->count();
        $avgTicket = ($gross / $numberOfCustomer) / 100;

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
