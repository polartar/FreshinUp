<?php

namespace App\Http\Controllers\Foodfleet;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\MenuItem;
use App\Models\Foodfleet\Store;
use App\Http\Resources\Foodfleet\MenuItem as MenuItemResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\Request;

class MenuItems extends Controller
{
    public function index(Request $request, $uuid)
    {
        $items = QueryBuilder::for(MenuItem::class, $request)
            ->allowedIncludes(['store'])
            ->allowedFilters([
                'store',
                Filter::exact('uuid'),
                Filter::exact('store_uuid')
            ]);
        return MenuItemResource::collection($items->get());
    }
}
