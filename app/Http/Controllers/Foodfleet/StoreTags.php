<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\StoreTag;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\StoreTag as StoreTagResource;

class StoreTags extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $storeTags = QueryBuilder::for(StoreTag::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return StoreTagResource::collection($storeTags->get());
    }
}
