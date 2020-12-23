<?php


namespace App\Http\Controllers\Foodfleet;

use App\Enums\PaymentStatus;
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
                'store_uuid',
                'name',
                Filter::exact('uuid'),
                'square_id',
                Filter::exact('status_id')
            ])
            ->allowedIncludes([
                'status',
                'event'
            ])
            ->allowedSorts([
                'name',
                'status_id',
                'created_at',
                'due_date',
                'amount_money'
            ]);


        return PaymentResource::collection($payments->jsonPaginate());
    }

    public function store(Request $request)
    {
        $this->validate($request, PaymentModel::RULES);
        $payload = $request->only(PaymentModel::FILLABLES);
        $item = PaymentModel::create(array_merge($payload, [
            'status_id' => $request->input('status_id', PaymentStatus::PENDING)
        ]));
        return new PaymentResource($item);
    }

    public function update(Request $request, $uuid)
    {
        $payment = PaymentModel::where('uuid', $uuid)->firstOrFail();
        $this->validate($request, PaymentModel::EDIT_RULES);
        $payload = $request->only(PaymentModel::FILLABLES);
        $payment->update($payload);
        $payment->load('event');
        return new PaymentResource($payment);
    }
}
