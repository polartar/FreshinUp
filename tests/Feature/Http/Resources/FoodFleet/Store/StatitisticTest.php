<?php


namespace Tests\Feature\Http\Resources\Foodfleet\Store;

use App\Enums\StoreStatus as StoreStatusEnum;
use App\Http\Resources\Foodfleet\Store\Statistic as StatisticResource;
use Illuminate\Http\Request;
use Tests\TestCase;

class StatisticTest extends TestCase
{

    public function getStatuses () {
        return [
            [StoreStatusEnum::DRAFT, 'Draft', 'grey'],
            [StoreStatusEnum::PENDING, 'Pending', 'warning'],
            [StoreStatusEnum::REVISION, 'Revision', 'success'],
            [StoreStatusEnum::REJECTED, 'Rejected', 'error'],
            [StoreStatusEnum::APPROVED, 'Approved', 'success'],
            [StoreStatusEnum::ON_HOLD, 'On hold', 'accent'],
        ];
    }

    /**
     * @dataProvider getStatuses
     * @param $status
     * @param $name
     * @param $color
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testResource($status, $name, $color)
    {
        $payload = [
            'id' => $status,
            'color' => $color,
            'stores_count' => 12,
            'name' => $name
        ];
        $resource = new StatisticResource(json_decode(json_encode($payload)));
        $expected = [
            "label" => $name,
            "value" => 12,
            "color" => $color,
        ];
        $request = app()->make(Request::class);
        $result = $resource->toArray($request);
        $this->assertArraySubset($expected, $result);
    }
}
