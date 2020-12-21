<?php

namespace Tests\Feature\Http\Resources\Foodfleet;


use App\Enums\PaymentStatus as Enum;
use App\Http\Resources\Foodfleet\Square\PaymentStatus as Resource;
use App\Models\Foodfleet\Square\PaymentStatus;
use Illuminate\Http\Request;
use Tests\TestCase;

class PaymentStatusTest extends TestCase{

    public function getDataProvider () {
        return [
            [Enum::PENDING, 'grey'],
            [Enum::OVERDUE, 'warning'],
            [Enum::FAILED, 'error'],
            [Enum::REFUNDED, 'orange'],
            [Enum::PAID, 'success']
        ];
    }

    /** @dataProvider getDataProvider
     * @param $statusId
     * @param $color
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testResource ($statusId, $color) {
        $status = factory(PaymentStatus::class)->make([
            'id' => $statusId
        ]);
        $resource = new Resource($status);
        $expected = [
            'id' => $status->id,
            'name' => $status->name,
            'color' => $color,
        ];
        $request = app()->make(Request::class);
        $this->assertArraySubset($expected, $resource->toArray($request));
    }
}
