<?php


namespace App\Http\Controllers\Foodfleet;

use App\Actions\UpdateStore;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Store;
use App\Actions\UpdateDocument;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Store\Store as StoreResource;

class Stores extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $stores = QueryBuilder::for(Store::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name',
                Filter::exact('supplier_uuid')
            ]);

        return StoreResource::collection($stores->jsonPaginate());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $uuid
     * @return StoreResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $uuid, UpdateStore $action)
    {
        $inputs = $request->input();
        $inputs['uuid'] = $uuid;

        $document = $action->execute($inputs);

        return new StoreResource($document);
    }
}
