<?php

namespace App\Http\Controllers\Foodfleet;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Foodfleet\Menu;
use App\Models\Foodfleet\EventMenuItem;
use App\Enums\EventMenuItemFlags as MenuItemFlagsEnum;
use App\Http\Resources\Foodfleet\EventMenuItem as MenuItemResource;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class EventMenuItems extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = QueryBuilder::for(EventMenuItem::class, $request)
            ->allowedIncludes(['menu', 'store', 'event'])
            ->allowedFilters([
                'item',
                Filter::exact('uuid'),
                Filter::exact('store_uuid'),
                Filter::exact('event_uuid')
            ]);
        return MenuItemResource::collection($items->jsonPaginate());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showNewRecommendation()
    {
        return new MenuItemResource(
            EventMenuItem::make()
        );
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
            'event_uuid' => 'string|required|exists:events,uuid',
            'menu_uuid' => 'string|exists:menus,uuid',
            'item' => 'string|required',
            'servings' => 'integer|required',
            'cost' => 'integer|required',
            'description' => 'string'
        ]);

        $inputs = $request->input();
        if (!empty($inputs['menu_uuid'])) {
            $inputs['flag'] = MenuItemFlagsEnum::SAME_STORE_MENU;
            $menu = Menu::where('uuid', $inputs['menu_uuid']).first();
            if ($menu->item != $inputs['item'] || $menu->description != $inputs['description'] ) {
                $inputs['flag'] = MenuItemFlagsEnum::EDIT_STORE_MENU;
            }
        }

        $menuItem = EventMenuItem::create($inputs);

        return new MenuItemResource($menuItem);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $uuid)
    {
        $menu = QueryBuilder::for(EventMenuItem::class, $request)
            ->where('uuid', $uuid)
            ->allowedIncludes([ 'store', 'event'])
            ->firstOrFail();

        return new MenuItemResource($menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $uuid)
    {
        $this->validate($request, [
            'item' => 'string',
            'servings' => 'integer',
            'cost' => 'integer',
            'description' => 'string'
        ]);

        $inputs = $request->input();
        $menuItem = EventMenuItem::where('uuid', $uuid)->first();
        if (!empty($menuItem->menu_uuid)) {
            $inputs['flag'] = MenuItemFlagsEnum::SAME_STORE_MENU;
            $menu = Menu::where('uuid', $menuItem->menu_uuid).first();
            if ($menu->item != $inputs['item'] || $menu->description != $inputs['description'] ) {
                $inputs['flag'] = MenuItemFlagsEnum::EDIT_STORE_MENU;
            }
        }
        if ($menuItem) {
            $menuItem->update($inputs);
        }

        return new MenuItemResource($menuItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($uuid)
    {
        $menuItem = EventMenuItem::where('uuid', $uuid)->firstOrFail();
        $menuItem->delete();
        return response()->json(null, SymfonyResponse::HTTP_NO_CONTENT);
    }
}
