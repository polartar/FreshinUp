<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\PaymentType;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\PaymentType as PaymentTypeResource;

class PaymentTypes extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $paymentTypes = QueryBuilder::for(PaymentType::class, $request)->allowedFilters([
            Filter::exact('uuid')
        ]);
        return PaymentTypeResource::collection($paymentTypes->get());
    }
}
