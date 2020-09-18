<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\LocationCategory as LocationCategoryResource;
use App\Models\Foodfleet\LocationCategory;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocationCategoryTest extends TestCase
{

    public function testResource()
    {
        $category = factory(LocationCategory::class)->create();
        $resource = new LocationCategoryResource($category);
        $expected = [
            "id" => $category->id,
            "name" => $category->name,
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
