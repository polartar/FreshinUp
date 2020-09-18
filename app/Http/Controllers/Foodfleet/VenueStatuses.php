<?php

namespace App\Http\Controllers\Foodfleet;

use App\Http\Resources\Foodfleet\VenueStatus as VenueStatusResource;
use App\Models\Foodfleet\VenueStatus;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class VenueStatuses extends Controller
{
    public function index(Request $request)
    {
        $documentStatuses = QueryBuilder::for(VenueStatus::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return VenueStatusResource::collection($documentStatuses);
    }
}
