<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Http\Resources\Foodfleet\Store\StoreArea as StoreAreaResource;
use App\Models\Foodfleet\StoreArea as StoreAreaModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class StoreAreaTest extends TestCase {

    public function testResource () {
        $area = factory(StoreAreaModel::class)->create();
        $resource = new StoreAreaResource($area);
        $expected = [
            'id' => $area->id,
            'name' => $area->name,
            'radius' => $area->radius,
            'state' => $area->state,
            'store_uuid' => $area->store_uuid
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
        $this->assertArrayHasKey('store', $result);
    }
}
