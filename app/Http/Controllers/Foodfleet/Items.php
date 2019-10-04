<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Item;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Item as ItemResource;

class Items extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(Item::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return ItemResource::collection($items->jsonPaginate());
    }
}
