<?php

namespace Tests\Feature\Http\Resources;

use App\Enums\EventStatus as EventStatusEnum;
use App\Http\Resources\Foodfleet\EventStatus as EventStatusResource;
use App\Models\Foodfleet\EventStatus as EventStatusModel;
use Illuminate\Http\Request;
use Tests\TestCase;

class EventStatusTest extends TestCase {
    public function getDataProvider()
    {
        return [
            [ EventStatusEnum::DRAFT, 'grey' ],
            [ EventStatusEnum::FF_INITIAL_REVIEW, 'warning' ],
            [ EventStatusEnum::CUSTOMER_AGREEMENT, 'warning' ],
            [ EventStatusEnum::FLEET_MEMBER_SELECTION, 'warning' ],
            [ EventStatusEnum::CUSTOMER_REVIEW, 'warning' ],
            [ EventStatusEnum::FLEET_MEMBER_CONTRACTS, 'warning' ],
            [ EventStatusEnum::CONFIRMED, 'success' ],
            [ EventStatusEnum::CANCELLED, 'error' ],
            [ EventStatusEnum::PAST, 'grey' ],
        ];
    }

    /** @dataProvider getDataProvider
     * @param $statusId
     * @param $color
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function testResource ($statusId, $color) {
        $eventStatus = factory(EventStatusModel::class)->make([
            'id' => $statusId
        ]);
        $resource = new EventStatusResource($eventStatus);
        $expected = [
          'id' => $eventStatus->id,
          'name' => $eventStatus->name,
          'color' => $color,
        ];
        $request = app()->make(Request::class);
        $this->assertEquals($expected, $resource->toArray($request));
    }
}
