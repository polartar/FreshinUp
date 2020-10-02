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
    public function index(Request $request)
    {
        $items = QueryBuilder::for(MenuItem::class, $request)
            ->allowedIncludes(['store'])
            ->allowedFilters([
                Filter::exact('uuid'),
                Filter::exact('store_uuid')
            ])
            ->allowedSorts([
                'title',
                'cost'
            ])
        ;
        return MenuItemResource::collection($items->jsonPaginate());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'store_uuid' => 'string|required|exists:stores,uuid',
            'title' => 'string|required',
            'servings' => 'integer|required',
            'cost' => 'integer|required',
            'description' => 'string'
        ]);

        $inputs = $request->input();
        $menuItem = MenuItem::create($inputs);

        return new \App\Http\Resources\Foodfleet\MenuItem($menuItem);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $uuid
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $uuid)
    {
        $menu = QueryBuilder::for(MenuItem::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([ 'store'])
            ->firstOrFail();

        return new MenuItemResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $uuid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'title' => 'string',
            'servings' => 'integer',
            'cost' => 'integer',
            'description' => 'string'
        ]);

        $inputs = $request->input();
        $menuItem = MenuItem::where('uuid', $uuid)->firstOrFail();
        $menuItem->update($inputs);
        return new MenuItemResource($menuItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $uuid
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $menuItem = MenuItem::where('uuid', $uuid)->firstOrFail();
        $menuItem->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
