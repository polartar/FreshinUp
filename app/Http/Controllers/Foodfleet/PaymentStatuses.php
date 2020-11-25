<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

use App\Models\Foodfleet\Square\PaymentStatus as PaymentStatusModel;
use App\Http\Resources\Foodfleet\Square\PaymentStatus as PaymentStatusResource;

class PaymentStatuses extends Controller
{
    public function index(Request $request)
    {
        $statuses = QueryBuilder::for(PaymentStatusModel::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return PaymentStatusResource::collection($statuses);
    }
}
