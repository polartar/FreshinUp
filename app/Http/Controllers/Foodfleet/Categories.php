<?php


namespace App\Http\Controllers\Foodfleet;

use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Square\Category;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\Foodfleet\Square\Category as CategoryResource;
use Spatie\QueryBuilder\Filter;

class Categories extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $categories = QueryBuilder::for(Category::class, $request)
            ->allowedFilters([
                Filter::exact('uuid'),
                'name'
            ]);

        return CategoryResource::collection($categories->jsonPaginate());
    }
}
