<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\Store\Type as StoreTypeResource;
use App\Models\Foodfleet\StoreType as StoreTypeModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class StoreType extends Controller
{
    public function index(Request $request)
    {
        $storeTypes = QueryBuilder::for(StoreTypeModel::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return StoreTypeResource::collection($storeTypes);
    }
}
