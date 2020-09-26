<?php

namespace Tests\Feature\Http\Resources;


use App\Http\Resources\Foodfleet\MenuItem as MenuItemResource;
use App\Models\Foodfleet\MenuItem as MenuItemModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class MenuItemTest extends TestCase
{
    public function testResource ()
    {
        $menuItem = factory(MenuItemModel::class)->create();
        $resource = new MenuItemResource($menuItem);
        $request = app()->make(Request::class);
        $expected = [
            "uuid" => $menuItem->uuid,
            "title" => $menuItem->title,
            "description" => $menuItem->description,
            "servings" => $menuItem->servings,
            "cost" => $menuItem->cost,
            "store_uuid" => $menuItem->store_uuid,
        ];
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
