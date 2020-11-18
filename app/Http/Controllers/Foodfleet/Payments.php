<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Payment as PaymentModel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Payment as PaymentResource;

class Payments extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $payments = QueryBuilder::for(PaymentModel::class, $request)
            ->allowedFilters([
                'name',
                Filter::exact('uuid'),
                'square_id',
                Filter::exact('status_id')
            ])
            ->allowedIncludes([
                'status'
            ])
            ->allowedSorts([
                'name',
                'status_id',
                'created_at',
                'due_date'
            ]);


        return PaymentResource::collection($payments->jsonPaginate());
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'string|required',
            'description' => 'string',
            'amount_money' => 'integer|required',
            'due_date' => 'date|required',
            'status_id' => 'integer'
        ];
        $this->validate($request, $rules);
        $payload = $request->only(array_keys($rules));
        $item = PaymentModel::create($payload);
        return new PaymentResource($item);
    }
}
