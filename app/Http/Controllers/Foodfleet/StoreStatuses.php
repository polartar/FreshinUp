<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\Store\Status as StoreStatusResource;
use App\Models\Foodfleet\StoreStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class StoreStatuses extends Controller
{
    public function index(Request $request)
    {
        $documentStatuses = QueryBuilder::for(StoreStatus::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return StoreStatusResource::collection($documentStatuses);
    }
}
