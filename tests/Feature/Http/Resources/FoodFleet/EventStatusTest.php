<?php

namespace Tests\Feature\Http\Resources\Foodfleet;

use App\Enums\EventStatus as EventStatusEnum;
use App\Http\Resources\Foodfleet\EventStatus;
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

    public function getDescriptionProvider () {
        return [
            [EventStatusEnum::DRAFT, 'Event was created in the system and submitted for approval'],
            [EventStatusEnum::FF_INITIAL_REVIEW, 'Food Fleet Staff will review the event request'],
            [EventStatusEnum::CUSTOMER_AGREEMENT, 'Customer will review / sign event agreement and terms'],
            [EventStatusEnum::FLEET_MEMBER_SELECTION, 'FoodFleet will define event menu and identify interested Fleet Members'],
            [EventStatusEnum::CUSTOMER_REVIEW, 'Customer will review interested Fleet Members and authorize work order'],
            [EventStatusEnum::FLEET_MEMBER_CONTRACTS, 'Approved Fleet Members will review and sign event contracts'],
            [EventStatusEnum::CONFIRMED, 'Customer will review and sign the final event contract'],
            [EventStatusEnum::CANCELLED, ''],
            [EventStatusEnum::PAST, ''],
        ];
    }

    /**
     * @dataProvider getDescriptionProvider
     * @param $statusId
     * @param $description
     */
    public function testDescription ($statusId, $description) {
        $this->assertEquals(EventStatus::getDescriptionFor($statusId), $description);
    }
}
