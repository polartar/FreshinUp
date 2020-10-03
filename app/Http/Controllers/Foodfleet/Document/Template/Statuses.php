<?php

namespace App\Http\Controllers\Foodfleet\Document\Template;

use App\Http\Resources\Foodfleet\Document\Template\Status as Resource;
use App\Models\Foodfleet\Document\Template\Status as Model;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class Statuses extends Controller
{
    public function index(Request $request)
    {
        $statuses = QueryBuilder::for(Model::class, $request)
            ->allowedFilters(['name'])
            ->get();

        return Resource::collection($statuses);
    }
}
