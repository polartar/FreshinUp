<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Enums\StoreStatus;
use App\Http\Resources\Foodfleet\Store\StoreStatus as StoreStatusResource;
use App\Models\Foodfleet\StoreStatus as StoreStatusModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class StoreStatusTest extends TestCase {


    public function getDataProvider () {
        return [
            [StoreStatus::DRAFT, 'accent'],
            [StoreStatus::PENDING, 'warning'],
            [StoreStatus::REVISION, 'success'],
            [StoreStatus::REJECTED, 'error'],
            [StoreStatus::APPROVED, 'success'],
            [StoreStatus::ON_HOLD, 'accent'],
        ];
    }

    /**
     * @dataProvider getDataProvider
     * @param $id
     * @param $color
     */
    public function testResource ($id, $color) {
        $storeStatus = factory(StoreStatusModel::class)->make([
            'id' => $id
        ]);
        $resource = new StoreStatusResource($storeStatus);
        $expected = [
            'id' => $id,
            'value' => $id,
            'name' => $storeStatus->name,
            'text' => $storeStatus->name,
            'color' => $color
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
