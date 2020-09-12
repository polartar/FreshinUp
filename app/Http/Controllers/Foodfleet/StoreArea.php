<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Http\Resources\Foodfleet\Store\StoreArea as StoreAreaResource;
use App\Models\Foodfleet\StoreArea as StoreAreaModel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class StoreArea extends Controller
{
    public function index(Request $request)
    {
        $areas = QueryBuilder::for(StoreAreaModel::class, $request)
            ->allowedIncludes([
                'store'
            ])
            ->allowedSorts([
                'name',
                'radius',
                'state',
                'store_uuid',
            ])
            ->allowedFilters([
                'name',
                'state',
                'radius',
                Filter::exact('store_uuid')
            ]);

        return StoreAreaResource::collection($areas->jsonPaginate());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'store_uuid' => 'required'
        ]);
        $area = StoreAreaModel::create($request->only([
            'name', 'radius', 'state', 'store_uuid'
        ]));
        return new StoreAreaResource($area);
    }
}
